<?php


require("BotWars.php");

$bw = new BotWars();

$move = $bw->move($_GET["botId"], $_GET["rollNumber"], $_GET["yourScore"], $_GET["opponentsScore"], $_GET["turnPoints"]);
$move = trim($move);

echo json_encode(["shouldRoll"=>$move == "true" ? true : false]); 
