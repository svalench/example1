<?php
$host = '127.0.0.1';  // поменять под себя (обычно везде стандартная настройка)
$db   = 'smart';    // указать название ДБ
$user = 'astexpert';   // поменять под себя
$pass = 'astexpert';    // плменять под себя
$charset = 'utf8';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$opt = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
try {
$pdo = new PDO($dsn, $user, $pass, $opt);
} catch (PDOException $e) {
    echo 'Соединение оборвалось: ' . $e->getMessage();
    exit;
}

function temp($html){     // шаблонизатор для html 
	$pathHTMLDir=$_SERVER['DOCUMENT_ROOT']."/html/";			// указать путь к папке с html
	$path=$pathHTMLDir.$html.".html";
	$content=file_get_contents($path);
	$content = str_replace("{{%", "<?php echo(", $content);
	$content = str_replace("%}}", ");?>", $content);
	return $content;
}

class Data{
    private $conn,$tableName;
    public $count,$columnCount;
    /* Get database access */
    public function __construct(PDO $pdo, $table) {
        $this->conn = $pdo;
        $this->tableName = $table;
    }
     public function getAll() {  // получает все данные из таблтицы
       $res=$this->conn->prepare("SELECT * FROM `".$this->tableName."`;");
     $res->execute();
       $this->columnCount = $res->columnCount();
       $this->count = $res->rowCount();
        return $res->fetchAll();
    }

    public function get($where) {  // получает данные из таблтицы по условию $where
        $res= $this->conn->query("SELECT * FROM ".$this->tableName." WHERE $where;");
        $res->execute();
        $this->columnCount = $res->columnCount();
        $this->count = $res->rowCount();
        return $res->fetchAll();
    }

     public function getLast($row="id") {   // получает последнюю строку из таблицы
        return $this->conn->query("SELECT * FROM ".$this->tableName." ORDER BY $row desc;")->fetchColumn();
    }

         public function getFerst($row="id") {   // получает первую строку из таблицы
        return $this->conn->query("SELECT * FROM ".$this->tableName." ORDER BY $row;")->fetchColumn();
    }

       public function deleteAll() {  /// очищает табилцу
        return $this->conn->exec("TRUNCATE  FROM ".$this->tableName.";");
    }

          public function delete($where) {  // удоляет строки по условию $where прим $where="RowName>=12"
        return $this->conn->exec("DELETE  FROM ".$this->tableName." WHERE $where;");

    }

       public function insert($rows,$data) {  // вставляет данные в таблица со столбуами в $rows и значениями в $data
       	$strRow="";
       	$strData="";
       	foreach ($rows as $key => $value) {
       		$strRow.=" `$value`,";
       	}
       	foreach ($data as $key => $value) {
       		if(is_string($value)){
       			$value= $this->conn->quote($value);
       		}
       		$strData.=" $value,";	
       }
       	$strData = substr($strData, 0, -1);
       	$strRow = substr($strRow, 0, -1);
        $this->conn->exec("INSERT  INTO  `".$this->tableName."` ($strRow) VALUES ($strData);");

    }


     public function insertFetch($rows) {   // вставляет даные со столбцми в виде ключей и данными как значения этих ключей
       	$strRow="";
       	$strData="";
       	foreach ($rows as $key => $value) {
       		$strRow.=" $key,";
       	if(is_string($value)){
       			$value= $this->conn->quote($value);
       		}
       		$strData.=" $value,";	
       		
       }
       	$strData = substr($strData, 0, -1);
       	$strRow = substr($strRow, 0, -1);
        $this->conn->exec("INSERT  INTO  `".$this->tableName."` ($strRow) VALUES ($strData);");

    }


    public function addTable($rows){
    	$strRow="CREATE TABLE `".$this->tableName."` (id INT( 255 ) AUTO_INCREMENT PRIMARY KEY,";
    	/*
			Объект $rows должен быть вида
			[
			'name'=> имя столбца,
			'type'=> VARCHAR,INT,TEXT,DATE,TEXT и т.д.
			'length'=> параметр для столбца длина, кол-во точек после запятой и т.д.(смотри спцификацию Mysql)
			'charset'=> по умолчанию utf8
			'compare'=> параметр сравнения для VARCHAR по умолченю utf8_general_ci
			'NULL'=> по умолчаню включен, для отаключения установить в 1
			]
    	*/
			if(count($rows)<=0){ print_r("При попытке созданиы таблицы произошла ошибка. Второй параметр пуст!"); exit();}
    	foreach ($rows as $key => $value) {
    		$name=$value['name'];
    		$type=$value['type'];
    		$length=$value['length'];
    		$compare=$value['compare'];
    		$charset=$value['charset'];
    		$null=$value['NULL'];
    		
    		if((!$length || $length=="") && ($type=="VARCHAR" || $type=="INT" || $type=="int"  || $type=='intager')){
    			$length=255;
    		}elseif((!$length || $length=="") && $type=="DATE"){
				$lenth="0000-00-00";
			}
    		if(!$compare || $compare==""){
    			$compare="utf8_general_ci";
    		}
    		if(!$charset || $charset==""){
    			$charset="utf8";
    		}
    		if($type=="text" || $type=="TEXT"){
    			$strRow.=" $name $type";
    		}else{
    			$strRow.=" $name $type($length)";
    		}
    		if($type=="VARCHAR" || $type=="varchar"){
    			$strRow.=" CHARACTER SET $charset COLLATE $compare";
    		}
    		
    		if(!$null || $null==""){
    			$strRow.=" NULL";
    		}else{
    			$strRow.=" NOT NULL";
    		}
    		
    	$strRow.=", ";
    	}
    	$strRow = substr($strRow, 0, -2);
    	$strRow.="); ";
    	print_r($strRow);
    	return $this->conn->exec($strRow);
    }

}


class Link{

    public function getCSS($link){ /// создает <link> для всех css в указанной папке
    	if(is_dir($link)) {   //проверяем наличие директории
         $files = scandir($link);    //сканируем (получаем массив файлов)
         array_shift($files); // удаляем из массива '.'
         array_shift($files); // удаляем из массива '..'
         $strCSS="";
         for($i=0; $i<sizeof($files); $i++){
             $format = array_pop(explode(".",$files[$i]));  

             if( $format == 'css'){
                 $strCSS.='<link href="'.$link."/".$files[$i].'" rel="stylesheet" >';  //выводим все файлы
             }
              if($strCSS==""){return "Нет CSS файлов";}
         }
    } else{return "Нет директории";}
        
    return $strCSS;
    }

        public function getJS($link){  /// создает <script> для всех js файлов по указанному пути
    	if(is_dir($link)) {   //проверяем наличие директории
         $files = scandir($link);    //сканируем (получаем массив файлов)
         array_shift($files); // удаляем из массива '.'
         array_shift($files); // удаляем из массива '..'
         $strJS="";
         for($i=0; $i<sizeof($files); $i++){
             $format = array_pop(explode(".",$files[$i]));  

             if( $format == 'js'){
                 $strJS.='<script src="'.$link."/".$files[$i].'"></script>';  //выводим все файлы
             }
             if($strJS==""){return "Нет JS файлов";}
         }
    } else{return "Нет директории JS";}
        
    return $strJS;
    }
}

class Autorize{  // класс для добавления пользователя и проверки авторизации, так же логаут
	protected $ip,$agent,$tableUser,$pdo;
	public function __construct($table,$pdo=$pdo) {
        $this->ip = $_SERVER['REMOTE_ADDR'];
        $this->agent = $_SERVER['HTTP_USER_AGENT'];
        $this->tableUser=$table;
        session_start();
    }
    public function LogIn($email,$pass){
    	if (preg_match("/^(?:[a-z0-9]+(?:[-_.]?[a-z0-9]+)?@[a-z0-9_.-]+(?:\.?[a-z0-9]+)?\.[a-z]{2,5})$/i", $email)) {
			$login=new Data($pdo,$this->tableUser);
			$passMD=md5($pass);
			$user=$login->get("pass=$passMD AND email=$email");
			if(is_array($user)){
				$_SESSION['ip']=$this->ip;
				$_SESSION['agent']=$this->agent;
				$_SESSION['token']=$this->Token();
			}
		}else{
			return "Email не корректен!";
		}
    }


    private function Token(){
    	return	md5(uniqid($this->ip.$this->agent, true));
    }

}

?>