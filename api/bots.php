<?php

$personal = [];
$contest = [];

$files = scandir("../bots");

foreach($files as $file) {
    if ($file[0] == ".") continue;

    $parts = explode(".", $file);

    if ($parts[1] == "json") {
        $bits = explode("-", $parts[0]);
        if (count($bits).length == 1) continue;
        
        if ($bits[0] == "personal") {
//            $bot = json_decode(file_get_contents("../bots/".$file), true);
            if ($bits[1] == $_GET["key"]) {
                $personal[] = [
                    "type" => "personal",
                    "name" => "Your bot",
                    "id" => $bits[1],
                ];
            }
        }

        if ($bits[0] == "contest") {
            $bot = json_decode(file_get_contents("../bots/".$file), true);
            $contest[] = [
                "type" => "contest",
                "rank" => $bot["rank"],
                "name" => $bot["name"],
                "author" => $bot["author"],
                "id" => $bits[1],
            ];
        }

    }
}

echo json_encode(["personal"=>$personal, "contest"=>$contest]);
