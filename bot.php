<?php
$title = "About Pig";
include("head.php");

?>

<h3>Bot</h3>

<p>
Your code will have access to these variables:
</p>

<div style="white-space:pre; font-family:mono">
rollNumber // The number of the roll in this turn. The first roll of the turn is 1.
yourScore // Your current score, not including points from this turn
opponentsScore // Your opponent's current score
turnPoints  // The total amount of points earned during this turn so far, which may or may not make it into yourScore
</div>

<p>
Your code should return a boolean (true or false), which answers the question: Do you want to roll again?
</p>

<?php
include("tail.php")
?>