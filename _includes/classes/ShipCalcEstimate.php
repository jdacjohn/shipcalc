<?php
namespace shipcalc;
require('GMapAPIDistance.php');
/**
 *  ShipCalcEstimate builds a vehicle shipping cost estimate based on startPoint, endPoint, vehicleType, and vehicleSize.
 *
 *  @author John Arnold, JDAC Solutions <john@jdacsolutions.com>
 *  @copyright  Copyright (c) 2018 jdacsolutions.com
 *  @version    $1.0$
 */

class ShipCalcEstimate {
    
    public $origin = '';
    public $destination = '';
    public $vType = 0;
    public $vSize = 0;
    
    public function __construct($startPoint = '', $endPoint = '', $vClass = 0, $vClassSize = 0) {
        $this->origin = $startPoint;
        $this->destination = $endPoint;
        $this->vType = $vClass;
        $this->vSize = $vClassSize;
    }
    
    public function getEstimate() {
        // 1. get the miles.
        // 2.  get the rate
        // 3.  return the rate multiplied by the miles.
        
        $miles = (new GMapAPIDistance($this->origin, $this->destination, GOOGLE_MAPS_APIKEY))->getMiles();
        $baseFactor = $this->getVTypeBaseFactor();
        $vSizeFactor = $this->getVSizeRateFactor();
        $seasonal = getActiveRate();
        
        return array(
            'miles' => $miles,
            'vCostFactor' => $baseFactor,
            'vSizeFactor' => $vSizeFactor,
            'effectiveRate' => $seasonal
        );
    }
    
    private function getVTypeBaseFactor() {
        $base = getVTypeBaseFactor($this->vType);
        return $base;
    }
    
    private function getVSizeRateFactor() {
        $factor = getVSizeRateFactor($this->vSize);
        return $factor;
    }
}
