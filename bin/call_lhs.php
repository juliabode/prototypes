<?php

include_once('config.php');

$url           = "https://api.lufthansa.com/v1/operations/flightstatus/" . $_REQUEST['dataString'][0] . "/" . $_REQUEST['dataString'][1] . "?api_key=" . $config['api_key_lhs'];
$url2          = "https://api.lufthansa.com/v1/operations/flightstatus/" . $_REQUEST['dataString'][2] . "/" . $_REQUEST['dataString'][3] . "?api_key=" . $config['api_key_lhs'];
$ch            = curl_init($url);
$ch2           = curl_init($url2);

curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

curl_setopt($ch2, CURLOPT_HTTPHEADER, array('Accept: application/json'));
curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);

$response_body = curl_exec($ch);
$status        = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$response_body2 = curl_exec($ch2);
$status2        = curl_getinfo($ch2, CURLINFO_HTTP_CODE);

if (!curl_errno($ch) && !curl_errno($ch2)) {

    if (intval($status) != 200) throw new Exception("HTTP $status\n$response_body");

    if (intval($status2) != 200) throw new Exception("HTTP $status\n$response_body2");

} else {
    $response_body = file_get_contents('response_example_01.json');
    $response_body2 = file_get_contents('response_example_02.json');
}

$response = json_decode($response_body);
$listings = $response;
$response2 = json_decode($response_body2);
$listings2 = $response2;

//$var = '$';
//var_dump($listings->FlightStatusResource->Flights->Flight->Arrival->AirportCode->$var);

?>

<div class="wrapper column day-one">
    <header>
        <h2>Day 1<br>14 Dec</h2>
    </header>

    <div class="content">
        <div class="my-input">
            <p>I reached <span class="yellow"><?php echo $listings->FlightStatusResource->Flights->Flight->Arrival->AirportCode; ?></span> at <span class="yellow"><?php echo $listings->FlightStatusResource->Flights->Flight->Arrival->ScheduledTimeLocal->DateTime; ?></span></p>
        </div>
        <p>What do you want to add to your diary?</p>
        <p>
            <a class="button add-text" onclick="addText(this)">Text</a>
        </p>
        <p>
        <a class="button add-image" onclick="addImage(this)">Image</a></p>
        <p>
        <a class="button add-sound" onclick="addSound(this)">Music</a></p>
   </div>

</div>

<div class="wrapper column day-two">
    <header>
        <h2>Day 2<br>15 Dec</h2>
    </header>

    <div class="content">
        <div class="my-input">
            </div>
        <p>What do you want to add to your diary?</p>
        <p>
            <a class="button">Text</a>
        </p>
        <p>
        <a class="button">Image</a></p>
        <p>
        <a class="button">Music</a></p>
    </div>
</div>

<div class="wrapper column day-three">
    <header>
        <h2>Day 3<br>16 Dec</h2>
    </header>

    <div class="content">
        <div class="my-input">
            <p>I arrived <span class="yellow"><?php echo $listings2->FlightStatusResource->Flights->Flight->Arrival->AirportCode; ?></span> at <span class="yellow"><?php echo $listings2->FlightStatusResource->Flights->Flight->Arrival->ScheduledTimeLocal->DateTime; ?></span></p>
        
            </div>
        <p>What do you want to add to your diary?</p>
        <p>
            <a class="button">Text</a>
        </p>
        <p>
        <a class="button">Image</a></p>
        <p>
        <a class="button">Music</a></p>
    </div>
</div>
