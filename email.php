<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

ini_set('allow_url_fopen',1);

require './vendor/autoload.php'; // If you're using Composer (recommended)
// Comment out the above line if not using Composer
// require("<PATH TO>/sendgrid-php.php");
// If not using Composer, uncomment the above line and
// download sendgrid-php.zip from the latest release here,
// replacing <PATH TO> with the path to the sendgrid-php.php file,
// which is included in the download:
// https://github.com/sendgrid/sendgrid-php/releases

if (isset($_POST['subscribe_form']) && isset($_POST['recaptcha_response'])) {

          // Build POST request:
          $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
          $recaptcha_secret = '6LdNxvEUAAAAAL8tc4Ck4An4QT0uMvFGsomndSMD';
          $recaptcha_response = $_POST['recaptcha_response'];

          // Make and decode POST request:
          $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
          $recaptcha = json_decode($recaptcha);

          // Newsletter - Take action based on the score returned:
          if ($recaptcha->score >= 0.5) {
              // Verified - send email
                  $emaildata = $_POST['maildata'];

                  $email = new \SendGrid\Mail\Mail();
                  $email->setFrom("sitecallquotes@gmail.com", "GAQuoters.com");
                  $email->setSubject("New Newsletter Subscription");
                  $email->addTo("JayWalkerAllstate@gmail.com", "GAQuoters.com Visitor");
                  $email->addContent("text/plain", "Check Details");
                  $email->addContent("text/html", "<strong>{$emaildata}</strong>"
                  );
                  $sendgrid = new \SendGrid('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');
                  try {
                      $response = $sendgrid->send($email);
                      print $response->statusCode() . "\n";
                      print_r($response->headers());
                      print $response->body() . "\n";
                  } catch (Exception $e) {
                      echo 'Caught exception: '. $e->getMessage() ."\n";
                  }
          } else {
              // Not verified - show form error
          }

} else {

        if(isset($_SESSION['agent_email']) && !empty($_SESSION['agent_email'])) {
           $agent_email = $_SESSION["agent_email"];
           $agent_email = strtolower($agent_email);
           $agent_phone = $_SESSION["agent_phone"];
           $company_name = $_SESSION["company_name"];
           $agent_name = $_SESSION["agent_name"];
        } else {
        	$agent_email = 'jaywalkerallstate@gmail.com';
        	$agent_phone = '6788095100';
        	$company_name = 'GAquoters';
        	$agent_name = 'Jay Walker';
        }

        	$emaildata = $_POST['maildata'];

        $email = new \SendGrid\Mail\Mail();
        $email->setFrom("sitecallquotes@gmail.com", "GAQuoters.com");
        $email->setSubject("New Insurance Client");
        $email->addTo($agent_email, "GAQuoters.com Visitor");
        $email->addContent("text/plain", "Check Details");
        $email->addContent("text/html", "<strong>{$emaildata}</strong>"
        );
        $sendgrid = new \SendGrid('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');
        try {
            $response = $sendgrid->send($email);
            print $response->statusCode() . "\n";
            print_r($response->headers());
            print $response->body() . "\n";
        } catch (Exception $e) {
            echo 'Caught exception: '. $e->getMessage() ."\n";
        }

        /* Send Email Copy */
        if ($agent_email == 'jaywalkerallstate@gmail.com' || $agent_phone == '6788095100' || $agent_phone == '+16788095100' ) {
          // code...
        } else {

              $msg = '<h4>This is the email copy of - </h4>';
              $msg .= '<h5> Agent Name : '. $agent_name .' </h5>';
              $msg .= '<h5> Company Name : '. $company_name .' </h5>';
              $msg .= '<h5> Phone : '. $agent_phone .' </h5>';
              $msg .= '<h5> Email : '. $agent_email .' </h5>';
              $msg .= '-------------------***---------------- <br><br>';

              $email = new \SendGrid\Mail\Mail();
              $email->setFrom("sitecallquotes@gmail.com", "GAQuoters.com");
              $email->setSubject("New Insurance Client");
              $email->addTo("gaquotersclients@gmail.com", "GAQuoters.com Visitor");
              $email->addContent("text/plain", "Check Details");
              $email->addContent("text/html", "<strong>{$msg} {$emaildata}</strong>"
              );
              $sendgrid = new \SendGrid('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');
              try {
                  $response = $sendgrid->send($email);
                  print $response->statusCode() . "\n";
                  print_r($response->headers());
                  print $response->body() . "\n";
              } catch (Exception $e) {
                  echo 'Caught exception: '. $e->getMessage() ."\n";
              }

        }
}
