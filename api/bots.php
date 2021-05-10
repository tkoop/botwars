<?php

$result = [];

$files = scandir("../bots");

foreach($files as $file) {
    if ($file[0] == ".") continue;

    $parts = explode(".", $file);

    if ($parts[1] == "json") {
        $bot = json_decode(file_get_contents("../bots/".$file), true);
        if ($bot["rank"] > 0) {
            $result[] = [
                "rank" => $bot["rank"],
                "name" => $bot["name"],
                "author" => $bot["author"],
                "id" => $parts[0],
            ];
        }
    }
}

echo json_encode($result);
