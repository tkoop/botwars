<html>
    <head>
        <title>Botwars: <?= htmlentities($title) ?></title>
        <link rel="icon" href="/favicon.png"  type="image/png">
    </head>
<body>

<link rel="stylesheet" href="style.css">
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet">


<div class="content">

<img src="logo.png" style="width:100%">

<div class="menu">
    <a href="/index.php" class="<?= $_SERVER["REQUEST_URI"] == "/index.php" ? "active":"" ?>">About Pig</a>
    <a href="/play.php" class="<?= $_SERVER["REQUEST_URI"] == "/play.php" ? "active":"" ?>">Play The Game</a>
    <a href="/bot.php" class="<?= $_SERVER["REQUEST_URI"] == "/bot.php" ? "active":"" ?>">Create Your Bot</a>
    <a href="/fight.php" class="<?= $_SERVER["REQUEST_URI"] == "/fight.php" ? "active":"" ?>">Fight Your Bot</a>
    <a href="/submit.php" class="<?= $_SERVER["REQUEST_URI"] == "/submit.php" ? "active":"" ?>">Submit Your Bot To Competition</a>
</div>

