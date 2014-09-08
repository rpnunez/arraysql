<?php

namespace Nunez;

/**
 * Class ArraySQL
 *
 * @package Nunez
 */
class ArraySQL {

    private $array = null;
    private $terms = array();
    private $result = array();

    public function __construct($array) {
        if (empty($array)) {
            throw new \Exception('ArraySQL can not be instantiated with an emtpy array.');
        }

        $this->array = $array;
    }

    public function select($term) {
        if (empty($term) || is_string($term) == false) {
            throw new \Exception('ArraySQL->select must be called with a non-empty string.');
        }

        $this->terms['select'] = $term;

        return $this;
    }

    public function with($term) {
        if (empty($term) || is_string($term) == false) {
            throw new \Exception('ArraySQL->with must be called with a non-empty string.');
        }

        $this->terms['with'] = $term;
    }

    public function result() {
        $this->execute();

        return $this->result;
    }

    private function execute() {
        // Loop through the array
        for ($i = 0; $i <= sizeOf($this->array); $i++) {
            $key = key($this->array);
            $val = $this->array[$i];

            log(array('index' => $i, 'key' => $key, 'val' => $val));
        }
    }
}

function log() {
    $args = func_get_args();

    echo '<hr/>';
    echo '<h2>Debug</h2>';
    echo '<pre>';
    print_r($args);
    echo '</pre>';
    echo '<hr/>';
}