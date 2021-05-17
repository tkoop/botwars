<?php


$thatBotId = preg_replace("/[^A-Za-z0-9\\-]/", '', $_GET["id"]);
$thatBot = json_decode(file_get_contents("../bots/".$thatBotId.".json"), true);
$thatCode = $thatBot["code"];

$thisBotId = preg_replace("/[^A-Za-z0-9]/", '', $_GET["key"]);
$thisBot = json_decode(file_get_contents("../bots/personal-".$thisBotId.".json"), true);
$thisCode = $thisBot["code"];


$script = <<<END

function eval(str) {
    return null
}

function require() {
    return null
}

function include() {
    return null
}

function thisRoll(rollNumber, yourScore, opponentsScore, turnPoints) {
    {$thisCode}
}

function thatRoll(rollNumber, yourScore, opponentsScore, turnPoints) {
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
                var die = parseInt(Math.random()*6) + 1
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
                    var die = parseInt(Math.random()*6) + 1
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
    console.log(JSON.stringify({winner:winner, log:log}))
}

run()


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

     echo $return;
 
     // It is important that you close any pipes before calling
     proc_close($process);

} else {
    echo "{error:'bad resource'}";
}




