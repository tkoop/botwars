<?php
$title = "About Pig";
include("head.php");

include("api/BotWars.php");
$bw = new BotWars();

function createRandom() {
    $letters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $result = '';
    for($i=0; $i<32; $i++) {
        $result .= $letters[rand(0, strlen($letters)-1)];
    }
    return $result;
}

if (isset($_POST["name"])) {
    $bot = [
        "rank" => 0,
        "name" => $_POST["botName"],
        "author" => $_POST["name"],
        "passkey" => $_POST["passkey"],
        "code" => $_POST["code"]
    ];

    $fileName = "bots/contest-" . createRandom() . ".json";
    file_put_contents($fileName, json_encode($bot));

}

$bw->war();

?>

<div style="display:flex; justify-content: space-around;">

<div>
<h3>Submit your Bot</h3>

<form method="post" id="theForm">
    <p>
    Name your bot:<br>
    <input type="text" name="botName" style="width:150px">
    </p>

    <p>
    Your name:<br>
    <input type="text" name="name" style="width:150px">
    </p>

    <input type="hidden" name="code" value="">
    <input type="hidden" name="passkey" value="">

    <p>
    <input type="submit" value="Go to War" style="width: 105; height: 40px; margin-bottom: 12px; font-size: larger; width:150px">
    </p>

</form>

</div>

<script>
$(() => {
    $("#theForm input[name=code]").val(localStorage.getItem("code"));
    $("#theForm input[name=passkey]").val(localStorage.getItem("key"));
})
</script>

<div>
<h3>Battle Rankings</h3>
<?php
$bots = $bw->getRankings();
foreach($bots as $bot) {
    ?>
    <div>#<?php echo $bot["rank"] . " \"" . htmlentities($bot["name"]) . "\", by " . htmlentities($bot["author"]) ?></div>

    <?php
}
?>
</div>

</div>

<?php
include("tail.php")
?>