<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!------ Include the above in your HEAD tag ---------->
<link href="html/css/logIn.css" rel="stylesheet" id="bootstrap-css">
<script src="html/js/logIn.js"></script>
<div class="login-reg-panel">
							
		<div class="register-info-box">
			<h2>Не знаете данные для Входа!?</h2>
			<p>Нажите на кнопку ниже.</p>
			<label id="label-login" for="log-login-show">Запросить</label>
			<input type="radio" name="active-log-panel" id="log-login-show">
		</div>
							
		<div class="white-panel">
			<div class="login-show">
				<h2>ВХОД &darr; {{%$var%}}  <?php echo($var);?> </h2>

				<input type="text" placeholder="Email">
				<input type="password" placeholder="пароль">
				<input type="button" value="Login">
				<a href="">Заабыли пароль?</a>
			</div>
			<div class="register-show">
				<h2>РЕГИСТРАЦИЯ</h2>
				<input type="text" placeholder="Email">
				<input type="password" placeholder="Password">
				<input type="password" placeholder="Confirm Password">
				<input type="button" value="Register">
			</div>
		</div>
	</div>