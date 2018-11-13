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
print_r($game);
if ($_POST != null) {
    if ($game->parseAction($_POST) === true) {
//        $game->endTurn();
        $game->saveState();
    }
    $game->displayErrors();
    $game->actors->player->checkStatus();
    $game->showActorControls();
    $game->actors->showStatus();
} else {
    $game->showActorControls();
    $game->actors->showStatus();
    $game->displayErrors();
    $game->saveState();
}

function setDefaultValues() {
    echo 'setDefaultValues<br>';
    $gameState = array();
    $actors = array();
}