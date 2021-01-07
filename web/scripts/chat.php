<? require_once("../bd/db.php"); 



	function load() {
	$echo = "";
	if ((isset($_SESSION['logged_user']))) {
		$result = R::findAll('messedjes'); 
		if($result) {
			if(count($result) >= 1) {
				foreach($result as $result_now)
				{
					$user_result = (int)$result_now->who_otpr;
						$user = R::findOne('users', 'id = ?', [$user_result]);
						if ($user->tr_fam_nam)
						{
							$nam_log = $user->real_name .' '. $user->real_fam;
						} else
						{
							$nam_log = $user->login;
						}
						if ($_SESSION['logged_user']->login == $user->login)
							$echo .= "<div style='margin-right: 10px; word-wrap: break-word;' class='text-right pr-5 col-10'> $result_now->message<b>: $nam_log </b></div>"; 
						else
							$echo .= "<div style='word-wrap: break-word;' class='text-left'><b>$nam_log:</b> $result_now->message</div>";

				}
			
			} else {
				$echo = "Нет сообщений!";
			}
		}
		else {
				$echo = "Нет сообщений!";
		}
	}
		else
		{
			$echo = "Ошибка авторизации";
		}
	return $echo;
	
}

	function send($message) {
		if ((isset($_SESSION['logged_user']))) {
	
		$message = htmlspecialchars($message);
		$message = trim($message); 
		$message = addslashes($message); 
		$message = transformEmoji($message);
		$res = R::dispense('messedjes');
		$res->who_otpr = $_SESSION['logged_user']->id;
		$res->message = $message;
		$res->date_time = $rdate = date("d-m-Y в H:i");
		R::store($res);
}
	return load($db);

}



function load_to($id_1,$id_2) {
	$echo = "";
	if (
		(isset($_SESSION['logged_user']))
		and
			(
			($_SESSION['logged_user']->id==(int)($id_1))
			or
			($_SESSION['logged_user']->id==(int)($id_2)) 
			)
		)
		 {
		$st_load = 'messedjes_'.$id_1.'_'.$id_2;
		$result = R::findAll($st_load);
		if($result) {
			if(count($result) >= 1) {
				foreach($result as $result_now)
				{
					$user_result = (int)$result_now->who_otpr;
						$user = R::findOne('users', 'id = ?', [$user_result]);
						if ($user->tr_fam_nam)
						{
							$nam_log = $user->real_name .' '. $user->real_fam;
						} else
						{
							$nam_log = $user->login;
						}
						if ($_SESSION['logged_user']->login == $user->login)
							$echo .= "<div style='margin-right: 10px; word-wrap: break-word;' class='text-right pr-5'> $result_now->message<b>: $nam_log </b></div>";
						else
							$echo .= "<div style='word-wrap: break-word;' class='text-left'><b>$nam_log:</b> $result_now->message</div>";

				}
			
			} else {
				$echo = "Нет сообщений!";
			}
		}
		else {
				$echo = "Нет сообщений!";
		}
	}
		else
		{
			$echo = "Ошибка авторизации";
		}
	return $echo;
	
}

function send_to($message, $id_1, $id_2) {
		if (
		(isset($_SESSION['logged_user']))
		and
			(
			($_SESSION['logged_user']->id == (int)($id_1))
			or
			($_SESSION['logged_user']->id == (int)($id_2)) 
			)
		)
		{
		
		$message = htmlspecialchars($message);
		$message = trim($message);
		$message = addslashes($message); 
		$message = transformEmoji($message);
		R::ext('xdispense', function($table_name){
			return R::getRedBean()->dispense($table_name);
		});
		
		$sttt = 'messedjes_'.$id_1.'_'.$id_2;
		$res = R::xdispense($sttt);
		$res->who_otpr = $_SESSION['logged_user']->id;
		$res->message = $message;
		$res->date_time = $rdate = date("d-m-Y в H:i");
		R::store($res);
		}
	return load_to($id_1,$id_2); 
}

if(isset($_POST['act'])) 
{
	$act = $_POST['act'];
}

if(isset($_POST['var1'])) 
{
	$var1 = $_POST['var1'];
}


if ($_POST['act'] == 'loadmessages')
{
	$echo = load(); 
}
else
if ($_POST['act'] == 'sendmessages')
{
	if(isset($var1)) {
			$echo = send($var1);
	}
}
else
if ($_POST['act'] == 'load-emojis')
{
	$echo = getEmojis();
}
else
{
	$str = $_POST['act'];
	$new_str = explode("_", $str);
	if ($new_str[0] == 'sendmessages')
	{
		if ((int)$new_str[1] < (int)$new_str[2])
			$echo = send_to($var1, $new_str[1], $new_str[2]);
		else
		if ((int)$new_str[2] < (int)$new_str[1])
			$echo = send_to($var1, $new_str[2], $new_str[1]);
	}
	else
	if ($new_str[0] == 'loadmessages')
	{
		
		if ((int)$new_str[1] < (int)$new_str[2])
			$echo = load_to($new_str[1], $new_str[2]);
		else
		if ((int)$new_str[2] < (int)$new_str[1])
			$echo = load_to($new_str[2], $new_str[1]);
	}
		
}


echo $echo;
//echo $ec;
?>