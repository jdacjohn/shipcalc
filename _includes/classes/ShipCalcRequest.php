<?php
namespace shipcalc;
/*
 * shipcalc
 * ShipCalcRequest
 *
 * Description - Enter a description of the file and its purpose.
 *
 * Author:      John Arnold <john@jdacsolutions.com>
 * Link:           https://jdacsolutions.com
 *
 * Created:             Apr 22, 2018 8:40:52 PM
 * Last Updated:    Date 
 * Copyright            Copyright 2018 JDAC Computing Solutions All Rights Reserved
 */

/**
 * The ShipCalcRequest class is a convenience class that allows a clean way to aggregate $_POST data
 *  and calculated cost data that gets created as a result of a user submitting an estimate request on  the home page.
 *
 * @author John Arnold <john@jdacsolutions.com>
 */
class ShipCalcRequest {
    
    private $startLoc = null;
    private $endLoc = null;
    private $vehicleClass = 0;
    private $vehicleClassSize = 0;
    private $tripLen = 0;
    private $estimatedBaseCost = 0;
    private $estimatedSurcharge = 0;
    private $requestId = 0;
    private $vClassName = '';
    private $vClassSizeName = '';
    
    public function __construct($vClass = 0, $vSize = 0, $start = null, $end = null ) {
        ($end == null) ? $this->endLoc = new Location() : $this->endLoc = $end;
        ($start == null) ? $this->startLoc = new Location() : $this->startLoc = $start;
        $this->vehicleClass = $vClass;
        $this->vehicleClassSize = $vSize;
    }
    
    // Getters and Setters
    public function getStartLoc() {
        return $this->startLoc;
    }

    public function getEndLoc() {
        return $this->endLoc;
    }

    public function getVehicleClass() {
        return $this->vehicleClass;
    }
    
    public function getVehicleClassSize() {
        return $this->vehicleClassSize;
    }

    public function setStartLoc($startLoc) {
        $this->startLoc = $startLoc;
    }

    public function setEndLoc($endLoc) {
        $this->endLoc = $endLoc;
    }

    public function setVehicleClass($vehicleClass) {
        $this->vehicleClass = $vehicleClass;
    }

    public function setVehicleClassSize($vehicleClassSize) {
        $this->vehicleClassSize = $vehicleClassSize;
    }
    public function getTripLen() {
        return $this->tripLen;
    }

    public function getEstimatedBaseCost() {
        return $this->estimatedBaseCost;
    }

    public function getEstimatedSurcharge() {
        return $this->estimatedSurcharge;
    }

    public function getRequestId() {
        return $this->requestId;
    }

    public function setTripLen($tripLen) {
        $this->tripLen = $tripLen;
    }

    public function setEstimatedBaseCost($estimatedBaseCost) {
        $this->estimatedBaseCost = $estimatedBaseCost;
    }

    public function setEstimatedSurcharge($estimatedSurcharge) {
        $this->estimatedSurcharge = $estimatedSurcharge;
    }

    public function setRequestId($requestId) {
        $this->requestId = $requestId;
    }

    public function getTotalCost() {
        return ($this->estimatedBaseCost + $this->estimatedSurcharge);
    }
    public function getVClassName() {
        return $this->vClassName;
    }

    public function getVClassSizeName() {
        return $this->vClassSizeName;
    }

    public function setVClassName($vClassName) {
        $this->vClassName = $vClassName;
    }

    public function setVClassSizeName($vClassSizeName) {
        $this->vClassSizeName = $vClassSizeName;
    }


}
