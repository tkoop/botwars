<?php


$bot = json_decode(file_get_contents("../bots/aoejfpoahefahekjfahlef.json"), true);


$rollNumber = json_encode(isset($_GET["rollNumber"]) ? $_GET["rollNumber"] : 1);
$yourScore = json_encode(isset($_GET["yourScore"]) ? $_GET["yourScore"] : 0);
$opponentsScore = json_encode(isset($_GET["opponentsScore"]) ? $_GET["opponentsScore"] : 1);
$turnPoints = json_encode(isset($_GET["turnPoints"]) ? $_GET["turnPoints"] : 1);
$code = $bot["code"];

// $rollNumber = json_encode(1);
// $yourScore = json_encode(1);
// $opponentsScore = json_encode(1);
// $turnPoints = json_encode(1);
// $code = "return rollNumber <= 2";

$script = <<<END

var rollNumber = {$rollNumber} 
var yourScore = {$yourScore}
var opponentsScore = {$opponentsScore}
var turnPoints = {$turnPoints}

function move() {
    {$code}
}

console.log(move())

END;



$descriptorspec = array(
    0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
    1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
 );
 
 
 $process = proc_open('node', $descriptorspec, $pipes);
 
 if (is_resource($process)) {
     // $pipes now looks like this:
     // 0 => writeable handle connected to child stdin
     // 1 => readable handle connected to child stdout
     // Any error output will be appended to /tmp/error-output.txt
 
     fwrite($pipes[0], $script);
     fclose($pipes[0]);
 
     $return = stream_get_contents($pipes[1]);
     fclose($pipes[1]);

     echo "Returned: " . $return;
 
     // It is important that you close any pipes before calling
     // proc_close in order to avoid a deadlock
     $return_value = proc_close($process);
 
//     echo "command returned $return_value\n";

} else {
    echo "bad resource";
}

