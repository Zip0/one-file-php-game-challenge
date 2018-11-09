<?php

exec('php -l config.php', $output);
if ($output[0] === "No syntax errors detected in config.php") {
    require "config.php";
} else {
    setDefaultValues();
}

$gameState = unserialize($gameState);
$actors = unserialize($actors);

$game = new Game($gameState, $actors);

if ($_POST != null) {
    if ($game->parseAction($_POST) === true) {
        echo ' true';
        $game->actors->checkStatus();
        $game->actors->showControls();
        $game->actors->showStatus();
        $game->displayErrors();
        $game->endTurn();
        $game->saveState();
    } else {
        echo ' false';
        $game->actors->showControls();
        $game->actors->showStatus();
        $game->displayErrors();
//        $game->saveState();
    }
}

class Game {

    function __construct($gameState, $actors) {

        $this->gameState = $gameState;
        $this->loadActors($actors);
    }

    function loadActors($actors) {
        if (!empty($actors)) {
            $this->actors = $actors;
        } else {
            $player = new Actor($type = 'player', $name = 'Faggot', $effects = array('alive' => 1), $stats = array(
                'hp' => 10,
                'hp_max' => 10,
                'ep' => 80,
                'ep_max' => 100,
                    ), $inventory = array()
            );
            $this->actors = $player;
        }
    }

    function parseAction($_POST) {
        switch ($_POST['action']) {
            case 'wait':
                $this->actors->alterStat('hp', 0.05);
                $this->actors->alterStat('ep', 2);
                break;

            case 'forage':
                $this->actors->alterStat('ep', -3);
                $this->actors->alterInventory('food', 1);
                break;

            case 'eat':
                if ($this->actors->alterInventory('food', -1) === true) {
                    $this->actors->alterStat('fp', +10);
                } else {
                    $this->addError('Not enough food!');
                echo 'why is this?';
                    return false;
                }
                echo 'even here?';
                break;

            case 'restart':
                $this->actors->setDefaults();
            default:
                $this->addError('Unrecognized command.<br>');
                return false;
        }
        echo 'twat';
        return true;
    }

    function displayErrors() {
        if (!empty($this->errors)) {
            print_r($this->errors);
//            exit;
        }
    }

    function endTurn() {
        $this->actors->alterStat('hp', 0.02);
        $this->actors->alterStat('ep', -0.5);
    }

    function saveState() {

        file_put_contents("config.php", "<?php 
                $" . "gameState = '" . serialize($this->gameState) . "';
                $" . "actors = '" . serialize($this->actors) . "';");
    }

    function addError($error) {
        $this->errors .= $error;
    }

}

class Actor {

    function __construct($type, $name, $effects, $stats, $inventory) {
        $this->type = $type ?: 'unknown';
        $this->name = $name ?: 'unknown';

        $this->effects = $effects;
        $this->stats = $stats;
        $this->inventory = $inventory;
    }

    function checkStatus() {

        if ($this->stats['hp'] < 0) {
            $this->effects['alive'] = false;
        }
    }

    function alterStat($type, $amount) {
        $this->stats[$type] += $amount;
        if ((isset($this->stats[$type . '_max'])) && ($this->stats[$type] > $this->stats[$type . '_max'])) {
            $this->stats[$type] = $this->stats[$type . '_max'];
        }
    }

    function alterInventory($type, $amount) {
        $this->inventory[$type] += $amount;
        if ($this->inventory[$type] < 0) {
            $this->inventory[$type] = 0;
            return false;
        }
        return true;
    }

    function showControls() {
        if ($this->effects['alive'] == true) {
            $this->showMainControls();
        } else {
            $this->showRestartControls();
        }
    }

    function showMainControls() {
        echo
        "<form action='index.php' method='post' >" .
        $this->showForageControls() .
        $this->showEatControls() .
        $this->showWaitControls() .
        $this->showRestartControls() . //temporary
        "</form>";
    }

    function showForageControls() {
        return "<button type='submit' name='action' value='forage' class='w3-button w3-theme-d1 w3-margin-bottom'><i class='fa fa-thumbs-up'></i> Forage </button>";
    }

    function showEatControls() {
        return "<button type='submit' name='action' value='eat' class='w3-button w3-theme-d2 w3-margin-bottom'><i class='fa fa-comment'></i> Eat </button>";
    }

    function showWaitControls() {
        return "<button type='submit' name='action' value='wait' class='w3-button w3-theme-d2 w3-margin-bottom'><i class='fa fa-comment'></i> Wait </button>";
    }

    function showRestartControls() {
        return "<button type='submit' name='action' value='restart' class='w3-button w3-theme-d2 w3-margin-bottom'><i class='fa fa-comment'></i> Restart </button>";
    }

    function showStatus() {
        if ($this->effects['alive'] == false) {
            echo 'BEPIS';
        } else {
            foreach ($this->effects as $key => $value) {
                echo '<pre>' . $key . ': ' . $value . '</pre>';
            }
            foreach ($this->stats as $key => $value) {
                echo '<pre>' . $key . ': ' . $value . '</pre>';
            }
            foreach ($this->inventory as $key => $value) {
                echo '<pre>' . $key . ': ' . $value . '</pre>';
            }
        }
    }

    function setDefaults() {
        $this->effects = array(
            'alive' => true
        );
        $this->stats = array(
            'hp' => 96,
            'hp_max' => 100,
            'ep' => 80,
            'ep_max' => 100,
            'fp' => 40,
            'fp_max' => 100
        );
        $this->inventory = array();
    }

}

function setDefaultValues() {
    $gameState = array(
        'turn' => 0
    );
}

//class Player extends Actor {
//
//    private $name;
//    private $name;
//    private $name;
//    private $name;
//
//    function __construct($name, $effects, $stats, $inventory) {
//        $this->name = $name;
//        $this->effects = $effects;
//        $this->stats = $stats;
//        $this->inventory = $inventory;
//    }
//
//}
//$number = rand(1,10);
//
//var_dump($number);
//
//$array = array();
//
//while ($number > 0) {
//    array_push($array, 'PING');
//    $number--;
//}
//
//var_dump($array);
//$needle = 'dog';
//$haystack = 'The quick brown fox jumps over the lazy dog';
//$haystack = 'The quick brown fox jumps over the lazy ';
//$animals = array (
//    'dog',
//    'cat',
//    'horse',
//    'goat',
//    'chicken',
//    );
//$indices = 
//shuffle($animals);
//
//function checkHaystackForNeedle($haystack, $needle) {
//    echo 'checking...<br>';
//    var_dump(strpos($haystack, $needle));
//}
//
//checkHaystackForNeedle($haystack, $needle);
//var_dump(preg_match('%'. $needle . '%', $haystack) ? 'is in string' : 'not in string');
//echo(preg_match('%'. $needle . '%', $haystack) ? 'is in string' : 'not in string');
//echo(preg('%a%', $haystack));
//var_dump(preg_match('%arse%', $haystack));
//echo $haystack;
//foreach ($animals as $animal) {
//echo ($haystack.$animal.'<br>');
//}