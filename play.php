<?php
$title = "About Pig";
include("head.php");

?>


<div id="board">

    <div id="left">
    <h3 style="text-align: center; margin-bottom:0px">You</h3>

    <div style="height:26px"></div>

    <div style="margin-top:20px">
    Total Score<br>
    <div class="score" id="myTotal">0</div>
    </div>

    <div style="margin-top:20px">
    This Turn (<span id="myRolls">0 rolls</span>)<br>
    <div class="score" id="myTurn">0</div>
    </div>

    <div style="margin-top:20px">
        <button id="rollButton" onclick="roll()">Roll</button><!--
        --><button id="doneButton" onclick="done()" style="margin-left:6px">Done</button>
    </div>

    </div>




    <div>
        <img id="die" src="dice/1.png" style="margin: auto; display: block;">
        <div id="message"></div>
    </div>




    <div id="right">
    <h3 style="text-align: center; margin-bottom:0px;">Bot</h3>

    <select style="width: 215px; padding: 3px; height:26px" id="bots">
    </select>

    <div style="margin-top:20px">
    Total Score<br>
    <div class="score" id="botTotal">0</div>
    </div>

    <div style="margin-top:20px">
    This Turn (<span id="botRolls">0 rolls</span>)<br>
    <div class="score" id="botTurn">0</div>
    </div>


    </div>
</div>


<script>
/*
Game states:
0 = Waiting for user to roll or done
1 = User is rolling
2 = Bot is thinking
3 = User just rolled a non-one
4 = Bot is rolling
5 = Bot is done
6 = Nothing
7 = Bot is done.  It's your turn.
11 = Bot won
*/
var gameState = 0
var myTotalScore = 0
var myTurnScore = 0
var myRollCount = 0
var botTotalScore = 0
var botTurnScore = 0
var botRollCount = 0

function renderState(state) {
    gameState = state

    const states = {
        0:{message:"It's your turn.<br><br>Roll or Done.", roll:true, done:true},
        1:{message:"Rolling...", roll:false, done:false},
        2:{message:"Bot is thinking...", roll:false, done:false},
        3:{appendMessage:"<br><br>Roll or Done.", roll:true, done:true},
        4:{message:"Bot is rolling...", roll:false, done:false},
        5:{message:"Bot is done.", roll:false, done:false},
        6:{appendMessage:"", roll:false, done:false},
        7:{message:"Bot is done.<br><br>It's your turn.<br><br>Roll or Done.", roll:true, done:true},
        8:{message:"Bot rolled a 1.<br><br>It's your turn.<br><br>Roll or Done.", roll:true, done:true},
        10:{message:"You won!", roll:false, done:false, playAgain:true},
        11:{message:"Bot won.<br><br>", roll:false, done:false, playAgain:true},
    }

    var state = states[gameState]

    if (state.appendMessage) {
        $("#message").append($("<span></span>").html(state.appendMessage))
    } else {
        $("#message").html(state.message)
    }

    if (state.playAgain) {
        var botId = $("#bots").val()
        $("#message").append($("<span></span>").html("<br><br><a href='play.php?id="+botId+"'>Play again?</a>"))
    }

    $("#rollButton").prop("disabled", !state.roll)
    $("#doneButton").prop("disabled", !state.done)

    $("#myTotal").text(myTotalScore)
    $("#myTurn").text(myTurnScore)
    $("#myRolls").text(myRollCount)
    $("#botTotal").text(botTotalScore)
    $("#botTurn").text(botTurnScore)
    $("#botRolls").text(botRollCount)
}

function shake() {
    return new Promise((resolve, fail) => {
        const number = parseInt(Math.random() * 6 + 1)
        const degrees = parseInt(Math.random() * 90 - 45)
        const x = parseInt(Math.random() * 20 - 10)
        const y = parseInt(Math.random() * 20 - 10)

        $("#die").attr("src", "dice/" + number + ".png").css("transform", "rotate("+degrees+"deg)").css({left:x+"px", top:y+"px"})

        setTimeout(()=>{
            resolve(number)
        }, 180)
    })
}

async function botThink() {
    renderState(2)

    var response = await fetch("/api/move.php?rollNumber="+botRollCount+"&yourScore="+botTotalScore+"&opponentsScore="+myTotalScore+"&turnPoints="+botTurnScore+"&botId="+$("#bots").val())
    var answer = await response.json()

    if (answer.shouldRoll) {
        botRoll()
    } else {
        botRollCount = 0
        botTotalScore += botTurnScore
        botTurnScore = 0

        if (botTotalScore >= 100) {
            renderState(11)
            return
        }

        setTimeout(() => {
            renderState(7)
        }, 200)
    }
}

async function botRoll() {
    renderState(4)

    await shake()
    await shake()
    await shake()
    await shake()
    await shake()
    var number = await shake()

    if (number == 1) {
        $("#message").html("Bot rolled a 1.")
        botTurnScore = 0
        botRollCount = 0
        renderState(6)
        setTimeout(() => {
            renderState(8)
        }, 2000)
    } else {
        $("#message").html("Bot rolled a " + number + ".")
        botTurnScore += number

        setTimeout(() => {
            renderState(6)  // this updates the score
        }, 1000)

        setTimeout(() => {
            botRollCount++
            botThink()
        }, 2000)
    }
}

function done() {
    myTotalScore += myTurnScore
    myTurnScore = 0
    botRollCount = 1
    myRollCount = 0

    if (myTotalScore >= 100) {
        renderState(10)
        return
    }

    botThink()
}

async function roll() {
    myRollCount++
    renderState(1)

    await shake()
    await shake()
    await shake()
    await shake()
    await shake()
    var number = await shake()


    if (number == 1) {
        $("#message").html("You rolled a 1  :(")
        myTurnScore = 0
        botRollCount = 1
        myRollCount = 0
        setTimeout(() => {
            botThink()
        }, 2000)
    } else {
        $("#message").html("You rolled a " + number + ".")
        myTurnScore += number
        renderState(3)
    }
}

async function populateBots() {
    var fetchData = await fetch("/api/bots.php")
    var bots = await fetchData.json()
    $("#bots").text("")

    bots.forEach(bot => {
        var option = $("<option></option>").prop("value", bot.id).text("#" + bot.rank + " \"" + bot.name + "\" by " + bot.author)
        $("#bots").append(option)
        <?php if (isset($_GET["id"])) { ?>
            $("#bots").val(<?php echo json_encode($_GET["id"]) ?>)
        <?php } ?>
    })

    renderState(0)
}


$(() => {
    populateBots()
})
</script>



<style>
#board {
    display:flex;
    justify-content: space-around;
}
.score {
    border: 1px solid black;
    padding: 12px;
    text-align: right;
    width: 190px;
    background-color: #fffee6;
}
button {
    width:105;
    height:40px;
    margin-bottom:12px;
    font-size:larger;
}
#message {
    margin-top: 24px; 
    font-style: italic;
    width: 120px;
    text-align:center;
}
#die {
    position:relative;
}
</style>


<?php
include("tail.php")
?>