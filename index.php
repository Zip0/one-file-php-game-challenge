<body style="background-color: grey">

<?php

require_once 'game.php';
require_once 'actor.php';
require_once 'player.php';

exec('php -l config.php', $output);
if ($output[0] === "No syntax errors detected in config.php" && filesize("config.php") > 0) {
//    echo 'config.php loaded.<br>';
    require "config.php";
    $gameState = unserialize($gameState);
    $actors = unserialize($actors);
} else {
//    echo 'config.php failed. rebuilding...<br>';
    $gameState = array();
    $actors = array();
}

$game = new Game($gameState, $actors);

if ($_POST != null) {
    if ($game->parseAction($_POST, 'player') === true) {
        $game->saveState();
    }
    $game->displayErrors();
    $game->showActorControls();
    $game->actors['player']->showStatus();
} else {
    $game->showActorControls();
    $game->actors['player']->showStatus();
    $game->displayErrors();
    $game->saveState();
}

function setDefaultValues() {
    echo 'setDefaultValues<br>';
    $gameState = array();
    $actors = array();
}

?>
</body>

