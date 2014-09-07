<?php

$cars = array(
    0 => array(
        'name' => 'Corvette',
        'manufacturer' => 'Chevrolet',
        'models' => array(
            'Base',
            'ZO6'
        ),
        'engines' => array(
            'LS3',
            'LS7'
        )
    ),
    1 => array(
        'name' => 'Camaro',
        'manufacturer' => 'Chevrolet',
        'models' => array(
            'Base',
            'RS',
            'SS'
        )
    ),
);

$query = new \Nunez\ArraySQL($cars);
$query->select('name')->with('corvette');