<?php
/*
 * shipcalc
 * Location
 *
 * Description - Enter a description of the file and its purpose.
 *
 * Author:      John Arnold <john@jdacsolutions.com>
 * Link:           https://jdacsolutions.com
 *
 * Created:             Apr 22, 2018 9:32:17 PM
 * Last Updated:    Date 
 * Copyright            Copyright 2018 JDAC Computing Solutions All Rights Reserved
 */

namespace shipcalc;

/**
 * Description of Location
 *
 * @author John Arnold <john@jdacsolutions.com>
 */
class Location {
    protected $lat = '';
    protected $lon = '';
    protected $city = '';
    protected $state = '';
    protected $countryCode = '';
    protected $zipCode = '';
    
    /**
     *  Create a new instance of Location using a combined latitude-longitude string as provided by the Google Maps API for
     *  places.
     * @param type $latlon
     */
    public function __construct($latlon = '') {
        if (!($latlon === '')) {
            $coordArray = explode(",", $latlon);
            $this->lat = $coordArray[0];
            $this->lon = $coordArray[1];
        }
    }
    
    /**
     *   Return boolean indicating if this location is in the lower 48 states of the US
     */
    public function isLower48() {
        return ($this->getCountryCode() == 'US' && $this->getState() != 'Alaska' && $this->getState() != 'Hawaii');
    }
    
    /**
     *   Return boolean indicating if this location is in the US
     */
    public function isDomestic() {
        return ($this->getCountryCode() == 'US');
    }

    // Getters and Setters
    public function getLat() {
        return $this->lat;
    }

    public function getLon() {
        return $this->lon;
    }

    public function getCity() {
        return $this->city;
    }

    public function getState() {
        return $this->state;
    }

    public function getCountryCode() {
        return $this->countryCode;
    }

    public function getZipCode() {
        return $this->zipCode;
    }

    public function setLat($lat) {
        $this->lat = $lat;
    }

    public function setLon($lon) {
        $this->lon = $lon;
    }

    public function setCity($city) {
        $this->city = $city;
    }

    public function setState($state) {
        $this->state = $state;
    }

    public function setCountryCode($countryCode) {
        $this->countryCode = $countryCode;
    }

    public function setZipCode($zipCode) {
        $this->zipCode = $zipCode;
    }


}
