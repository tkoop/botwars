<?php

require("BotWars.php");
$bw = new BotWars();

$bots = $bw->getBots($_GET["key"]);

echo json_encode($bots);
