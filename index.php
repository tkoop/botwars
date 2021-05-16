<?php
$title = "About Pig";
include("head.php");

?>

<h3>How to Botwar</h3>
<ol>
    <li>Familiarize yourself with the game of Pig, by playing the game.</li>
    <li>Create your own bot in JavaScript that can play the game itself.</li>
    <li>Test your bot by playing it against yourself or by fighting other bots.</li>
    <li>Once your bot is awesome, submit it to competition against other bots.</li>
</ol>


<h3>Rules of Pig</h3>
<ul>
<li>Players take turns rolling a die.</li>
<li>When it's a player's turn, he can roll as many times as he wants, collecting points (one point for each number shown on the die).</li>
<li>When he decides to quit rolling, all the points he rolled during that turn get added to his total.</li>
<li>But if he rolls a one, he gets no points for that turn and his turn is over.</li>
<li>The first person to 100 points wins.</li>
</ul>


<h3 style="margin-top:31px">Your Personal Key</h3>
<input id="key" type="text" style="width:100%;padding:4px; font-family: monospace;" ><br>
<div id="save" style="display:none; padding-top:8px"><a href="#" onclick="save()">Save</a></div>
<div style="padding-top:8px">This is your personal key.  Use it to log in on any computer. You will stay logged in here.</div>


<script>
function save() {
    localStorage.setItem("key", $("#key").val())
    $("#save").slideUp()
}

function logIn() {
    $("#key").val("").focus()
}

function generate() {
    var chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890"
    var key = ""
    for(var i=0; i<32; i++) {
        key += chars[parseInt(Math.random() * chars.length)]
    }
    return key
}

$(() => {
    var key = localStorage.getItem("key")
    if (key == null) {
        key = generate()
        localStorage.setItem("key", key)
    }

    $("#key").val(key)

    $('#key').on("keyup", function(e) {
        if (e.keyCode == 13) {
            e.preventDefault();
            e.stopPropagation();
            save();
        }
    });

    $("#key").on("focus keydown", function() {
        $('#save').slideDown()
    })
})
</script>

<?php
include("tail.php")
?>