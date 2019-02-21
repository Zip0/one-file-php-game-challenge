<?php

class Player extends Actor {
    
    function showStatus() {
        if ($this->effects['alive'] == false) {
            echo 'BEPIS';
        } else {
                echo '<br>STATS<br>';
                echo '<pre>Health    : ' . round($this->stats['hp'], 2) . ' / ' . round($this->stats['hp_max'], 2) . '</pre>';
                echo '<pre>Energy    : ' . round($this->stats['ep'], 2) . ' / ' . round($this->stats['ep_max'], 2) . '</pre>';
                echo '<pre>Nutrition : ' . round($this->stats['fp'], 2) . ' / ' . round($this->stats['fp_max'], 2) . '</pre>';
                echo '<pre>HP regen  : ' . round($this->stats['hpRegenRate'], 2) . '</pre>';
                echo '<pre>EP regen  : ' . round($this->stats['epRegenRate'], 2) . '</pre>';
                echo '<br>INVENTORY<br>';
                echo '<pre>Weight    : ' . round($this->inventory['weight'], 2) . ' / ' . round($this->stats['weight_max'], 2);
                echo '<pre>Food      : ' . round($this->inventory['food'], 2) . '</pre>';
                echo '<pre>Coppers   : ' . $this->inventory['coppers'] . '</pre>';
            
            //stats and inv
        }
    }

}
