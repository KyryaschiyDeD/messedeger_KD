<?php 
require "../bd/db.php";

if( isset($_SESSION['logged_user']) ) 
{	
	$user=R::load('users',$_SESSION['logged_user']->id);
	$user->status=0;
	$user->time_exit= $rdate = date("d-m-Y в H:i");
	R::store($user); 
	unset($_SESSION['logged_user']); 
}
echo '<meta http-equiv="refresh" content="1;URL=http://timber2602.beget.tech/">';
?>