<?php


class BotWars {
    private $nodeCommand = 'nodejs';


    public function getBots($personalKey=null) {

        $personal = [];
        $contest = [];
        
        $files = scandir(realpath(__DIR__) . "/../bots");
        
        foreach($files as $file) {
            if ($file[0] == ".") continue;
        
            $parts = explode(".", $file);
        
            if ($parts[1] == "json") {
                $bits = explode("-", $parts[0]);
                if (count($bits) == 1) continue;
                
                if ($bits[0] == "personal") {
                    if ($bits[1] == $personalKey) {
                        $personal[] = [
                            "type" => "personal",
                            "name" => "Your bot",
                            "id" => $bits[1],
                        ];
                    }
                }
        
                if ($bits[0] == "contest") {
                    $bot = json_decode(file_get_contents(realpath(__DIR__) . "/../bots/".$file), true);
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
        
        if (count($contest) == 0) {
            $random = rand(0,99999) . rand(0,99999) . rand(0,99999);
            $bot = '{"rank": 1, "name": "The Two Step", "author": "Botwars", "passkey": "aoiefjuaheaoiefhauiwea", "code": "return rollNumber <= 2"}';
            file_put_contents(realpath(__DIR__) . "/../bots/contest-aoiefjuaheaoiefhauiwea".$random.".json", $bot);
            $contest[] = [
                "type" => "contest",
                "rank" => 1,
                "name" => "The Two Step",
                "author" => "Botwars",
                "id" => "aoiefjuaheaoiefhauiwea" . $random,
            ];
        }

        usort($contest, function($a, $b) {
            return $a["rank"] <=> $b["rank"];
        });

        return ["personal"=>$personal, "contest"=>$contest];
    }


    public function move($botId, $rollNumber, $yourScore, $opponentsScore, $turnPoints) {

        $botId = preg_replace("/[^A-Za-z0-9\\-]/", '', $botId);
        $fileContents = file_get_contents(realpath(__DIR__) . "/../bots/".$botId.".json");
        $bot = json_decode($fileContents, true);
        
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

        function fetch() {
            return null
        }

        function move() {
            {$code}
        }
        
        console.log(move() ? "true":"false")
        
END;
        
        
        return $this->runScript($script);
        
    }


    private function runScript($script) {
        
        $descriptorspec = array(
            0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
            1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
         );
         
         
         $process = proc_open($this->nodeCommand, $descriptorspec, $pipes);
         
         if (is_resource($process)) {
             // $pipes now looks like this:
             // 0 => writeable handle connected to child stdin
             // 1 => readable handle connected to child stdout
             // Any error output will be appended to /tmp/error-output.txt
         
             fwrite($pipes[0], $script);
             fclose($pipes[0]);
         
             $return = stream_get_contents($pipes[1]);
             fclose($pipes[1]);
        
             // It is important that you close any pipes before calling
             proc_close($process);
        
             return $return;
        
        } else {
            return "{error:'bad resource'}";
        }
    }


    public function war() {
        $bots = $this->getBots()["contest"];
        if (count($bots) == 0) return;

        usort($bots, function($bot1, $bot2) {
            if ($bot1["name"] < $bot2["name"]) return -1;
            if ($bot1["name"] > $bot2["name"]) return 1;

            if ($bot1["author"] < $bot2["author"]) return -1;
            if ($bot1["author"] > $bot2["author"]) return 1;

            return 0;
        });

        usort($bots, function($bot1, $bot2) {
            $result = json_decode($this->fight("contest-".$bot1["id"], "contest-".$bot2["id"], 200), true);

            if ($result["firstWins"] > $result["secondWins"]) return -1;
            if ($result["firstWins"] < $result["secondWins"]) return 1;
            return 0;
        });

        for($i=0; $i<count($bots); $i++) {
            $bot = $bots[$i];
            $fileContents = file_get_contents(realpath(__DIR__) . "/../bots/contest-".$bot["id"].".json");
            $b = json_decode($fileContents, true);
            $b["rank"] = $i+1;
            file_put_contents(realpath(__DIR__) . "/../bots/contest-".$bot["id"].".json", json_encode($b));
        }

    }


    public function getRandomNumbers() {
        if (file_exists(realpath(__DIR__). "/../bots/seed.json")) {
            $seed = json_decode(file_get_contents(realpath(__DIR__) . "/../bots/seed.json"), true);
        } else {
            $seed = mt_rand(0, 999999);
            file_put_contents(realpath(__DIR__) . "/../bots/seed.json", $seed);
        }

        srand($seed);

        $numbers = [];
        for($i=0; $i<5000; $i++) {
            $numbers[] = mt_rand(1, 6);
        }
        return $numbers;
    }


    public function fight($bot1, $bot2, $times=1) {
        $thisBotId = preg_replace("/[^A-Za-z0-9\\-]/", '', $bot1);
        $thisBot = json_decode(file_get_contents(realpath(__DIR__) . "/../bots/".$thisBotId.".json"), true);
        $thisCode = $thisBot["code"];
        
        $thatBotId = preg_replace("/[^A-Za-z0-9\\-]/", '', $bot2);
        $thatBot = json_decode(file_get_contents(realpath(__DIR__) . "/../bots/".$thatBotId.".json"), true);
        $thatCode = $thatBot["code"];
        
        $printLog = $times == 1 ? "true" : "false";

        $randoms = json_encode($this->getRandomNumbers());
        
        $script = <<<END

        var randoms = {$randoms}
        var randomsIndex = 0
        
        function eval(str) {
            return null
        }
        
        function require() {
            return null
        }

        function fetch() {
            return null
        }

        function include() {
            return null
        }
        
        function thisRoll(rollNumber, yourScore, opponentsScore, turnPoints) {
            function thatRoll() {
                return true
            }
            function run() {
                return null
            }
            var randoms = []
            var randomsIndex = 0
            {$thisCode}
        }
        
        function thatRoll(rollNumber, yourScore, opponentsScore, turnPoints) {
            function thisRoll() {
                return true
            }
            function run() {
                return null
            }
            var randoms = []
            var randomsIndex = 0
            {$thatCode}
        }
        
        function run() {
            var log = ""
            var thisScore = 0
            var thatScore = 0
        
            var turnPoints
            var done = false
        
            log += "Starting the game.\\n"
        
            var doneTurn
            var rollNumber
        
        
        
            while (thisScore < 100 && thatScore < 100) {
                done = false
                doneTurn = false
                rollNumber = 0
                turnPoints = 0
                while (!doneTurn) {
                    rollNumber++
                    var shouldRoll = thisRoll(rollNumber, thisScore, thatScore, turnPoints)
                    log += "Does your bot roll? " + (shouldRoll ? "Yes":"No") + ". "
                    if (shouldRoll) {
                        var die = randoms[randomsIndex]
                        randomsIndex = (randomsIndex+1) % randoms.length
                        if (die == 1) {
                            turnPoints = 0
                            doneTurn = true
                        } else {
                            turnPoints += die
                        }
                        log += "rollNumber="+rollNumber+", turnPoints=" + turnPoints + ", score=" + thisScore + ", die=" + die + "\\n" 
                    } else {
                        thisScore += turnPoints
                        doneTurn = true
                        log += "rollNumber="+rollNumber+", turnPoints=" + turnPoints + ", score=" + thisScore + "\\n" 
                    }
                }
        
                log += "Your bot's score: "+thisScore+", that bot's score: " + thatScore + "\\n\\n"
        
                doneTurn = false
                rollNumber = 0
                turnPoints = 0
                rollNumber = 0
                if (thisScore < 100) {
                        while (!doneTurn) {
                        rollNumber++
                        var shouldRoll = thatRoll(rollNumber, thatScore, thisScore, turnPoints)
                        log += "Does that bot roll? " + (shouldRoll ? "Yes":"No") + ". "
                        if (shouldRoll) {
                            var die = randoms[randomsIndex]
                            randomsIndex = (randomsIndex+1) % randoms.length
                            if (die == 1) {
                                turnPoints = 0
                                doneTurn = true
                            } else {
                                turnPoints += die
                            }
                            log += "rollNumber="+rollNumber+", turnPoints=" + turnPoints + ", score=" + thisScore + ", die=" + die + "\\n" 
                        } else {
                            thatScore += turnPoints
                            doneTurn = true
                            log += "rollNumber="+rollNumber+", turnPoints=" + turnPoints + ", score=" + thisScore + "\\n" 
                        }
                    }
        
                    log += "Your bot's score: "+thisScore+", that bot's score: " + thatScore + "\\n\\n"
                }
            }
        
            var winner = "bot"
            if (thisScore > thatScore) winner = "you"
            if ({$printLog}) {
                console.log(JSON.stringify({winner:winner, log:log}))
            } else {
                return winner
            }
        }
        
        if ({$printLog}) {
            run()
        } else {
            var firstWins = 0
            var secondWins = 0

            for(var i=0; i<{$times}; i++) {
                if (run() == "you") {
                    firstWins++
                } else {
                    secondWins++
                }
            }
            console.log(JSON.stringify({firstWins, secondWins}))
        }
        
        
END;
        

        return $this->runScript($script);
    }



    public function compete($bot1, $bot2) {

    }

}

