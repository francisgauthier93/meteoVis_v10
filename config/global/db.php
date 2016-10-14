<?php

return array(
    'default' => 'mysql',
    
    'connection' => array(
        'mysql' => array(
            'driver' => 'mysql',
            'hostname' => 'www-labs.iro.umontreal.ca',
            'database' => 'wwwrali_meteovis_stage',
            'username' => 'wwwrali',
            'password' => 'Tissu16Epsilon',
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'strict' => false,
        )
    ),
    
    'query' => array(
        'limit' => array(
            'length' => array(
                'max' => 1000
            )
        ),
        'status' => array(
            'offline' => 0,
            'online' => 1
        )
    )
);