<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require './vendor/autoload.php';
use \Twilio\Rest\Client;

if (!empty($_POST['salesPhone']) ){
    // Get form input

    $userPhone =  urlencode($_POST['userPhone']);
    $firstname =  urlencode($_POST['firstname']);
    $lastname =  urlencode($_POST['lastname']);

    $encodedSalesPhone = urlencode($_POST['salesPhone']);
    // Set URL for outbound call - this should be your public server URL
    $host = $_SERVER['HTTP_HOST'];

    /* @ Start Agent and Lead Info */
        $link = mysqli_connect("localhost", "gaquoter_agents", "Dvv9(x()6{^Y", "gaquoter_agents");

        //set default agent info into session
        $agent_id = 1;
        $agent_email = 'jaywalkerallstate@gmail.com';
        $agent_phone = '+16788095100';
        $_SESSION["agent_id"] = $agent_id;
        $_SESSION["agent_email"] = $agent_email;

        $record = mysqli_query($link, "SELECT * from round_robins WHERE id=1");
        if ($record->num_rows >= 1 ) { //update agent info

      			$row = mysqli_fetch_array($record);
            $current_serial = $row['next_serial'];

            $data = mysqli_fetch_array( mysqli_query($link, "SELECT * from round_robins WHERE id=$current_serial") );
            // echo $data['agent_name'];
            $_SESSION["agent_id"] = $data['id']; // update agent session data
            $_SESSION["agent_email"] = $data['email'];
            $_SESSION["agent_phone"] = $data['phone'];
            $_SESSION["company_name"] = $data['company_name'];
            $_SESSION["agent_name"] = $data['agent_name'];

            //update next serial
            if ($current_serial==10) {
              $new_serial = 1;
            } else {
                $new_serial = $current_serial + 1;
            }

            $sql = "UPDATE round_robins SET next_serial=$new_serial WHERE id=1";
            mysqli_query($link, $sql);
    		}

        //Insert new lead info
        // $user_phone = $sales_phone = $first_name = $last_name = $email = $phone = $insurence = $insurence_type = null;
        $twilio_phone = '+17708022078';
        $user_phone = $_POST['userPhone'];
        $sales_phone = $_POST['salesPhone'];
        $first_name = $_POST['firstname'];
        $last_name = $_POST['lastname'];
        $email = $_POST['email'];
        $insurence = $_POST['insurence'];
        $insurence_type = $_POST['insurence_type'];

        $agent_id = $_SESSION["agent_id"];
        // $timestamp = date("Y-m-d H:i:s");

        $sql = "INSERT INTO leads (twilio_phone, user_phone, sales_phone, first_name, last_name, email, insurence, insurence_type, agent_id, created_at)
                VALUES ('$twilio_phone', '$user_phone', '$sales_phone', '$first_name', '$last_name', '$email', '$insurence', '$insurence_type', '$agent_id', NOW())";
        mysqli_query($link, $sql);
        $lead_id = mysqli_insert_id($link);
        $_SESSION["lead_id"] = $lead_id;

        $get_agent_phone = $_SESSION["agent_phone"];

        mysqli_close($link);
    /*End Agent and Lead Info */

    // Create authenticated REST client using account credentials in
    // <project root dir>/.env.php
    $client = new Client( 'AC066a511bb63ec69c97f526417c2d02a1','2ff54d015f2fa68de36db38c8d63c0ef');

    $outboundUri = "http://$host/twilio_dialer/outbound.php?sales_phone=$encodedSalesPhone&firstname=$firstname&lastname=$lastname&agent_id=$agent_id&lead_id=$lead_id";

    try {
        $client->calls->create(
            ''. $get_agent_phone .'', //'+16788095100', // The visitor's phone number
            '+17708022078', // A Twilio number in your account  // Phone #:+18142906336
            array(
                "url" => $outboundUri
            )
        );
    } catch (Exception $e) {
        // Failed calls will throw
        echo $e;
    }

    print_r('Call Incoming !');
}
?>
