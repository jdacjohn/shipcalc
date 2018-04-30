<?php
    namespace shipcalc;
/**
 *  GMapAPIDIstance utilizes PHP cURL and the GoogleMaps Distance Matrix API to calculate and return driving-based
 *  distances between a origin and destination, both expressed in latitudes and longitudes.  The Class can provide distances in
 *  either Miles or Kilometers.
 *
 *  @author John Arnold, JDAC Solutions <john@jdacsolutions.com>
 *  @copyright  Copyright (c) 2018 jdacsolutions.com
 *  @version    $1.0$
 */
class GMapAPIDistance {
    protected $apiKey = '';
    public $origin = '';
    public $destination = '';
    const API_URL = 'https://maps.googleapis.com/maps/api/distancematrix/json';
    const METERS_PER_MILE = 1609.34;
    
    public function __construct($startPoint = '0.0', $endPoint = '0.0', $key = '') {
        $this->origin = $startPoint;
        $this->destination = $endPoint;
        $this->apiKey = $key;
    }
    
    public function getMiles(): int {
        if (!($this->origin == '0.0' || $this->destination == '0.0' || $this->apiKey = '')) {
            return floor(($this->queryDistance() / self::METERS_PER_MILE));
        } else {
            return 0;
        }
    }
    
    public function getKMs(): int {
        if (!($this->origin == '0.0' || $this->destination == '0.0' || $this->apiKey = '')) {
            return floor(($this->queryDistance() / 1000));
        } else {
            return 0;
        }
    }
    
    public  function queryDistance()  {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::API_URL . "?origins=" . $this->origin . "&destinations=" . $this->destination . "&key=" . $this->apiKey);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_AUTOREFERER, false);
        $result = curl_exec($ch);
        curl_close($ch);
        return json_decode($result, true)["rows"][0]["elements"][0]["distance"]["value"];
    }
    
}
