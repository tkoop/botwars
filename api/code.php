<?php

$botId = $_GET["key"];
$botId = preg_replace("/[^A-Za-z0-9]/", '', $botId);
$fileContents = file_get_contents("../bots/personal-".$botId.".json");
echo $fileContents;

