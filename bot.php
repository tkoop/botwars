<?php

include("start.php");

if (isset($_POST["code"])) {
    $key = preg_replace("/[^A-Za-z0-9]/", '', $_POST["passkey"]);

    if (strlen($key) < 32) {
        $_SESSION["errorMessage"] = "Your passkey is not long enough.";
        header("Location: /bot.php");
        exit();
    }

    $bot = [
        "code" => $_POST["code"]
    ];

    $written = file_put_contents("bots/personal-" . $key . ".json", json_encode($bot));
    $_SESSION["message"] = "Your bot was saved.";

    if ($_POST["action"] == "Save") header("Location: /bot.php");
    if ($_POST["action"] == "Save and Play") header("Location: /play.php");
    if ($_POST["action"] == "Save and Fight") header("Location: /fight.php");

    exit();
}

$title = "About Pig";
include("head.php");

?>

<h3>Bring Your Bot To Life!</h3>

<p>This is how:  Write some JavaScript that returns a true or false, based on whatever variables you want.  A true value means "roll again."  A false value means "Done."  These are the variables you have access to:</p>

<p style="white-space:pre; font-family:mono">const rollNumber;<span class="comment"> // The number of the roll in this turn. The first roll of the turn is 1.</span>
const yourScore;<span class="comment"> // Your current score, not including points from this turn</span>
const opponentsScore;<span class="comment"> // Your opponent's current score</span>
const turnPoints;<span class="comment">  // The total amount of points earned during this turn so far, which may or may not make it into yourScore</span>
</p>


<form method="post" onsubmit="localStorage.setItem('code', $('#code').val())">

<textarea id="code" name="code"></textarea><br>

<input type="submit" value="Save" name="action">
<input type="submit" value="Save and Play" name="action">
<input type="submit" value="Save and Fight" name="action">

<input type="hidden" name="passkey" id="personalKey" value="">

</form>


<script>
$(async () => {
    $("#personalKey").val(localStorage.getItem("key"))
    try {
        var data = await fetch("/api/code.php?key="+localStorage.getItem("key"))
        var bot = await data.json()
        var code = bot.code
        console.log(code)
        if (code != null) {
            $("#code").val(code)
        } else {
            $("#code").val("// This simple bot just rolls (up to) two times.\n\nreturn rollNumber <= 2")
        }
    } catch(exceptione) {
        $("#code").val("// This simple bot just rolls (up to) two times.\n\nreturn rollNumber <= 2")
    }
})
</script>


<style>
.comment {
    color:gray;
    font-size:smaller;
    font-family: 'Open Sans', sans-serif;
}
#code {
    width:100%;
    font-family:mono;
    height:200px;
}
</style>

<?php
include("tail.php")
?>