<?php

class Game {

    function __construct($gameState, $actors) {

        $this->gameState = $gameState;
        $this->loadActors($actors);
    }

    function loadActors($actors) {
        if (!empty($actors)) {
            $this->actors = $actors;
        } else {
            $player = $this->resetPlayer();
            $this->actors = $player;
        }
    }

    function parseAction($_POST) {
        switch ($_POST['action']) {
            case 'wait':
                $this->actors->calculatePassiveStatChange();
                $this->actors->wait();
                break;

            case 'rest':
                $this->actors->calculatePassiveStatChange();
                $this->actors->rest();
                break;

            case 'forage':
                $this->actors->calculatePassiveStatChange();
                $this->actors->forage();
                break;

            case 'eat':
                if ($this->actors->alterInventory('food', -1) === true) {
                    $this->actors->calculatePassiveStatChange();
                    $this->actors->alterStat('fp', +12);
                } else {
                    $this->addError('Not enough food!');
                    return false;
                }
                break;

            case 'restart':
                $player = $this->resetPlayer();
                $this->actors = $player;
                break;
            default:
                $this->addError('Unrecognized command.<br>');
                return false;
        }
        return true;
    }

    function showActorControls() {
        if ($this->actors->effects['alive'] == true) {
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
        $this->showRestControls() .
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

    function showRestControls() {
        return "<button type='submit' name='action' value='rest' class='w3-button w3-theme-d2 w3-margin-bottom'><i class='fa fa-comment'></i> Rest </button>";
    }

    function showRestartControls() {
        return "<button type='submit' name='action' value='restart' class='w3-button w3-theme-d2 w3-margin-bottom'><i class='fa fa-comment'></i> Restart </button>";
    }

    function displayErrors() {
        if (!empty($this->errors)) {
            print_r($this->errors);
//            exit;
        }
    }

    function saveState() {
        echo 'saveState<br>';
        file_put_contents("config.php", "<?php 
                $" . "gameState = '" . serialize($this->gameState) . "';
                $" . "actors = '" . serialize($this->actors) . "';");
    }

    function addError($error) {
        $this->errors .= $error;
    }

    function resetPlayer() {

        $actors['player'] = new Player(
                $type = 'player', $name = 'Faggot', $effects = array('alive' => true), $stats = array(
            'hp' => 63,
            'hp_max' => 65,
            'ep' => 75,
            'ep_max' => 85,
            'fp' => 40,
            'fp_max' => 70,
//            'strength' => 70,
//            'agility' => 70,
//            'dexterity' => 85,
//            'endurance' => 60,
//            'speed' => 70
                ), $inventory = array()
        );
        return $actors;
    }

}
