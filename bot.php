<?php

if (isset($_POST["code"])) {
    var $bot = [
        "type" => "personal",
        "passkey" => $_POST["passkey"],
        "code" => $_POST["code"]
    ];
    header("Location: /bot.php");
    exit();
}

$title = "About Pig";
include("head.php");

?>

<h3>Bring Your Bot To Life!</h3>

<p>
Your JavaScript code will have access to these variables:
</p>

<div style="white-space:pre; font-family:mono">const rollNumber;<span class="comment"> // The number of the roll in this turn. The first roll of the turn is 1.</span>
const yourScore;<span class="comment"> // Your current score, not including points from this turn</span>
const opponentsScore;<span class="comment"> // Your opponent's current score</span>
const turnPoints;<span class="comment">  // The total amount of points earned during this turn so far, which may or may not make it into yourScore</span>
</div>

<p>
Your code should return a boolean (true or false), which answers the question: Do you want to roll again?
</p>

<form method="post">

<textarea id="code" name="code">// This simple bot just rolls (up to) two times.

return rollNumber <= 2</textarea><br>

<input type="submit" value="Save" name="action">
<input type="submit" value="Save and Play" name="action">
<input type="submit" value="Save and Fight" name="action">

<input type="hidden" name="passkey" id="personalKey" value="">

</form>


<script>
$(() => {
    $("#personalKey").val(localStorage.getItem("key"))
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