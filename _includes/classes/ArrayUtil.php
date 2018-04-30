<?php

/*
 * shipcalc
 * ArrayUtil
 *
 * Description - A utility class to return new arrays built from operations on the array used to instantiate the class.
 *
 * Author:      John Arnold <john@jdacsolutions.com>
 * Link:           https://jdacsolutions.com
 *
 * Created:             Apr 23, 2018 3:34:55 PM
 * Last Updated:    Date 
 * Copyright            Copyright 2018 JDAC Computing Solutions All Rights Reserved
 */

namespace shipcalc;

/**
 * Description of ArrayUtil
 *
 * @author John Arnold <john@jdacsolutions.com>
 */
class ArrayUtil {
    
    private $arrayOperand = array();
    
    public function __construct($anArray = null) {
        if (!$anArray == null) {
            $this->arrayOperand = $anArray;
        }
    }
    
    public function addAll() {
        return $this->arrayOperand;
    }
    
    /**
     *  Return an array with  random elements and a size of limit 
     * @param int $limit
     * @return array
     */
    public function addRandom($limit = 0) {
        if ($limit == 0) {
            return $this->addAll();
        } else {
            // Return an array with a random selection of elements of arrayOperand with a  total number of element = $limit
            $foundKeys = array();
            $selectedValues = array();
            $min = 0;
            $max = count($this->arrayOperand) - 1;
            $limitNdx = 0;
            while ($limitNdx < $limit) {
                $x = rand($min, $max);
                if (!in_array($x, $foundKeys)) {
                    array_push($selectedValues, $this->arrayOperand[$x]);
                    array_push($foundKeys, $x);
                    $limitNdx++;
                }
            }
            return $selectedValues;
        }
    }
}