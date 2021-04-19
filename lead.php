<?php

if (!empty($_POST['salesPhone']) ){
    // Get form input

    $userPhone =  urlencode($_POST['userPhone']);
    $firstname =  urlencode($_POST['firstname']);
    $lastname =  urlencode($_POST['lastname']);

    $encodedSalesPhone = urlencode($_POST['salesPhone']);

    /* @ Start Agent and Lead Info */
        $link = mysqli_connect("localhost", "gaquoter_agents", "Dvv9(x()6{^Y", "gaquoter_agents");

        //set default agent info into session
        $agent_id = 1;
        $agent_email = 'jaywalkerallstate@gmail.com';
        $agent_phone = '+16788095100';

        $record = mysqli_query($link, "SELECT * from round_robins WHERE id=1");
        if ($record->num_rows >= 1 ) { //update agent info

      			$row = mysqli_fetch_array($record);
            $current_serial = $row['next_serial'];

            $data = mysqli_fetch_array( mysqli_query($link, "SELECT * from round_robins WHERE id=$current_serial") );
            // echo $data['agent_name'];
            $agent_id = $data['id'];

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

        // $timestamp = date("Y-m-d H:i:s");

        $sql = "INSERT INTO leads (twilio_phone, user_phone, sales_phone, first_name, last_name, email, insurence, insurence_type, agent_id, created_at)
                VALUES ('$twilio_phone', '$user_phone', '$sales_phone', '$first_name', '$last_name', '$email', '$insurence', '$insurence_type', '$agent_id', NOW())";
        mysqli_query($link, $sql);

        // $lead_id = mysqli_insert_id($link);

        mysqli_close($link);
    /*End Agent and Lead Info */
}
?>
