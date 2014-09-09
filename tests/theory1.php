<?php

namespace Nunez;

error_reporting(E_ALL);

echo '<p>Hello, world!</p>';

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

require_once '../ArraySQL.php';
$query = new ArraySQL($cars);
$query->select('name')->with('corvette');
$result = $query->result();
debug($result);

echo '<hr />';
\debug_print_backtrace();