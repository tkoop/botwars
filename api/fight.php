<?php


require("BotWars.php");

$bw = new BotWars();

echo $bw->fight("personal-".$_GET["key"], $_GET["id"]);

