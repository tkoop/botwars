<?php

$botId = $_GET["botId"];
$botId = preg_replace("/[^A-Za-z0-9\\-]/", '', $botId);
$fileContents = file_get_contents("../bots/".$botId.".json");
$bot = json_decode($fileContents, true);

$rollNumber = isset($_GET["rollNumber"]) ? $_GET["rollNumber"] : 1;
$yourScore = isset($_GET["yourScore"]) ? $_GET["yourScore"] : 0;
$opponentsScore = isset($_GET["opponentsScore"]) ? $_GET["opponentsScore"] : 1;
$turnPoints = isset($_GET["turnPoints"]) ? $_GET["turnPoints"] : 1;
$code = $bot["code"];

$rollNumber = preg_replace("/[^0-9]/", '', $rollNumber);
$yourScore = preg_replace("/[^0-9]/", '', $yourScore);
$opponentsScore = preg_replace("/[^0-9]/", '', $opponentsScore);
$turnPoints = preg_replace("/[^0-9]/", '', $turnPoints);


$script = <<<END

var rollNumber = {$rollNumber} 
var yourScore = {$yourScore}
var opponentsScore = {$opponentsScore}
var turnPoints = {$turnPoints}

function eval(str) {
    return null
}

function require() {
    return null
}

function include() {
    return null
}

function move() {
    {$code}
}

console.log(!!move())

END;



$descriptorspec = array(
    0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
    1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
 );
 
 
 $process = proc_open('nodejs', $descriptorspec, $pipes);
 
 if (is_resource($process)) {
     // $pipes now looks like this:
     // 0 => writeable handle connected to child stdin
     // 1 => readable handle connected to child stdout
     // Any error output will be appended to /tmp/error-output.txt
 
     fwrite($pipes[0], $script);
     fclose($pipes[0]);
 
     $return = stream_get_contents($pipes[1]);
     fclose($pipes[1]);

     $shouldRoll = (trim($return) == "true");

     echo json_encode(["shouldRoll"=>$shouldRoll]);
 
     // It is important that you close any pipes before calling
     proc_close($process);

} else {
    echo "{error:'bad resource'}";
}

