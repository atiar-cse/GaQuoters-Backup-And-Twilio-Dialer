<?php
	$sender_firstname = $_POST['first_name'];
	$sender_lastname = $_POST['last_name'];
	$sender_email = $_POST['email'];
	$phone = $_POST['phone'];

	$insurance = $_POST['insurance'];
		$insurancetype = $_POST['insurance1'];
		
	
	$body = $sender_name." sent a new message for you<br/><br/>First Name: ".$sender_firstname."<br/>Last Name: ".$sender_lastname."<br/>Email: ".$sender_email."<br>Phone: ".$phone."<br/>Insurance : ".$insurance."<br>Insurance Type: ".$insurancetype;
	
	sendMail($sender_name , $sender_email, $body);
	
	function sendMail($sender, $sender_mail, $body) {
		$to = 'dymond01@yahoo.com'; // Set Receiver Email Here
		$myemail = 'usmanchatha1996@gmail.com'; // Set Sender Email Here
		$subject = "New GaQuoters Client"; // Set Subject Here
		$headers = "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";            
		$headers .= "From: EasyInsurance <info@onushorit.com>\r\n"; // Set Header Here
		
		$message = $body;
		
		$sentmail = mail($to,$subject,$message,$headers);
		if($sentmail) { echo "Request submitted successfully. Thank you for your submission. We will contact with you very soon."; }
		else { echo "Submission Failed"; }
	}

?>