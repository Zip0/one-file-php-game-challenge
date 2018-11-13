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
