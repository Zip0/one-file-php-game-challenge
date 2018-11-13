<?php

class Player extends Actor {
    
    function calculatePassiveStatChange() {
        $this->alterStat('hp', 0.02);
        $this->alterStat('ep', -0.5);
        $this->alterStat('fp', -1.5);
    }

    function wait() {
        $this->alterStat('hp', 0.05);
        $this->alterStat('ep', 2);
    }

    function rest() {
        $this->alterStat('hp', 0.15);
        $this->alterStat('ep', 8);
    }

    function forage() {
        $this->alterStat('ep', -3);
        $this->alterInventory('food', 1);
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
            echo 'Inventory';
            foreach ($this->inventory as $key => $value) {
                echo '<pre>' . $key . ': ' . $value . '</pre>';
            }
        }
    }

}
