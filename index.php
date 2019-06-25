<?php 


// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);

include_once "modules/modules.php";
$var="IN";
$sql=new Data($pdo,'user'); // в данном примере $pdo -> глобальная перемнная для утсановки подключения. Указанные параметрыобязательны, второй название иаблицы. 
$auto=new Autorize();
$auto->logIn("a","v");
/*
В данном примере создается таблица user подробнее про второй параметр можно прочитать в самом методе
 $rows[0]=["name"=>"userName","type"=>"varchar","length"=>255];
 $rows[1]=["name"=>"age","type"=>"int"];
 $rows[2]=["name"=>"wegth","type"=>"real","length"=>"5,2"];
 $sql->AddTable($rows);

*/
// print_r($sql->get("id>5"));
// print_r($sql->count);

// $r=["userName","age","wegth"];
// $d=["Wert",58,120];
// $sql->insert($r,$d);
// print_r($sql->count);	
$link=new Link;
$css=$link->getCSS('html/css'); /// создает <link> для всех css в указанной папке
$js=$link->getJS('html/css');   /// создает <script> для всех js файлов по указанному пути

// $r1=["userName"=>"Ruslan","age"=>27,"wegth"=>67];
// $sql->insertFetch($r1);


include "html/logIn.html";
?>