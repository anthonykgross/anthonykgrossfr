<?php
if(isset($_POST['action'])) { // Checking for submit form
	$my_email	= "dotstheme@gmail.com"; // Your email address
	if($_POST['action']=='add') {
		$name		= trim(strip_tags(addslashes($_POST['name'])));
		$email		= trim(strip_tags(addslashes($_POST['email'])));
		$subject	= trim(strip_tags(addslashes($_POST['subject'])));
		$message	= trim(strip_tags(addslashes($_POST['message'])));
		$pattern	= '/^[^\W][a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\@[a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\.[a-zA-Z]{2,4}$/';
		
		if($email != "" && $message != "") {
			if(preg_match($pattern, $email)) {
				$messages	= "Name: " . $name . "\r\n" . $message;
				$headers 	= "From: " . $email . "\r\n";
				mail($my_email,$subject,$messages,$headers);
				echo "success|<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>×</button>Send a message process completed.</div>";
			} else {
				echo "error|<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>×</button>Please enter a valid email address.</div>";	
			}
		} else {
			echo "error|<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>×</button>Please fill all the required fields.</div>";	
		}
	}
} else { // Submit form false
	header("Location: index.html");	
}
?>