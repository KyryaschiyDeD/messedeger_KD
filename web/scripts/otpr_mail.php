<? 
			$data = $_POST;
			$prov = $data['rnd'];
			$to = trim($data['mail']);
  			$subject = "Месседжер";
  			$message = "Код подтвердждения: ".(string)$prov;
  			mail ($to, $subject, $message);
?>