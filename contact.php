<?php
ini_set('allow_url_fopen',1);

if (isset($_POST['recaptcha_response'])) {

    // Build POST request:
    $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
    $recaptcha_secret = '6LdNxvEUAAAAAL8tc4Ck4An4QT0uMvFGsomndSMD';
    $recaptcha_response = $_POST['recaptcha_response'];

    // Make and decode POST request:
    $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
    $recaptcha = json_decode($recaptcha);

    // Take action based on the score returned:
    if ($recaptcha->score >= 0.5) {
        // Verified - send email

							$sender_name = $_POST['InputName'];
							$sender_email = $_POST['InputEmail'];
							$phone = $_POST['InputPhone'];
							$input_zip_code = $_POST['InputZipCode'];
							$mail_body = $_POST['InputMessage'];

							$body = $sender_name." sent a new message for you<br><br> Name: ".$sender_name."<br>Email: ".$sender_email."<br>Phone: ".$phone."<br>Zip Code: ".$input_zip_code."<br>Message: ".$mail_body;

							sendMail($sender_name , $sender_email, $body);

    } else {
        // Not verified - show form error
    }
}

function sendMail($sender, $sender_mail, $body) {
	$to = 'jaywalkerallstate@gmail.com'; // Set Receiver Email Here
	$myemail = 'sitecallquotes@gmail.com'; // Set Sender Email Here
	$subject = "New EasyInsurance Client"; // Set Subject Here
	$headers = "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
	$headers .= "From: EasyInsurance <Receiver@domain.com>\r\n"; // Set Header Here

	$message = $body;

	$sentmail = mail($to,$subject,$message,$headers);
	if($sentmail) {

			header("Location: https://www.gaquoters.com/thankyou.html");

	}
	else { echo "Mail not sent"; }
}

?>
