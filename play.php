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
    <div class="score" id="youTotal">0</div>
    </div>

    <div style="margin-top:20px">
    This Turn (<span id="botRolls">0 rolls</span>)<br>
    <div class="score" id="youTurn">0</div>
    </div>

    <div style="margin-top:20px">
        <button>Roll</button><button style="margin-left:6px">Done</button>
    </div>

    </div>




    <div>
    <img src="dice/1.png">
    </div>




    <div id="right">
    <h3 style="text-align: center; margin-bottom:0px;">Bot</h3>

    <select style="width: 215px; padding: 3px; height:26px">
        <option>#1 "Alice", by Tim Koop</option>
        <option>#2 "Bob", by Tim Koop</option>
        <option>#3 "Super Pig Beater", by Tim Koop</option>
        <option>#4 "Thsi Bot is Better Than Your Bot", by The Pig Farmer</option>
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
}
button {
    width:105;
    height:40px;
    margin-bottom:12px;
    font-size:larger;
}
</style>


<?php
include("tail.php")
?>