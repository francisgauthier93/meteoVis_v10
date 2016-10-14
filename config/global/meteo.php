<?php

return array(
    'limit' => array(
        'day' => array(
            'min' => 1,
            'max' => 7
        ),
        'temperature' => array(
            'min' => -100.0,
            'max' => 100.0
        )
    ),
    'require' => array(
        'precipitation' => array(
            'probability' => 30 // minimum probability to display
        )
    ),
    'graph' => array(
        'measure' => array(
            'iWidth' => 1050,       // in px
            'iHeight' => 300,       // in px
            'iXSpace' => 6.25,      // in px // = iYCenter / 24
            'iYSpace' => 5,         // in px
            'iDayWidth' => 150,     // in px
            'iYCenter' => 165       // in px
        ),
        'coeff' => array(
            'degre_to_px' => 5
        ),
        'image' => array(
            'rain' => 'rain.png',
            'snow' => 'snow.png',
            'arrow' => 'arrow.png',
            'sky_sunny' => 'sky_sunny.png',
            'sky_fair' => 'sky_fair.png', 
            'sky_mostly_sunny' => 'sky_mostly_sunny.png',
            'sky_partly_cloudy' => 'sky_partly_cloudy.png',
            'sky_mostly_cloudy' => 'sky_mostly_cloudy.png',
            'sky_broken' => 'sky_broken.png',
            'sky_cloudy' => 'sky_cloudy.png'
        )
    )
);