<html>
    <head>
        <title>Botwars: <?= htmlentities($title) ?></title>
        <link rel="icon" href="/favicon.png"  type="image/png">
    </head>
<body>

<link rel="stylesheet" href="style.css">
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<div class="content">

<img src="logo.png" style="width:100%">

<div class="menu">
    <a href="/index.php" class="<?= $_SERVER["REQUEST_URI"] == "/index.php" || $_SERVER["REQUEST_URI"] == "/" ? "active":"" ?>">About This</a>
    <a href="/play.php" class="<?= $_SERVER["REQUEST_URI"] == "/play.php" ? "active":"" ?>">Play The Game</a>
    <a href="/bot.php" class="<?= $_SERVER["REQUEST_URI"] == "/bot.php" ? "active":"" ?>">Create Your Bot</a>
    <a href="/fight.php" class="<?= $_SERVER["REQUEST_URI"] == "/fight.php" ? "active":"" ?>">Fight Your Bot</a>
    <a href="/submit.php" class="<?= $_SERVER["REQUEST_URI"] == "/submit.php" ? "active":"" ?>">Submit Your Bot To Competition</a>
</div>

<?php 
if (isset($_SESSION["errorMessage"])) {
    ?>
    <p style="color:darkred"><?php echo htmlentities($_SESSION["errorMessage"]) ?></p>
    <?php
    unset($_SESSION["errorMessage"]);
} 
if (isset($_SESSION["message"])) {
    ?>
    <p style="color:darkgreen"><?php echo htmlentities($_SESSION["message"]) ?></p>
    <?php
    unset($_SESSION["message"]);
} 
?>
