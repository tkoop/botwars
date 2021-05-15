<?php
$title = "About Pig";
include("head.php");

?>

<div style="display:flex; justify-content: space-around;">

<div style="text-align:center; flex-basis: calc(100% / 2);">
    <h3>You</h3>
    <p><a href="/bot.php">Your Bot</a></p>
</div>

<div style="text-align:center">
    <h3>&nbsp;</h3>
    <button style="padding:12px;" onclick="fight()">Fight!</button>
</div>

<div style="text-align:center; flex-basis: calc(100% / 2);">
    <h3>Bot</h3>
    <select id="bots"></select>
</div>

</div>

<div style="margin-top:30px">
Details of the fight:
</div>

<textarea id="details" style="width:100%; height:200px"></textarea>


<script>

async function fight() {
    var id = $("#bots").val()
    var key = localStorage.getItem("key")
    var data = await fetch("/api/fight.php?id="+id+"&key="+key)
    try {
        var result = await data.json()
        $("#details").val(result.log)
    } catch (error) {
        $("#details").val("Error: " + error)
    }

}

async function populateBots() {
    var fetchData = await fetch("/api/bots.php?key=")
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
}


$(() => {
    populateBots()
})

</script>


<?php
include("tail.php")
?>