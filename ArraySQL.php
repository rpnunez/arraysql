<?php

namespace Nunez;

/**
 * Class ArraySQL
 *
 * @package Nunez
 */
class ArraySQL {

    private $array = null;
    private $size = 0;
    private $position = 0;
    private $terms = array();
    private $result = array();
    
    // Internal pointers
    private $_selectFound = false;
    private $_withFound = false;
    private $_queryFound = false;
    private $_depthLimit = 5;
    private $_depth = 0;

    // Debugging variables
    private $debug = array();
    private $verbose = true;

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
        // Did we already find the search?
        // @TODO: This is a duplicate of the first if() in the for loop. However, should it stay here? That way there is no processing power wasted on the for loop and the if() conditional.
        if ($this->_selectFound == true && $this->_withFound == true) {
            $this->debug[] = 'Match found!';
            return $this->result;
        }

        // Did we reach the maximum depth already?
        if ($this->_depth >= $this->_depthLimit) {
            $this->debug[] ='Maximum depth reached ('. $this->_depthLimit .');
            return;
        }

        // Increase depth by 1
        $this->_depth++;

        $this->debug[] = 'Execute called, #'. $this->_depth .' with data:<br /><pre>'. print_r($data, true) .'</pre>';

        // Loop through the array
        $keys = array_keys($data);
        $values = array_values($data);

        for ($i = 0; $i < sizeOf($data); $i++) {
            $key = key($data);
            $val = $values[$i];

            $sf = ($this->_selectFound) ? 'Yes ' : 'No';
            $wf = ($this->_withFound) ? 'Yes' : 'No';

            $this->debug[] = 'Index: ['. $i .'] of '. sizeOf($data) .'. Key: ['. $key .']. Value: [<pre>'. print_r($val, true) .'</pre>]. Select Found: '. $sf .'. With Found: '. $wf;

            if ($key == $this->terms['select'] && $val == $this->terms['with']) {
                $this->_selectFound = true;
                $this->_withFound = true;
            } elseif ($this->_selectFound == false || $this->_withFound == false) {
                // @TODO: There has to be a better way
                $array = array();

                $debug[] = 'About to call execute with: [<pre>'. print_r($values, true) .'</pre>';

                for ($x = 0; $x < sizeOf($data[$i]); $x++) {
                    $array[$i] = $val;
                }

                $this->execute($array);
            } elseif ($this->_selectFound == true && $this->_withFound == true) {
                // Stop! Found the requested search.
                $this->_queryFound = true;
                $this->result = $val;

                // @TODO: This should return the containing array, not just the key/value pair which is... pointless.
                return $val;
            } else {
                //throw new \Exception('Unknown error occurred during ArraySQL->execute().');
                //$this->execute($val);
                $this->debug[] = 'Reached else block in if() conditional within the for() loop. Depth: '. $this->_depth;
            }

            $this->position++;
        }
    }

    public function __destruct() {
        if (!empty($this->debug) && $this->verbose == true) {
            echo '<div style="padding: 5px; background-color: #CCC; border: 1px solid #3333; margin: 2px 0px 2px 0px;">';
            echo '<ol>';

            foreach ($this->debug as $item) {
                if (is_array($item)) {
                    $item = '<pre>'. print_r($item, true) .'</pre>';
                }

                echo '<li>'. $item .'</li>';
            }

            echo '</ol>';
        }
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