<!doctype html>
<html>
<head lang="ru">
	<meta charset="utf-8">
	<title>Месседжер</title>
	<link rel="stylesheet" href="css/bootstrap/bootstrap.min.css" crossorigin="anonymous">
	<link rel="stylesheet" href="css/main.css" crossorigin="anonymous">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<? include("scripts/menu.php"); ?>
</head>
<?
		require_once "bd/db.php";
	//echo '<div style="color: green;">БД подключена</div><hr>';
	//var_dump($_POST);
	$data = $_POST;
	//echo '<div style="color: green;">Массив перенесён</div><hr>';
	//echo '<div style="color: green;">Конпка нажата</div><hr>';
if ( isset($data['do_registration']) )
	{
	$data['login'] = stripslashes($data['login']);
    $data['login'] = htmlspecialchars($data['login']);
 	$data['password'] = stripslashes($data['password']);
    $data['password'] = htmlspecialchars($data['password']);
	$errors = array();
	//echo '<div style="color: green;">Массив ошибок создан</div><hr>';

	if( trim($data['login'])=='')
	  {
		 $errors[]='Логин пуст'; 
	  }
	if (strlen($data['login'])>20)
	{
		$errors[]='Длина логина не может превышать 20 символов'; 
	}
	if( trim($data['mail'])=='')
	  {
		$errors[]='Email пуст'; 
	  }
	if( $data['password']=='')
	  {
		$errors[]='Введите пароль'; 
	  }  
	if( $data['password_two']!=$data['password'])
	  {
		$errors[]='Пароли не совпадают'; 
	  }
	if( R::count('users', "login = ?", array($data['login'])) > 0 )
	  {
		$errors[]='Введите другой логин'; 
	  }
	if( R::count('users', "email = ?", array($data['mail'])) > 0 )
	  {
		$errors[]='Введите другой email'; 
		}
		//echo '<div style="color: green;">На ошибки проверено</div><hr>';
		if( empty($errors))
		{
			//echo '<div style="color: green;">Ошибок нет</div><hr>';
			$user = R::dispense('users');
			$img = R::dispense('profimages');
			//echo '<div style="color: green;">Доступ к БД получен</div><hr>';
			$img -> whocreate = $data['login'];
			R::store($img);
			//echo '<div style="color: green;">Создана запись изображения профиля</div><hr>';
			$user->login = $data['login'];
			$user->email = $data['mail'];
			$user->password = password_hash($data['password'], PASSWORD_DEFAULT);
			//echo '<div style="color: green;">Пароль зашифрован</div><hr>';
			$user->registration_date = $rdate = date("d-m-Y в H:i");
			$user->server_ip = $_SERVER['SERVER_ADDR'];
			$user->user_ip = $_SERVER['REMOTE_ADDR'];
			$prov = rand(1000,1000000000);
			$to = trim($data['mail']);
  			$subject = "Месседжер";
  			$message = "Код подтвердждения: ".(string)$prov;
  			mail ($to, $subject, $message);
			$user->proverka = $prov;
			$user->verifity_mail  = 0;
			R::store($user); 
			$pr = rand(1,2);
			echo '<div class="container">
					<div style="position: absolute;" class="col-6 align-middle absolut_center text-center pt-5p">
						<h2 style="color: green;">Успешно</h2>';
			if ($pr==1)
				echo'<img style"height: 250px; max-width: 50%;" src="http://timber2602.beget.tech/images/info/like.png">';
				else
			if ($pr==2)
				echo'<img style"height: 250px; max-width: 50%;" src="http://timber2602.beget.tech/images/info/like_2.png">';
					echo'</div>
				</div>';
			echo '<meta http-equiv="refresh" content="2;URL=http://timber2602.beget.tech">';
		}
			else
		{
			echo '<div style="position: absolute; color: red;" class="col-6 align-middle absolut_center text-center"><h4>'.array_shift($errors).'</h4></div>';
		}
}
?>
<body>
	<div class="col-6 text-center align-middle absolut_center pt-10p">
		<form action="" enctype="multipart/form-data" method="post">
  			<div class="mb-3">
				<label for="Email" class="form-label"><h3>Email</h3></label>
				<input type="email" class="form-control border_radius_15" name="mail" id="Email1" aria-describedby="email"></input>
  			</div>
			<div class="mb-3">
				<label for="login" class="form-label"><h3>Логин</h3></label>
				<input type="login" class="form-control border_radius_15" name="login" id="login" aria-describedby="login"></input>
  			</div>
  			<div class="mb-3">
				<label for="Password1" class="form-label"><h3>Пароль</h3></label>
				<input type="password" class="form-control border_radius_15" name="password" id="Password1"></input>
  			</div>
			<div class="mb-3">
				<label for="Password2" class="form-label"><h3>Повтор пароля</h3></label>
				<input type="password" class="form-control border_radius_15" name="password_two" id="Password2"></input>
  			</div>
			<button type="submit" name="do_registration" class="btn btn-primary border_radius_15 col-4">Зарегистрироваться</button>
		</form>
		<form action="http://timber2602.beget.tech">
			<button type="submit" class="btn border_radius_15 col-4 btn-light pt-10">Вход</button>
         </form>
	</div>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
	<script src="js/bootstrap/bootstrap.min.js" crossorigin="anonymous"></script>
</body>

</html>