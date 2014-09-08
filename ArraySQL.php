<?php

namespace Nunez;

/**
 * Class ArraySQL
 *
 * @package Nunez
 */
class ArraySQL implements \Iterator {

    private $array = null;
    private $size = 0;
    private $position = 0;
    private $terms = array();
    private $result = array();
    
    // Internal pointers
    private $_selectFound = false;
    private $_withFound = false;
    private $_queryFound = false;

    public function __construct($array) {
        if (empty($array)) {
            throw new \Exception('ArraySQL can not be instantiated with an emtpy array.');
        }

        $this->array = $array;
        $this->size = sizeOf($array);
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
        $this->execute($this->array);

        return $this->result;
    }

    /**
     * Recursive function that executes the SQL/searches "term" and "with"
     *
     * @param $data
     */
    private function execute($data) {
        if (empty($data)) {
            $data = $this->array;
        }

        // Loop through the array
        for ($i = 0; $i < sizeOf($data); $i++) {
            $key = key($this->array);
            $val = $this->array[$i];

            if ($this->_selectFound == true && $this->_withFound == true) {
                // Stop! Found the requested search.
                $this->_queryFound = true;
                $this->result = $val;

                return $val;
            } elseif ($this->_selectFound == true && $this->_withFound == false) {
                $this->execute($val);
            } elseif ($val == $this->terms['select']) {
                $this->_selectFound = true;
            } elseif ($val == $this->terms['with']) {
                $this->_withFound = true;
            } else {
                //throw new \Exception('Unknown error occurred during ArraySQL->execute().');
                $this->execute($val);
            }

            $this->execute($val);

            debug(array($key, $val, 'selectFound' => $this->_selectFound, 'withFound' => $this->_withFound));
        }
    }

    //#########################
    // Iterator methods
    //#########################
    function rewind() {
        $this->position = 0;
    }

    function current() {
        return $this->array[$this->position];
    }

    function key() {
        return $this->position;
    }

    function next() {
        ++$this->position;
    }

    function valid() {
        return isSet($this->array[$this->position]);
    }
}

function debug($data) {
    echo '<hr/>';
    echo '<h2>Debug</h2>';
    echo '<pre>';
    print_r($data);
    echo '</pre>';
    echo '<hr/>';
}