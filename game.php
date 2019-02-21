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

    function parseAction($_POST, $actor) {
        switch ($_POST['action']) {
            
            case 'forage':
                $this->actors[$actor]->calculatePassiveStatChange();
                $this->actors[$actor]->forage();
                break;

            case 'eat':
                
                $this->actors[$actor]->calculatePassiveStatChange();
                $this->actors[$actor]->eat();//handle no food error
                break;

            case 'sell':
                $this->actors[$actor]->sell();
                break;
                
            case 'wait':
                $this->actors[$actor]->calculatePassiveStatChange();
                $this->actors[$actor]->wait();
                break;

            case 'rest':
                $this->actors[$actor]->calculatePassiveStatChange();
                $this->actors[$actor]->rest();
                break;

            case 'restart':
                $player = $this->resetPlayer();
                $this->actors['player'] = $player;
                break;
            default:
                $this->addError('Unrecognized command.<br>');
                return false;
        }
        return true;
    }

    function showActorControls() {
        if ($this->actors['player']->effects['alive'] == true) {
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
        $this->showSellControls() .
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
    
    function showSellControls() {
        return "<button type='submit' name='action' value='sell' class='w3-button w3-theme-d2 w3-margin-bottom'><i class='fa fa-comment'></i> Sell </button>";
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
        }
    }

    function saveState() {
        file_put_contents("config.php", "<?php 
                $" . "gameState = '" . serialize($this->gameState) . "';
                $" . "actors = '" . serialize($this->actors) . "';");
    }

    function addError($error) {
        $this->errors .= $error;
    }

    function resetPlayer() {

        $player = new Player(
                $type = 'player', $name = 'Faggot', $effects = array('alive' => true), $stats = array(
            'hp' => 70,
            'hp_max' => 75,
            'ep' => 70,
            'ep_max' => 75,
            'fp' => 70,
            'fp_max' => 75,
            'carry_max' => 50
//            'strength' => 70,
//            'agility' => 70,
//            'dexterity' => 85,
//            'endurance' => 60,
//            'speed' => 70
                ), $inventory = array('food' => 0)
        );
        return $player;
    }

}
