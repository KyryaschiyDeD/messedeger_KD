<?
header("Content-Type: text/html; charset=UTF-8");
require_once("../bd/db.php");
?>
<html>
<head lang="ru">
	<link rel="shortcut icon" href="../images/emblem.png" type="image/x-icon">
	<link rel="icon" href="../images/emblem.png" type="image/x-icon">
	<meta charset="utf-8">
	<title>Месседжер</title>
	<link rel="stylesheet" href="../css/bootstrap/bootstrap.min.css" crossorigin="anonymous">
	<link rel="stylesheet" href="../css/main.css" crossorigin="anonymous">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<? include("../scripts/menu.php") ?>
</head>
<body>
	<?
		$data = $_POST;
		if (isset($data['save_profil']))
		{
			$user = R::load('users',$_SESSION['logged_user']->id);
			$user -> real_name = $data['names'];
			$user -> real_fam = $data['fam'];
			$user -> tr_fam_nam = $data['tr_fam_nam'];
			R::store($user);
		}
		if (isset($_SESSION['logged_user']))
		{
			$user = R::findOne('users','id = ?', [$_SESSION['logged_user']->id]);
		}
	?>
	<div class="col-5 text-center align-middle absolut_center pt-15p">
		<form action="" enctype="multipart/form-data" method="post">
  			<div class="mb-3">
    			<input placeholder="Имя" type="text" value="<? echo($user->real_name); ?>" name="names" class="form-control border_radius_15" id="names" aria-describedby="names"></input>
				<input placeholder="Фамилия" type="text" value="<? echo($user->real_fam); ?>" name="fam" class="form-control border_radius_15" id="fam" aria-describedby="fam"></input>
  			</div>
  			<div class="form-check">
				<? if ((int)$user->tr_fam_nam): ?>
  				<input class="form-check-input" checked type="checkbox" value="" name="tr_fam_nam" id="check_fam">
				<? else: ?>
				<input class="form-check-input" type="checkbox" value="" name="tr_fam_nam" id="check_fam">
				<? endif; ?>
  				<label class="form-check-label" for="check_fam">
   					Отображать фамилию и имя вместо логина.
  				</label>
			</div>
  			<button name="save_profil" type="submit" class="btn btn-primary border_radius_15 col-4">Сохранить</button>
		</form>
	</div>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
	<script src="../js/bootstrap/bootstrap.min.js" crossorigin="anonymous"></script>
</body>
<footer>
	
</footer>
</html>