<?php

exec('php -l config.php', $output);
if ($output[0] === "No syntax errors detected in config.php") {
    require "config.php";
} else {
    setDefaultValues();
}


// $aIds = array();
//            if (Zend_Registry::get('config')->sale_collection->mode == 1) $aIds[] = Zend_Registry::get('config')->sale_collection->id;
////            if (Zend_Registry::get('config')->outlet_collection->mode == 1) $aIds[] = Zend_Registry::get('config')->outlet_collection->id; // wyłączenie outletu http://task.info.kazar.com/issues/1736
//            $sIds = implode(',', $aIds);
//            
//            if ($sIds == null) $sIds = 0;



$gameState = unserialize($gameState);
$actors = unserialize($actors);

$game = new Game($gameState, $actors);

if ($_POST != null) {
    $game->parseAction($_POST);
}

$game->actors->checkStatus();
$game->actors->showControls();
$game->actors->showStatus();
$game->displayErrors();
$game->saveState();

class Game {

    function __construct($gameState, $actors) {

        $this->gameState = $gameState;
        $this->loadActors($actors);
    }

    function loadActors($actors) {
        if (!empty($actors)) {
            $this->actors = $actors;
        } else {
            $player = new Actor($type = 'player', $name = 'Faggot', $effects = array('alive' => 1), $stats = array('hp' => 10), $inventory = array('gold' => 0));
            $this->actors = $player;
        }
    }

    function parseAction($_POST) {
        if ($_POST['wait'] == 'wait') {
            $this->actors->stats['hp'] += 0.2;
        } else if ($_POST['fight'] == 'fight') {
            $this->actors->stats['hp'] --;
            $this->actors->inventory['gold'] ++;
        } else if ($_POST['restart'] == 'restart') {
            $this->actors->stats['hp'] = 10;
            $this->actors->effects['alive'] = true;
        } else {
            $this->errors .= 'Unrecognized command.<br>';
        }
    }

    function displayErrors() {
        if (!empty($this->errors)) {
            print_r($this->errors);
            exit;
        }
    }

    function saveState() {
        
        file_put_contents("config.php", "<?php 
                $" . "gameState = '" . serialize($this->gameState) . "';
                $" . "actors = '" . serialize($this->actors) . "';");
    }

}

class Actor {

    function __construct($type, $name, $effects, $stats, $inventory) {
        $this->type = $type;
        $this->name = $name;
        $this->effects = $effects;
        $this->stats = $stats;
        $this->inventory = $inventory;
    }
    
    
    function checkStatus() {

        if ($this->stats['hp'] <= 0) {
            $this->effects['alive'] = false;
            $this->inventory['gold'] = 0;
        }
    }

    function showControls() {
        if ($this->effects['alive'] == true) {
            $this->showCombatControls();
        } else {
            $this->showRestartControls();
        }
    }

    function showCombatControls() {
        echo
        "<form action='index.php' method='post' >
            <button type='submit' name='fight' value='fight' class='w3-button w3-theme-d1 w3-margin-bottom'><i class='fa fa-thumbs-up'></i> Engage </button> 

            <button type='submit' name='pay off' value='pay off' class='w3-button w3-theme-d2 w3-margin-bottom'><i class='fa fa-comment'></i> Pay off </button> 

            <button type='submit' name='wait' value='wait' class='w3-button w3-theme-d2 w3-margin-bottom'><i class='fa fa-comment'></i> Wait </button> 
        </form>
        <div style='background-color: #FF0000'>TEST</div>";
    }

    function showRestartControls() {
        echo
        "<form action='index.php' method='post' >
            <button type='submit' name='restart' value='restart' class='w3-button w3-theme-d2 w3-margin-bottom'><i class='fa fa-comment'></i> Restart </button> 
        </form>";
    }

    function showStatus() {
        if ($this->effects['alive'] == false) {
            echo 'BEPIS';
        } else {
            echo('HP: ' . $this->stats['hp'] . '<br>');
            echo('GP: ' . $this->inventory['gold'] . '<br>');
        }
    }

    

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

function setDefaultValues() {
    $gameState = array(
        'turn' => 0
    );

    $effects = array(
        'alive' => 1
    );

    $stats = array(
        'hp' => 10
    );

    $inventory = array(
        'gold' => 0
    );

}

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