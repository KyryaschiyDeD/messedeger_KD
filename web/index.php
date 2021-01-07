<?
header("Content-Type: text/html; charset=UTF-8");
require_once("bd/db.php");
?>
<html>
<head lang="ru">
	<link rel="shortcut icon" href="images/emblem.png" type="image/x-icon">
	<link rel="icon" href="images/emblem.png" type="image/x-icon">
	<meta charset="utf-8">
	<title>Месседжер</title>
	<link rel="stylesheet" href="css/bootstrap/bootstrap.min.css" crossorigin="anonymous">
	<link rel="stylesheet" href="css/main.css" crossorigin="anonymous">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<? include("scripts/menu.php") ?>
	 <script src="js/jquery-3.5.1.min.js"></script>
	
</head>
	<?
	$data = $_POST;
	// Проверка кода подтверждения
	if ( isset($data['do_verifity']))
		{
		$user =  R::load('users', $_SESSION['logged_user']->id);
		if ($data['verifity_code']==$user->proverka)
		{
			$user->verifity_mail = 1;
			R::store($user);
			$pr = rand(1,2);
				  echo '<div class="container">
					<div style="position: absolute;" class="col-6 align-middle absolut_center text-center pt-5p">
						<h2 style="color: green;">Спасибо за подтверждение email!!!</h2>
					<img style"height: 200px; max-width: 50%;" src="http://timber2602.beget.tech/images/info/like_'.(string)$pr.'.png">
					</div>
						</div>';
			echo ('<meta http-equiv="refresh" content="2;">');
		} else {
			echo '<div style="position: absolute; color: red;" class="col-6 align-middle absolut_center text-center"><h4>КОД НЕВЕРЕН!!!</h4></div>';
		}
	}
	// Повторная отправка кода подтверждения
	if ( isset($data['repeat_verifity_code']))
		{
		$user =  R::load('users', $_SESSION['logged_user']->id);
		$prov = rand(1000,1000000000);
		$to = $user->email;
  		$subject = "Месседжер";
  		$message = "Код подтвердждения: ".(string)$prov;
  		mail ($to, $subject, $message);
		$user->proverka = $prov;
		R::store($user);
		echo '<div style="position: absolute; color: green;" class="col-6 align-middle absolut_center text-center"><h3>Код отправлен!</h3></div>';
		echo ('<meta http-equiv="refresh" content="1;">');
	}
	// Вход
	if( isset($data['do_login']))
		{
		$data['login'] = stripslashes($data['login']);
    	$data['login'] = htmlspecialchars($data['login']);
 		$data['password'] = stripslashes($data['password']);
    	$data['password'] = htmlspecialchars($data['password']);
		  $errors = array();
		if (trim($data['login'])=='')
			$errors[] = 'Поле логина пустое';
		if (trim($data['password'])=='')
			$errors[] = 'Поле пароля пустое';
		
		  $user = R::findOne('users', 'login = ?', array($data['login']));
		if ( !$user) 
		{
			$user = R::findOne('users', 'email = ?', array($data['login']));
		}
		
		if ( !$user)
		{
			$errors[] = 'Пользователь с таким логином или email не существует!';
		}
		if( !password_verify($data['password'], $user->password))
		{
			$errors[] = 'Пароль не верен';  
		}
		if(empty($errors))
		{
				  $user->last_IP_user_join = $_SERVER['REMOTE_ADDR'];
				  $user->last_IP_server_join = $_SERVER['SERVER_ADDR'];
				  $user->last_join = $rdate = date("d-m-Y в H:i");
				  $user->status = 1;
				  $_SESSION['logged_user'] = $user;
				  R::store($user);
				  $pr = rand(1,2);
				  echo '<div class="container">
					<div style="position: absolute;" class="col-6 align-middle absolut_center text-center pt-5p">
						<h2 style="color: green;">Успешно</h2>
					<img style"height: 200px; max-width: 50%;" src="http://timber2602.beget.tech/images/info/like_'.$pr.'.png">
					</div>
						</div>
					<meta http-equiv="refresh" content="2;">';
		}
		else
	{
	   echo '<div style="position: absolute; color: red;" class="col-6 align-middle absolut_center text-center"><h4>'.array_shift($errors).'</h4></div>';
	} 
	}
	?>
<body>
	<? if ((isset($_SESSION['logged_user']))): ?>
		<?	$user =  R::findOne('users', 'id = ?', array($_SESSION['logged_user']->id));
		if (($user->verifity_mail==1)) : ?>
			<div class="container-fluid">
		<div class="row">
			<div class="col-4">
				<div class="text-center">
					<h4>Список пользователей</h4>
				</div>
					<ul class="list-group">
					<?
						echo("<a href='http://timber2602.beget.tech'><li class='list-group-item'>Чат всея месседжера</li></a>");
						$users = R::findAll('users');
						$i=0;
						foreach($users as $user)
						{
							if ($user->id == $_SESSION['logged_user']->id)
							{
								echo("<li class='list-group-item'>".$user->login.' - Эт ты </li>');
							} else {
								$i++;
					 		echo("<a href='http://timber2602.beget.tech?$user->id'><li class='list-group-item'>".$i.". ".$user->login.'</li></a>');
							}
					 		
						}
					?>
					</ul>
			</div>
			<?
			
			$url = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
			$masss = parse_url ($url);
			$zap  = $masss[query];
			list($us_to_id, $param) = explode("?", $zap);
			//echo ($us_to_id);
			if ($us_to_id)
			{
				$id_messedge = 'messages_'.$_SESSION['logged_user']->id.'_'.$us_to_id;
			}
			else
			{
				$id_messedge = 'messages';
			}
			
			?>
			<div class="col-8 text-center">
				<? 
				$user_name_id = R::findOne('users','id = ?', [$us_to_id]);
				if ($us_to_id): ?>
				<h4>Диалог с <? echo($user_name_id->login); ?> </h4>
				<? else: ?>
				<h4>Сообщения</h4>
				<? endif; ?>
				<style>
					/* полоса прокрутки (скроллбар) */
::-webkit-scrollbar {
    width: 24px; /* ширина для вертикального скролла */
    height: 8px; /* высота для горизонтального скролла */
    background-color: #143861;
}

/* ползунок скроллбара */
::-webkit-scrollbar-thumb {
    background-color: #843465;
    border-radius: 9em;
    box-shadow: inset 1px 1px 10px #f3faf7;
}

::-webkit-scrollbar-thumb:hover {
    background-color: #253861;
}

/* Стрелки */

::-webkit-scrollbar-button:vertical:start:decrement {
    background: linear-gradient(120deg, #02141a 40%, rgba(0, 0, 0, 0) 41%),
    linear-gradient(240deg, #02141a 40%, rgba(0, 0, 0, 0) 41%),
    linear-gradient(0deg, #02141a 30%, rgba(0, 0, 0, 0) 31%);
    background-color: #f6f8f4;
}

::-webkit-scrollbar-button:vertical:end:increment {
    background:
        linear-gradient(300deg, #02141a 40%, rgba(0, 0, 0, 0) 41%),
        linear-gradient(60deg, #02141a 40%, rgba(0, 0, 0, 0) 41%),
        linear-gradient(180deg, #02141a 30%, rgba(0, 0, 0, 0) 31%);
    background-color: #f6f8f4;
}

::-webkit-scrollbar-button:horizontal:start:decrement {
    background:
        linear-gradient(30deg, #02141a 40%, rgba(0, 0, 0, 0) 41%),
        linear-gradient(150deg, #02141a 40%, rgba(0, 0, 0, 0) 41%),
        linear-gradient(270deg, #02141a 30%, rgba(0, 0, 0, 0) 31%);
    background-color: #f6f8f4;
}

::-webkit-scrollbar-button:horizontal:end:increment {
    background:
        linear-gradient(210deg, #02141a 40%, rgba(0, 0, 0, 0) 41%),
        linear-gradient(330deg, #02141a 40%, rgba(0, 0, 0, 0) 41%),
        linear-gradient(90deg, #02141a 30%, rgba(0, 0, 0, 0) 31%);
    background-color: #f6f8f4;
}
				</style>
				<input id="id_messegd" type="hidden" value=<? echo($id_messedge); ?> />
				<div id="<? echo($id_messedge); ?>" style="height: 40vw; overflow-y: scroll; max-width: 100%; overflow-x: hidden;" class="align-left col-10">
					
				</div>
				
				<div style="height: 10%" class="col-10 text-left">
						<form method='post' action="" id='chat-form'>
							<div class="row">
								<div class="col-10">
									<input type="text" id='message-text' class='col-12 form-control' placeholder="Ваше сообщение" />
								</div>
								<div class="col-2">
									<input type="submit" class="btn btn-info" value="Отправить =>" />
								</div>
							</div>
						</form>
				</div>
				
			</div>
			
		</div>
	
	</div>
		<? else: ?>
		<div class="col-5 text-center align-middle absolut_center pt-15p">
			<h3>Подтвердите почту!!!</h3>
			<form action="" enctype="multipart/form-data" method="post">
  				<div class="mb-3 col-6 absolut_center">
    				<input type="text" name="verifity_code" placeholder="Код подтверждения" class="form-control border_radius_15" id="verifity_code" aria-describedby="verifity_code">
  				</div>
				<button name="do_verifity" type="submit" class="btn btn-primary border_radius_15 col-4">Подтвердить</button><br>
				<button name="repeat_verifity_code" type="submit" class="btn border_radius_15 col-4 btn-light pt-10">Повторно отправить код</button>
        	</form>
		</div>
		<? endif; ?>
	<? else: ?>
	<div class="col-5 text-center align-middle absolut_center pt-15p">
		<form action="" enctype="multipart/form-data" method="post">
  			<div class="mb-3">
    			<label for="login" class="form-label"><h3>Email или Логин</h3></label>
    			<input type="text" name="login" class="form-control border_radius_15" id="login" aria-describedby="login">
  			</div>
  			<div class="mb-3">
    			<label for="Password" class="form-label"><h3>Пароль</h3></label>
    			<input type="password" name="password" class="form-control border_radius_15" id="Password">
  			</div>
  			<button name="do_login" type="submit" class="btn btn-primary border_radius_15 col-4">Войти</button>
		</form>
		<form action="http://timber2602.beget.tech/reg.php">
			<button type="submit" class="btn border_radius_15 col-4 btn-light pt-10">Регистрация</button>
        </form>
	</div> 
	<? endif; ?>
	<script>
		var id_mes = $("#id_messegd").val();
		var messages__container = document.getElementById(id_mes); //Контейнер сообщений — скрипт будет добавлять в него сообщения

		var interval = null; //Переменная с интервалом подгрузки сообщений

		var sendForm = document.getElementById('chat-form'); //Форма отправки
		
		var messageInput = document.getElementById('message-text'); //Инпут для текста сообщения
		
		
		
		//$('#output').text(id_mes);
		
		function send_request(act) 
		{//Основная функция
		//Переменные, которые будут отправляться
			var var1 = null;
			var st = 'send'+id_mes;
			if(act == st) {
				//Если нужно отправить сообщение, то получаем текст из поля ввода
				var1 = messageInput.value;
			}
			
			$.post('scripts/chat.php',
			{ //Отправляем переменные
				act: act,
				var1: var1
			}).done(function (data) 
				{
					messages__container.innerHTML = data;
					var st = 'send'+id_mes;
					if(act == st) 
					{
						//Если нужно было отправить сообщение, очищаем поле ввода
						messageInput.value = '';
					}
				}
			);
		}
		
		function update() {
			var st = 'load'+id_mes;
			send_request(st);
		}
		interval = setInterval(update,500);
		
		sendForm.onsubmit = function () {
			var st = 'send'+id_mes;
			send_request(st);
			return false; //Возвращаем ложь, чтобы остановить классическую отправку формы
			
		//var div = $("#messages");
		//	div.scrollTop(div.prop('scrollHeight'));
		};
	</script>
	<script>

	</script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
	<script src="js/bootstrap/bootstrap.min.js" crossorigin="anonymous"></script>
</body>
<footer>
	
</footer>
</html>