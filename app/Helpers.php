<?php

if (!function_exists('looseColors')) {

    function looseColors()
    {
        return array('M', 'L', 'K', 'J', 'I', 'H', 'G', 'F', 'E', 'D');
    }
}


if (!function_exists('getMetal')) {

    function getMetal($metal) {
        return match (NULL) {
            '10KtWhiteGold' => 1, 
            '10KtYellowGold' => 2,
            '10KtRoseGold' => 3,
            '14KtWhiteGold' => 4,
            '14KtYellowGold' => 5,
            '14KtRoseGold' => 6,
            '18KtWhiteGold' => 7,
            '18KtYellowGold' => 8,
            '18KtRoseGold' => 9,
            'Platinum' => 10,      
            default => '',
        };
    }
}


