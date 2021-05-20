<?php
$title = "About Pig";
include("head.php");

?>

<div style="display:flex; justify-content: space-around;">

<div style="text-align:center; flex-basis: calc(100% / 2);">
    <h3>You</h3>
    <p><a href="/bot.php">Your Bot</a></p>
    <p style="color:gray" data-wins="0" id="youWins">0 wins</p>
</div>

<div style="text-align:center">
    <h3>&nbsp;</h3>
    <button style="padding:12px;" onclick="fight()">Fight!</button>
    <p style="color:darkgreen; white-space:nowrap; width:77px" id="winner">&nbsp;</p>
</div>

<div style="text-align:center; flex-basis: calc(100% / 2);">
    <h3>Bot</h3>
    <select id="bots"></select>
    <p style="color:gray" data-wins="0" id="botWins">0 wins</p>
</div>

</div>

<div style="margin-top:30px">
Details of the fight:
</div>

<textarea id="details" style="width:100%; height:200px"></textarea>


<script>

async function fight() {
    $("#winner").html("&nbsp;")

    var id = $("#bots").val()
    var key = localStorage.getItem("key")
    var data = await fetch("/api/fight.php?id="+id+"&key="+key)
    try {
        var result = await data.json()
        $("#details").val(result.log)
        if (result.winner == "you") {
            $("#winner").text("You win!").css({color:"darkgreen"})
            var wins = $("#youWins").data("wins") + 1
            $("#youWins").data("wins", wins)
            $("#youWins").text(wins + (wins == 1 ? " win":" wins"))
        } else {
            $("#winner").text("You loose").css({color:"darkred"})
            var wins = $("#botWins").data("wins") + 1
            $("#botWins").data("wins", wins)
            $("#botWins").text(wins + (wins == 1 ? " win":" wins"))
        }
    } catch (error) {
        $("#details").val("Error: " + error)
    }
}


async function populateBots() {
    var fetchData = await fetch("/api/bots.php?key=")   // no key, because we don't want to fight our bot against ourselves
    var bots = await fetchData.json()
    $("#bots").text("")

    bots.personal.forEach(bot => {
        var option = $("<option></option>").prop("value", bot.type + "-" + bot.id).text(bot.name)
        $("#bots").append(option)
    })

    bots.contest.forEach(bot => {
        var option = $("<option></option>").prop("value", bot.type + "-" + bot.id).text("#" + bot.rank + " \"" + bot.name + "\" by " + bot.author)
        $("#bots").append(option)
    })

    $("#bots").change(() => {
        $("#youWins").data("wins", 0).text("0 wins")
        $("#botWins").data("wins", 0).text("0 wins")
        $("#winner").html("&nbsp;")
        $("#details").val("")
    })
}


$(() => {
    populateBots()
})

</script>


<?php
include("tail.php")
?>