<?php
header('Content-Type: text/xml');
session_start();

require './vendor/autoload.php';
use \Twilio\Twiml;

if(!isset($_SESSION["sales_phone"])){
		$queryArgs = array();

		parse_str($_SERVER['QUERY_STRING'], $queryArgs);

		$salesPhone = $queryArgs['sales_phone'];
		$firstname=$queryArgs["firstname"];
		$lastname=$queryArgs["lastname"];
		$_SESSION["sales_phone"]=$queryArgs['sales_phone'];
		$_SESSION["lastname"]=$queryArgs['lastname'];
		$_SESSION["firstname"]=$queryArgs['firstname'];


		$agent_id = $queryArgs["agent_id"];
		$lead_id = $queryArgs["lead_id"];
		$_SESSION["agent_id"]=$queryArgs['agent_id'];
		$_SESSION["lead_id"]=$queryArgs['lead_id'];
} else {
		$salesPhone=$_SESSION["sales_phone"];
		$firstname=$_SESSION["firstname"];
    $lastname=$_SESSION["lastname"];

		$agent_id = $_SESSION["agent_id"];
    $lead_id = $_SESSION["lead_id"];
}

$response = new TwiML();

// If the user entered digits, process their request
if (array_key_exists('Digits', $_POST)) {
    switch ($_POST['Digits']) {
    case 1:
        $response->say('The customer name is '.$firstname.' '.$lastname);
      $response->redirect('/outbound.php');
        break;
    case 2:
        $response->say('I will now call the customer'); //'The system will now dial the number that the customer put in the form!'
        $response->dial($salesPhone);

				$link = mysqli_connect("localhost", "gaquoter_agents", "Dvv9(x()6{^Y", "gaquoter_agents");
				$sql = "UPDATE leads SET calling_status=1, updated_at=NOW() WHERE id=$lead_id AND agent_id=$agent_id";
				mysqli_query($link, $sql);
				mysqli_close($link);
								
        break;
    default:
        $response->say('Sorry, I don\'t understand that choice.');
    }
} else {
    // If no input was sent, use the <Gather> verb to collect user input
    $gather = $response->gather(array('numDigits' => 1));
    // use the <Say> verb to request input from the user
    $gather->say('Press 1 , to get the customer name. Press 2 , to call the customer.'); //'For customer name , press 1. For contacting customer, press 2.'

    // If the user doesn't enter input, loop
    $response->redirect('/outbound.php');
}




print_r((string)$response);
