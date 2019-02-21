<?php

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

    function calculatePassiveStatChange() {
        $this->stats['hpRegenRate'] = (($this->stats['fp'] / $this->stats['fp_max']) - 1 + ($this->stats['ep'] / $this->stats['ep_max'])) / 8;
        $this->stats['epRegenRate'] = (($this->stats['fp'] / $this->stats['fp_max']) * 2) - 0.5;
        $this->stats['hpCunsumeRate'] = (($this->stats['fp'] / $this->stats['fp_max']) * 2) - 1;
        echo($this->epRegenRate);
        $this->alterStat('hp', $this->stats['hpRegenRate']);
        $this->alterStat('ep', $this->stats['epRegenRate']);
        $this->alterStat('fp', -2);
        $this->calculateWeight();
    }
    
    function calculateWeight() {
        $weight = 3; //do dis
    }

    function sell() {
        if ($this->alterInventory('food', -1) === true) {
            $this->alterInventory('coppers', 1);
        }
    }

    function wait() {
        $this->alterStat('hp', 0.01);
        $this->alterStat('ep', 0.5);
    }

    function eat() {
        if ($this->alterInventory('food', -1) === true) {
            $this->calculatePassiveStatChange();
            $this->alterStat('fp', +10);
        } else {
            return false;
        }
    }

    function rest() {
        $this->alterStat('hp', 0.05);
        $this->alterStat('ep', 2);
    }

    function forage() {
        $this->alterStat('ep', -2);
        $this->alterInventory('food', 1);
    }

    function alterStat($type, $amount) {
        if ((isset($this->stats[$type . '_max'])) && (($this->stats[$type] + $amount) > $this->stats[$type . '_max'])) {
            $this->stats[$type] = $this->stats[$type . '_max'];
        } else {
            $this->stats[$type] += $amount;
        }
    }

    function alterInventory($type, $amount) {
        if (($this->inventory[$type] + $amount) < 0) {
            $this->inventory[$type] = 0;
            return false;
        } else {
            $this->inventory[$type] += $amount;
        }
        return true;
    }

    function showFullStatus() {
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
