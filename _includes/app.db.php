<?php

/**
 *  Application DB functions file for PHP.
 *  Created:    2018-04-18 by John Arnold <john@jdacsolutions.com>
 *  Last Updated:    2018-04-18 - John Arnold <john@jdacsolutions.com>
 *  https://jdacsolutions.com
 * 
 *  Description:
 *          Provides common  MySQL DB functions used by the application
 * 
 *  Version History:
 *          Version:  1.0.0
 *          Date:  2018-04-18
 *          Summary:
 *                  Centralized common functions from other application PHP include files here.
 */

// Constants used for login attempts
define('LOGIN_BAD_QUERY', -1);
define('LOGIN_BAD_USERNAME', -2);
define('LOGIN_BAD_PASSWORD', -3);
define('LOGIN_BAD_ACCOUNT', -4);
define('LOGIN_OK', 1);
$db_loaded = 1;
$newCarrier = array(
    'id' => 0,
    'name' => '',
    'contact' => '',
    'address_1' => '',
    'address_2' => '',
    'city' => '',
    'state' => '',
    'zip' => '',
    'phone' => '',
    'toll_free' => '',
    'fax' => '',
    'contact_email' => '',
    'lead_email' => '',
    'pitch' => '',
    'logo_file' => '',
    'site_url' => '',
    'displays' => 0,
    'active' => 'Y',
    'create_date' => '',
    'created_by' => '',
    'last_updated' => '',
    'updated_by' => ''
);

$newCarrierAttributes = array(
    'id' => 0,
    'c_id' => 0,
    'lower_48' => 'N',
    'ak' => 'N',
    'hi' => 'N',
    'ovs' => 'N',
    'pov' => 'N',
    'mc' => 'N',
    'rv' => 'N',
    'atvutv' => 'N',
    'boat' => 'N',
    'open' => 'N',
    'enclosed' => 'N',
    'create_date' => '',
    'last_updated' => '',
    'created_by' => '',
    'updated_by' => '',
    'active' => 'Y'
);

/**
 *  Connect to the Application DB and return a DB Connection Handle.
 */
function db_connect() {
    if (!getenv('DB_SERVER')) {
        die('One or more environment variables failed assertions: DATABASE_DSN is missing');
    } elseif (!$dbConn = mysqli_connect(getenv('DB_SERVER'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'), getenv('DB_DATABASE'))) {
        die("Could not connect to the database.  Please try again later.");
    } else {
        return $dbConn;
    } 
}

/* RATES --------------------------------------------------------------------- */
/**
 *   Pull active mileage rate
 */
function getActiveRate() {
    $dbConn = db_connect();
    // Get the active season
    $activeSeason = 0;
    $seasonalRates = array(
        'name' => '',
        'miles_rate' => 0.0,
        'surcharge' => 0.0
    );
    $stmt = "select config_value from master_config where config_key = 'season'";
    $result = mysqli_query($dbConn, $stmt);
    if (mysqli_num_rows($result) > 0) {
        $resultRow = mysqli_fetch_array($result);
        $activeSeason = $resultRow['config_value'];
        $stmt2 = "select name, miles_rate, fuel_surcharge from seasons where id = $activeSeason";
        //printVarIfDebug($stmt2, getenv('gDebug'), 'SQL Query to get seasonal rates.');
        $result2 = mysqli_query($dbConn, $stmt2);
        if (mysqli_num_rows($result2) > 0) {
            $resRow2 = mysqli_fetch_array($result2);
            $seasonalRates = array();
            $seasonalRates['name'] = $resRow2['name'];
            $seasonalRates['miles_rate'] = $resRow2['miles_rate'];
            $seasonalRates['surcharge'] = $resRow2['fuel_surcharge'];
        }
    } else {
        printVarIfDebug('Could not find active season in Master Config.', getenv('gDebug'), 'So sad try again.');
    }
    mysqli_close($dbConn);
    return $seasonalRates;
}

/**
 *   Pull ids of rate seasons
 */
function getSeasonIds() {
    $seasonIds = array();
    $dbConn = db_connect();
    $stmt = "select id from seasons";
    $result = mysqli_query($dbConn, $stmt);
    while ($row = mysqli_fetch_array($result)) {
        array_push($seasonIds, $row['id']);
    }    
    mysqli_close($dbConn);
    return $seasonIds;
}

/**
 *  Update Seasons from provided array
 */
function updateSeasons($updateArray = 0) {
    if ($updateArray == 0) { return; }
    $dbConn = db_connect();
    $user = $_SESSION[SESSION_NAME]['user']['login'];
    foreach ($updateArray as $season) {
        $stmt = "update seasons set miles_rate = " . $season['base'] . ", fuel_surcharge = " . $season['surcharge'] . ", updated_by = '" . $user . "', last_updated = NOW() where id = " . 
            $season['id'] . " and miles_rate != " . $season['base'] . " and fuel_surcharge != " . $season['surcharge'];
        mysqli_query($dbConn, $stmt);
    }
    mysqli_close($dbConn);
}

/**
 *  Update the active season in the master config table
 */
function updateActiveSeason($season = 0) {
    if (!$season == 0) {
        $dbConn = db_connect();
        $stmt = "update master_config set config_value = " . $season . ", last_updated = NOW(), updated_by = '" . $_SESSION[SESSION_NAME]['user']['login'] . "' where config_key = 'season'";
        mysqli_query($dbConn, $stmt);
        mysqli_close($dbConn);
    }
}

/**
 *   Pull active mileage rate
 */
function getSeasonRates() {
    $dbConn = db_connect();
    // Get the active season
    $activeSeason = 0;
    $rates = array();
    $seasonalRate = array();
    $stmt = "select config_value from master_config where config_key = 'season'";
    $result = mysqli_query($dbConn, $stmt);
    if (mysqli_num_rows($result) > 0) {
        $resultRow = mysqli_fetch_array($result);
        $activeSeason = $resultRow['config_value'];
        $stmt2 = "select id, name, miles_rate, fuel_surcharge, last_updated, updated_by from seasons";
        //printVarIfDebug($stmt2, getenv('gDebug'), 'SQL Query to get seasonal rates.');
        $result2 = mysqli_query($dbConn, $stmt2);

        while ($rateRow = mysqli_fetch_array($result2)) {
            $seasonalRate = array();
            $seasonalRate['id'] = $rateRow['id'];
            $seasonalRate['name'] = $rateRow['name'];
            $seasonalRate['miles_rate'] = $rateRow['miles_rate'];
            $seasonalRate['surcharge'] = $rateRow['fuel_surcharge'];
            $seasonalRate['lastUpdated'] = $rateRow['last_updated'];
            $seasonalRate['updatedBy'] = $rateRow['updated_by'];
            if ($activeSeason == $rateRow['id']) {
                $seasonalRate['active'] = 1;
            } else {
                $seasonalRate['active'] = 0;
            }
            array_push($rates, $seasonalRate);
        }
    } else {
        printVarIfDebug('Could not find active season in Master Config.', getenv('gDebug'), 'So sad try again.');
    }
    mysqli_close($dbConn);
    return $rates;
}

/* CARRIERS ------------------------------------------------------------------ */
/**
 *   Pull counts of carriers for domestic (lower 48), lower 48 + AK and HA, and International.
 */
function getAdminCarrierSummary() {
    $dbConn = db_connect();
    $summary = array();
    $stmt1 = "select count(*) as lower48 from carrier_attributes where forty8 = 'Y'";
    $result = mysqli_query($dbConn, $stmt1);
    //printVarIfDebug($result, getenv('gDebug'), 'Result of select count(*) as lower48 from carrier where forty8 = Y');
    $resultRow = mysqli_fetch_array($result);
    $summary['lower48'] = $resultRow['lower48'];
    $stmt2 = "select count(*) as ak_ha from carrier_attributes where ak = 'Y' and hi = 'Y'";
    $result2 = mysqli_query($dbConn, $stmt2);
    $resultRow2 = mysqli_fetch_array($result2);
    $summary['ak_ah'] = $resultRow2['ak_ha'];
    $stmt3 = "select count(*) as intl from carrier_attributes where ovs = 'Y'";
    $result3 = mysqli_query($dbConn, $stmt3);
    $resultRow3 = mysqli_fetch_array($result3);
    $summary['intl'] = $resultRow3['intl'];
    $stmt4 = "select config_value from master_config where config_key = 'rotation'";
    $result4 = mysqli_query($dbConn, $stmt4);
    $resultRow4 = mysqli_fetch_array($result4);
    $summary['rot'] = $resultRow4['config_value'];
    $stmt5 = "select count(*) as leads from carriers_leads";
    $result5 = mysqli_query($dbConn, $stmt5);
    $resultRow5 = mysqli_fetch_array($result5);
    $summary['leads'] = $resultRow5['leads'];
    
    mysqli_close($dbConn);
    return $summary;
}

/**
 *  Summary:  Return an array containing all carriers who match the given criteria.
 * @param array $criteria - an associative array containing values for the criteria we want.
 * @return array
 */
function getCarrierIdsMatching($criteria = null) {
    $carriers = array();
    if ($criteria === null) {
        return $carriers;
    }
    $query = "select c_id from carrier_attributes where active = 'Y' and ";
    foreach($criteria as $key => $value) {
        if ($value == 'Y') {
            $query .= $key . " = 'Y' and ";
        }
    }
    $query = rtrim($query, " and");
    //printVarIfDebug($query, getenv('gDebug'), 'Query to fetch carrier ids on matching criteria');
    $dbConn = db_connect();
    $res1 = mysqli_query($dbConn, $query);
    $carrierIds = array();
    while ($cidRow = mysqli_fetch_array($res1)) {
        array_push($carrierIds, $cidRow['c_id']);
    }
    mysqli_close($dbConn);
    return $carrierIds;
}

/**
 *  Summary:  Return an array containing all carriers who match the given criteria.
 * @param array $criteria - an associative array containing values for the criteria we want.
 * @return array
 */
function getAllCarriers() {
    $query = "select id, name, contact,  address_1, address_2, city, state, zip, phone, toll_free, fax, contact_email, lead_email, pitch, logo_file, site_url, displays, active, create_date, created_by, last_updated, updated_by from carrier order by name ASC";
    $dbConn = db_connect();
    $res1 = mysqli_query($dbConn, $query);
    $carriers = array();
    while ($carrierRow = mysqli_fetch_assoc($res1)) {
        $stmt2 = "select count(*) as leads from carriers_leads where c_id = " . $carrierRow['id'];
        $res2 = mysqli_query($dbConn,$stmt2);
        $leads = 0;
        if ($res2) {
            $leads = mysqli_fetch_assoc($res2)['leads'];
        }
        $carrierRow['leads'] = $leads;
        array_push($carriers, $carrierRow);
    }
    mysqli_close($dbConn);
    //printVarIfDebug($carriers, getenv('gDebug'), 'Carriers');
    return $carriers;
}

/**
 *  Summary:  Return the carrier matching the given id.
 * @param int $cid - id of the carrier to fetch
 * @return array
 */
function getCarrier($cid = 0) {
    global $newCarrier;
    if ($cid == 0) { return $newCarrier; }
    
    $query = "select id, name, contact,  address_1, address_2, city, state, zip, phone, toll_free, fax, contact_email, lead_email, "
        . "pitch, logo_file, site_url, displays, active, create_date, created_by, last_updated, updated_by from carrier where id = " . $cid;
    $dbConn = db_connect();
    $res1 = mysqli_query($dbConn, $query);
    $carrierRow = mysqli_fetch_assoc($res1);
    mysqli_close($dbConn);
    //printVarIfDebug($carriers, getenv('gDebug'), 'Carriers');
    return $carrierRow;
}

/**
 *  Summary:  Return the carrier matching the given id.
 * @param int $cid - id of the carrier to fetch
 * @return array
 */
function getCarrierAttributes($cid = 0) {
    global $newCarrierAttributes;
    if ($cid == 0) { return $newCarrierAttributes; }
    
    $query = "select id, c_id, forty8 as lower_48,  ak, hi, ovs, pov, mc, rv, atvutv, boat, `open`, `enclosed`, "
        . "create_date, last_updated, created_by, updated_by, active from carrier_attributes where c_id = " . $cid;
    //printVarIfDebug($query, getenv('gDebug'), 'Carrier Attribute Query');
    $dbConn = db_connect();
    $res1 = mysqli_query($dbConn, $query);
    $attrRow = mysqli_fetch_assoc($res1);
    mysqli_close($dbConn);
    //printVarIfDebug($carriers, getenv('gDebug'), 'Carriers');
    return $attrRow;
}

/**
 *  Summary - Update the active status, updated date, and update user on the array of carriers.
 * @param array $cArray - Array of carrier ids and active statuses
 */
function updateCarrierActive($cArray = 0) {
    if (!$cArray == 0) {
        $dbConn = db_connect();
        $user = $_SESSION[SESSION_NAME]['user']['login'];
        foreach ($cArray as $carrier) {
            $stmt = "update carrier set active = '" . $carrier['active'] . "', last_updated = NOW(), updated_by = '" . $user . "' where id = " . $carrier['id'] . " and active != '" . $carrier['active'] . "'";
           mysqli_query($dbConn, $stmt);
        }
        mysqli_close($dbConn);
    }
}

/**
 *  Summary - Update the active status, updated date, and update user on the array of carriers.
 * @param array $cArray - Array of carrier ids and active statuses
 */
function updateCarrier($carrier = 0) {
    if (!$carrier == 0) {
        $dbConn = db_connect();
        $user = $_SESSION[SESSION_NAME]['user']['login'];
        $stmt = "update carrier set logo_file = '" . $carrier['logo_file'] . "', " .
            "contact = '" . $carrier['contact'] . "', " .
            "address_1 = '" . $carrier['address_1'] . "', " .
            "address_2 = '" . $carrier['address_2'] . "', " .
            "city = '" . $carrier['city'] . "', " .
            "state = '" . $carrier['state'] . "', " .
            "zip = '" . $carrier['zip'] . "', " .
            "phone = '" . $carrier['phone'] . "', " .
            "toll_free = '" . $carrier['toll_free'] . "', " .
            "fax = '" . $carrier['fax'] . "', " .
            "contact_email = '" . $carrier['contact_email'] . "', " .
            "lead_email = '" . $carrier['lead_email'] . "', " .
            "pitch = '" . $carrier['pitch'] . "', " .
            "site_url = '" . $carrier['site_url'] . "', " .
            "updated_by = '" . $user . "', " .
            "last_updated = now() where id = " . $carrier['id'];
        //printVarIfDebug($stmt, getenv('gDebug'), 'Carrier Update Statement');
        if (!mysqli_query($dbConn, $stmt)) {
            echo "Could not update carrier in database";
        }
        mysqli_close($dbConn);
    }
}
/**
 *  Update the carrier attributes
 * @param array $cAtts
 */
function updateCarrierAttributes($cAtts = 0) {
    if (!$cAtts == 0) {
        $user = $_SESSION[SESSION_NAME]['user']['login'];
        $stmt = 
            "update carrier_attributes set forty8 = '" . $cAtts['lower_48'] . "', " .
            "ak = '" . $cAtts['ak'] . "', " .
            "hi = '" . $cAtts['hi'] . "', " .
            "ovs = '" . $cAtts['ovs'] . "', " .
            "pov = '" . $cAtts['pov'] . "', " .
            "mc = '" . $cAtts['mc'] . "', " .
            "rv = '" . $cAtts['rv'] . "', " .
            "atvutv = '" . $cAtts['atvutv'] . "', " .
            "boat = '" . $cAtts['boat'] . "', " .
            "`open` = '" . $cAtts['open'] . "', " .
            "`enclosed` = '" . $cAtts['enclosed'] . "', " .
            "last_updated = now(), updated_by = '" . $user . "' " .
            "where id = " . $cAtts['id'];
        //printVarIfDebug($stmt, getenv('gDebug'), 'Carrier Attr update =>');
        $dbConn = db_connect();
        if (!mysqli_query($dbConn, $stmt)) {
            echo "Could not update carrier attributes in database. Please try again.";
        }
        $stmt2 = "update carrier set last_updated = now(), updated_by = '" . $user . "' where id = " . $cAtts['c_id'];
        if (!mysqli_query($dbConn, $stmt2)) {
            echo "Could not update carrier in database.  Please try again.";
        }
        mysqli_close($dbConn);
    }
}

/**
 *  Summary - Update the active status, updated date, and update user on the array of carriers.
 * @param array $cArray - Array of carrier ids and active statuses
 */
function insertCarrier($carrier = 0) {
    if (!$carrier == 0) {
        $dbConn = db_connect();
        $user = $_SESSION[SESSION_NAME]['user']['login'];
        $stmt = "insert into carrier (name, contact, address_1, address_2, city, state, zip, phone, toll_free, fax, contact_email, lead_email, pitch, site_url, logo_file, created_by, create_date) " .
            "values('" . $carrier['name'] . "', '" . $carrier['contact'] . "', '" . $carrier['address_1'] . "', '" . $carrier['address_2'] . "', '" . $carrier['city'] . "', '" . $carrier['state'] . "', '" .
            $carrier['zip'] . "', '" . $carrier['phone'] . "', '" . $carrier['toll_free'] . "', '" . $carrier['fax'] . "', '" . $carrier['contact_email'] . "', '" .
            $carrier['lead_email'] . "', '" . $carrier['pitch'] . "', '" . $carrier['site_url'] ."', '" . $carrier['logo_file'] . "', '" . $user . "', now())";
        //printVarIfDebug($stmt, getenv('gDebug'), 'Carrier Insert Statement');
        mysqli_query($dbConn, $stmt);
        $newId = mysqli_insert_id($dbConn);
        // Insert a new carrier attributes record with default values
        $stmt2 = "insert into carrier_attributes values(null, " . $newId . ", 'Y', 'N', 'N', 'N', 'Y', 'Y', 'N', 'Y', 'N', 'Y', 'N', now(), now(), '" . $user . "', '', 'Y')";
        mysqli_query($dbConn, $stmt2);
        //printVarIfDebug($stmt2, getenv('gDebug'), 'Carrier Attributes Insert Statement');
        mysqli_close($dbConn);
        return $newId;
    }
}

/**
 *  Summary - Get all carriers from the DB whose id is NOT in the passed in array.
 * 
 * @param array $anIdArray - An array containing the ids we're not interested in.
 * @param int $limit - Max number of carrier ids to get
 * @return array
 */
function getCarrierIdsNotIn($anIdArray = null, $limit = 1) {
    $stmt = "select id from carrier where id not in (";
    $idArray = array();
    if ($anIdArray == null) {
        return $idArray;
    }
    foreach ($anIdArray as $id) {
        $stmt .= $id . ", ";
    }
    // Strip the last comma
    $stmt = rtrim($stmt, ", ") . ") LIMIT " . $limit;
    //printVarIfDebug($stmt, getenv('gDebug'), 'SELECT STATEMENT FOR ADDITIONAL Carriers');
    $dbConn = db_connect();
    $result = mysqli_query($dbConn, $stmt);
    while ($idRow = mysqli_fetch_array($result)) {
        array_push($idArray, $idRow['id']);
    }
    mysqli_close($dbConn);
    return $idArray;
}

/**
 *  Increment the display counts for each of the indicated carriers and insert new request-carrier associations
 *  for the request.
 * 
 * @param array $selectedCarriers
 * @param int $reqNo
 */
function updateCarrierDisplayTally($selectedCarriers = null, $reqNo = 0) {
    if (!$selectedCarriers || $reqNo == 0) {
        //printvar("nothing happening here");
        return -1;
    }
    // Update the number of times the carrier has been displayed.
    $stmt1 = "update carrier set displays = displays + 1 where id in (";
    $tempVals = "";
    foreach($selectedCarriers as $cID) {
        $stmt1 .= $cID . ", ";
        $tempVals .= "(" . $reqNo . "," . $cID . "), ";
    }
    // Strip the last comma
    $stmt1 = rtrim($stmt1, ", ") . ")";
    $valueStr = rtrim($tempVals, ", ");
    //printVarIfDebug($stmt1, getenv('gDebug'), 'Statement to update display counts');
    //printVarIfDebug($valueStr, getenv('gDebug'), 'Values to insert into req_carrier_display');
    $dbConn = db_connect();
    mysqli_query($dbConn, $stmt1);
    $stmt2 = "insert into req_carrier_display values " . $valueStr;
    //printVarIfDebug($stmt2, getenv('gDebug'), 'Statement to insert new Carrier-Request Display Associations');
    mysqli_query($dbConn, $stmt2);
}

/**
 *   Pull all carrier logos and return them in an array.
 */
function getCarrierLogos($limit = 0) {
    $dbConn = db_connect();
    $stmt = "select logo_file from carrier where active = 'Y'";
    $result = mysqli_query($dbConn, $stmt);
    $logoArray = [];
    while ($logoRow = mysqli_fetch_array($result)) {
        array_push($logoArray, $logoRow['logo_file']);
    }
    mysqli_close($dbConn);
    if ($limit == 0) {
        return $logoArray;
    } else {
        // Pick a few random logos from the results to send back to the requestor
        $logos = 1;
        $randArray = [];
        $logoCount = count($logoArray);
        while ($logos <= $limit && $logos <= $logoCount) {
            $ndx = rand(0, $logoCount - 1);
            $thisLogo = $logoArray[$ndx];
            if (!(in_array($thisLogo, $randArray, true))) {
                array_push($randArray, $thisLogo);
                $logos++;
            }
        }
        return $randArray;
    }
}

function getSelectedCarriers($carrierIds = null) {
    if (!$carrierIds) {
        //printVarIfDebug('Nothing Happening Here - No Carriers Specified', getenv('gDebug'));
        return 0;
    }
    $carriers = array();
    $query = "select id, name, contact, lead_email, address_1, city, state, zip, phone, toll_free, pitch, logo_file, site_url from carrier where id in (" . implode(", ", $carrierIds) . ")";
    //printVarIfDebug($query, getenv('gDebug'), 'Select statement for carriers');
    $dbConn = db_connect();
    $res = mysqli_query($dbConn, $query);
    if (!$res) {
        printVarIfDebug('SQL query failed.  Could not access any carriers', getenv('gDebug'));
    } else {
        while ($carrierRow = mysqli_fetch_array($res)) {
            $carrier = array();
            $carrier['id'] = $carrierRow['id'];
            $carrier['name'] = $carrierRow['name'];
            $carrier['contact'] = $carrierRow['contact'];
            $carrier['lead_email'] = $carrierRow['lead_email'];
            $carrier['address_1'] = $carrierRow['address_1'];
            $carrier['city'] = $carrierRow['city'];
            $carrier['state'] = $carrierRow['state'];
            $carrier['zip'] = $carrierRow['zip'];
            $carrier['phone'] = $carrierRow['phone'];
            $carrier['toll_free'] = $carrierRow['toll_free'];
            $carrier['pitch'] = $carrierRow['pitch'];
            $carrier['logo_file'] = $carrierRow['logo_file'];
            $carrier['site_url'] = $carrierRow['site_url'];
            array_push($carriers, $carrier);
        }
    }
    mysqli_close($dbConn);
    return $carriers;
}

/* REQUESTS ------------------------------------------------------------------ */
/**
 *  Get a summary of all requests for the admin dashboard
 */
function getRequestSummary() {
    $reqArray = array(
        'requests' => 0,
        'avgBase' => 0,
        'avgSurcharge' => 0,
        'email_requests' => 0
     );
    $dbConn = db_connect();
    $query = "select count(*) as requests from request";
    $result = mysqli_query($dbConn, $query);
    if ($result) {
        $resultRow = mysqli_fetch_array($result);
        $reqArray['requests'] =  $resultRow['requests'];
    }
    $stmt2 = "select avg(base_quote) as avgBase, avg(surcharge) as avgSurcharge from request";
    $result2 = mysqli_query($dbConn, $stmt2);
    if ($result2) {
        $resRow2 = mysqli_fetch_array($result2);
        $reqArray['avgBase'] = $resRow2['avgBase'];
        $reqArray['avgSurcharge'] = $resRow2['avgSurcharge'];
    }
    $stmt3 = "select count(*) as email_requests from requester";
    $result3 = mysqli_query($dbConn, $stmt3);
    if ($result3) {
        $resRow3 = mysqli_fetch_array($result3);
        $reqArray['email_requests'] = $resRow3['email_requests'];
    }
    mysqli_close($dbConn);
    return $reqArray;
}

function getRequestDetail($reqId = 0) {
    if ($reqId == 0) { return false; }
    $dbConn = db_connect();
    $query = "select * from request where id = " . $reqId;
    $res = mysqli_query($dbConn, $query);
    $reqRow = array();
    if ($res) {
        $reqRow = mysqli_fetch_array($res);
    }
    mysqli_close($dbConn);
    return $reqRow;
}

/* 
 *  VEHICLE TYPES 
 */

/**
 *   Pull ids of vehicle types
 */
function getVehicleTypeIds() {
    $vTypeIds = array();
    $dbConn = db_connect();
    $stmt = "select id from vehicle_types";
    $result = mysqli_query($dbConn, $stmt);
    while ($row = mysqli_fetch_array($result)) {
        array_push($vTypeIds, $row['id']);
    }    
    mysqli_close($dbConn);
    return $vTypeIds;
}

/**
 *  Update vehicle types from provided array
 */
function updateVTypes($updateArray = 0) {
    if ($updateArray == 0) { return; }
    $dbConn = db_connect();
    $user = $_SESSION[SESSION_NAME]['user']['login'];
    foreach ($updateArray as $vType) {
        $stmt = "update vehicle_types set transport_rate = " . $vType['transport_rate'] . ", updated_by = '" . $user . "', last_updated = NOW() where id = " . 
            $vType['id'] . " and transport_rate != " . $vType['transport_rate'];
        mysqli_query($dbConn, $stmt);
    }
    mysqli_close($dbConn);
}

/**
 *   Pull all vehicle types return them in an array.
 */
function getVehicleTypes() {
    $dbConn = db_connect();
    $stmt = "select id, vehicle_class, class_name, vehicle_desc, transport_rate, last_updated, updated_by  from vehicle_types";
    $result = mysqli_query($dbConn, $stmt);
    $vehicleTypes = array();
    while ($vehicleRow = mysqli_fetch_array($result)) {
        $vType = array();
        $vType['id'] = $vehicleRow['id'];
        $vType['class'] = $vehicleRow['vehicle_class'];
        $vType['class_name'] = $vehicleRow['class_name'];
        $vType['desc'] = $vehicleRow['vehicle_desc'];
        $vType['transport_rate'] = $vehicleRow['transport_rate'];
        $vType['last_updated'] = $vehicleRow['last_updated'];
        $vType['updated_by'] = $vehicleRow['updated_by'];
        $query = "select count(*) as sizeClassCount from vehicle_sizes where vc_id = " . $vehicleRow['id'];
        $res2 = mysqli_query($dbConn, $query);
        $vType['sizeClasses'] = mysqli_fetch_array($res2)['sizeClassCount'];
        array_push($vehicleTypes, $vType);
    }
    mysqli_close($dbConn);
    //printvar($vehicleTypes, getenv('gDebug'), 'Vehicle Types');
    return $vehicleTypes;
}

/**
 *   Pull a vehicle type based on its id
 */
function getVehicleType($vtId = 0) {
    $dbConn = db_connect();
    $stmt = "select id, vehicle_class, class_name, vehicle_desc, transport_rate from vehicle_types where id=" . $vtId;
    $result = mysqli_query($dbConn, $stmt);
    $vType = array();
    while ($vehicleRow = mysqli_fetch_array($result)) {
        $vType['class'] = $vehicleRow['id'];
        $vType['vClass'] = $vehicleRow['vehicle_class'];
        $vType['option'] = $vehicleRow['class_name'];
        $vType['desc'] = $vehicleRow['vehicle_desc'];
        $vType['rate'] = $vehicleRow['transport_rate'];
    }
    mysqli_close($dbConn);
    return $vType;
}
/**
 *  Get a vehicle type name based on a given vehicle type id
 */
function getVehicleTypeName($vtID = 1) {
    $dbConn = db_connect();
    $query = "select class_name from vehicle_types where id = $vtID";
    $result = mysqli_query($dbConn, $query);
    if (!$result) {
        // Couldn't get type, return 'Automobile' as default
        return 'Automobile';
    } else {
        $resultRow = mysqli_fetch_array($result);
        return $resultRow['class_name'];
    }
}

/*
 *  VEHICLE CLASS SIZES
 */

/**
 *  Update vehicle class sizes from provided array
 */
function updateVSizes($updateArray = 0, $vType = 0) {
    if ($updateArray == 0 || $vType == 0) { return; }
    $dbConn = db_connect();
    $user = $_SESSION[SESSION_NAME]['user']['login'];
    foreach ($updateArray as $vSize) {
        $stmt = "update vehicle_sizes set rating_factor = " . $vSize['rating_factor'] . ", updated_by = '" . $user . "', last_updated = NOW() where id = " . 
            $vSize['id'] . " and vc_id = " . $vType . " and rating_factor != " . $vSize['rating_factor'];
        mysqli_query($dbConn, $stmt);
    }
    mysqli_close($dbConn);
}

/**
 *  Get a vehicle type name based on a given vehicle type id
 */
function getVehicleSizeName($vsID = 1) {
    $dbConn = db_connect();
    $query = "select menu_option from vehicle_sizes where id = $vsID";
    $result = mysqli_query($dbConn, $query);
    if (!$result) {
        // Couldn't get vehicle size descripion, return ''Standard" as default
        return 'Standard';
    } else {
        $resultRow = mysqli_fetch_array($result);
        return $resultRow['menu_option'];
    }
}

/**
 *   Pull base factor for hauling  a vehicle type
 */
function getVTypeBaseFactor($typeId) {
    //printVarIfDebug($typeId, getenv('$gDebug'), 'Vehicle Type ID');        
    $baseFactor = 0.9;
    $dbConn = db_connect();
    $stmt = "select transport_rate from vehicle_types where id = $typeId";
    //printvar($stmt, 'We are trying to run this query.');
    $result = mysqli_query($dbConn, $stmt);
    if (mysqli_num_rows($result) > 0) {
        $resultRow = mysqli_fetch_array($result);
        //printVarIfDebug($resultRow, getenv('gDebug'), 'Result Row');
        $baseFactor = $resultRow['transport_rate'];
    } else {
        printVarIfDebug('No matching vehicle type found', getenv('gDebug'), 'So sad try again.');
    }
    mysqli_close($dbConn);
    return $baseFactor;
}

/**
 *   Pull all vehicle size classifications and  return them in an array.
 */
function getVehicleSizes($vType = 0) {
    $dbConn = db_connect();
    if ($vType == 0) {
        $stmt = "select id, vc_id, menu_option, menu_order, rating_factor, last_updated, updated_by from vehicle_sizes";        
    } else {
        $stmt = "select id, vc_id, menu_option, menu_order, rating_factor, last_updated, updated_by from vehicle_sizes where vc_id = " . $vType;
    }
    $result = mysqli_query($dbConn, $stmt);
    $vehicleSizes = array();
    while ($vehicleSize = mysqli_fetch_array($result)) {
        $vSize = array();
        $vSize['id'] = $vehicleSize['id'];
        $vSize['vehicle-class'] = $vehicleSize['vc_id'];
        $vSize['option'] = $vehicleSize['menu_option'];
        $vSize['rate_factor'] = $vehicleSize['rating_factor'];
        $vSize['last_updated'] = $vehicleSize['last_updated'];
        $vSize['updated_by'] = $vehicleSize['updated_by'];
        array_push($vehicleSizes, $vSize);
    }
    mysqli_close($dbConn);
    return $vehicleSizes;
}

/**
 *   Pull ids of vehicle class sizes
 */
function getVehicleSizeIds($vType = 0) {
    $vSizeIds = array();
    $dbConn = db_connect();
    if ($vType == 0) {
        $stmt = "select id from vehicle_sizes";    
    } else {
        $stmt = "select id from vehicle_sizes where vc_id = " . $vType;    
    }
    $result = mysqli_query($dbConn, $stmt);
    while ($row = mysqli_fetch_array($result)) {
        array_push($vSizeIds, $row['id']);
    }    
    mysqli_close($dbConn);
    return $vSizeIds;
}

/**
 *   Pull base factor for hauling  a vehicle type
 */
function getVSizeRateFactor($classSizeId) {
    //printVarIfDebug($classSizeId, getenv('$gDebug'), 'Vehicle Class Size Type ID');        
    $rateFactor = 0.9;
    $dbConn = db_connect();
    $stmt = "select rating_factor from vehicle_sizes where id = $classSizeId";
    $result = mysqli_query($dbConn, $stmt);
    if (mysqli_num_rows($result) > 0) {
        $resultRow = mysqli_fetch_array($result);
        $rateFactor = $resultRow['rating_factor'];
    } else {
        printVarIfDebug('No matching vehicle sizing rating factor found', getenv('gDebug'), 'So sad try again.');
    }
    mysqli_close($dbConn);
    return $rateFactor;
}

function init_user($username, $password, $pw_prehashed = false) {
    $check_pw = (($pw_prehashed) ? $password : md5($password));
    $db = db_connect();
    $stmt = "select id, user, first_name, authentication_string, active, create_date from user where user ='$username' LIMIT 1"; 
    $res = mysqli_query($db, $stmt);
    if (!$res) {
        //printVarIfDebug('could not query user table: ' . $db->ErrorMsg(), getenv('gDebug'), basename(__FILE__) . ' (' . __LINE__ .')') ; 
        return LOGIN_BAD_QUERY;
    } elseif (mysqli_num_rows($res) == 0) {
        //printVarIfDebug('Username \'' . $username . '\' not found in user table', getenv('gDebug'), basename(__FILE__) . ' (' . __LINE__ . ')' ); 
        reset_session();
        return LOGIN_BAD_USERNAME;
    } else {
        $resultRow = mysqli_fetch_array($res);
        if ($resultRow['active'] != 'Y') {
            //printVarIfDebug('Username \'' . $username . '\' is disabled', getenv('gDebug'), basename(__FILE__) . ' (' . __LINE__ .')' ); 
            reset_session();
            return LOGIN_BAD_ACCOUNT;
        } elseif (trim($check_pw) != trim($resultRow['authentication_string'])) {
            //printVarIfDebug('Password specified does not match', getenv('gDebug'), basename(__FILE__) . ' (' . __LINE__ . ')' ); 
            reset_session();
            return LOGIN_BAD_PASSWORD;
        } else {
      //      printVarIfDebug('Login Success', getenv('gDebug'), basename(__FILE__) . ' (' . __LINE__ . ')' );
      //      printVarIfDebug($_SESSION, getenv('gDebug'), 'Session just before login'); 
            $_SESSION[SESSION_NAME]['user'] = array(
                'logged_in' => true,
                'id' => $resultRow['id'],
                'login' => $resultRow['user'],
                'name' => $resultRow['first_name'],
                'hash' => md5($resultRow['id'] . ARRAY_GLUE . $resultRow['create_date']),
                'last_initialized' => time()
            );
           //printVarIfDebug($_SESSION, getenv('gDebug'), 'Session just after login');
            return LOGIN_OK;
        }
    }
} 

function reinit_user() {
    if (!is_logged_in()) { return false; }
    //check login
    $dbConn = db_connect();
    if(getenv('gDebug')) { $dbConn->debug = true; }
    $stmt = "SELECT id, user, first_name, authentication_string, create_date FROM user WHERE id = {$_SESSION[SESSION_NAME]['user']['id']} LIMIT 1";
    $res = mysqli_query($dbConn, $stmt);
    //printVarIfDebug($res, getenv('gDebug'), 'Query Result');
    
    if (!$res) {
        //printVarIfDebug('could not query user table: ' . mysqli_error($dbConn), getenv('gDebug'), basename(__FILE__).' ('.__LINE__.')'); 
        reset_session();
        return false;
    } elseif(mysqli_num_rows($res) == 0) {
        //printVarIfDebug('User not found in user table', getenv('gDebug'), basename(__FILE__).' ('.__LINE__.')'); 
        reset_session();
        return false;
    } else {
        $resultRow = mysqli_fetch_array($res);
        if ($_SESSION[SESSION_NAME]['user']['hash'] != md5($resultRow['id'] . ARRAY_GLUE . $resultRow['create_date'])) {
            //printVarIfDebug('User hash does not match', getenv('gDebug'), basename(__FILE__).' ('.__LINE__.')'); 
            reset_session();
            return false;
        } else {
            return (init_user($resultRow['user'], $resultRow['authentication_string'], true) === LOGIN_OK);
        }
    }
}

/*
 *  Inserts a new request into the database from a shipcalc\ShipCalcRequest object and returns the id of the newly inserted
 *  row.
 */
function insertRequest($shipCalcRequest) {
    $stmt = "insert into request values(null, ";
    $stmt .= $shipCalcRequest->getVehicleClass() . ', ' . $shipCalcRequest->getVehicleClassSize() . ", ";
    $stmt .= "'" . $shipCalcRequest->getStartLoc()->getLat() . "', '" . $shipCalcRequest->getStartLoc()->getLon() . "', ";
    $stmt .= "'" . $shipCalcRequest->getStartLoc()->getCity() . "', '" . $shipCalcRequest->getStartLoc()->getState() . "', '" .
        $shipCalcRequest->getStartLoc()->getCountryCode() . "', '" . $shipCalcRequest->getStartLoc()->getZipCode() . "', '";
    $stmt .= $shipCalcRequest->getEndLoc()->getLat() . "', '" . $shipCalcRequest->getEndLoc()->getLon() . "', ";
    $stmt .= "'" . $shipCalcRequest->getEndLoc()->getCity() . "', '" . $shipCalcRequest->getEndLoc()->getState() . "', '" .
        $shipCalcRequest->getEndLoc()->getCountryCode() . "', '" . $shipCalcRequest->getEndLoc()->getZipCode() . "', ";
    $stmt .= $shipCalcRequest->getTripLen() . ", " . $shipCalcRequest->getEstimatedBaseCost() . ", " . $shipCalcRequest->getEstimatedSurcharge() . 
        ", NOW())";
    //printVarIfDebug($stmt, getenv('gDebug'), 'Insert Statment');
    $dbConn = db_connect();
    $res = mysqli_query($dbConn, $stmt);
    if ($res) {
        return mysqli_insert_id($dbConn);
    } else {
        return 0;
    }
}

/*
 *  Inserts a new requester into the database from a shipcalc\ShipCalcRequest and a RequestMailer object and returns the id of the newly inserted
 *  row.
 */
function insertRequester($shipCalcRequest, $reqMailer) {
    $stmt = "insert into requester (r_id, email) values(";
    $stmt .= $shipCalcRequest->getRequestId() . ", '" . $reqMailer->getRecipient() . "')";
    //printVarIfDebug($stmt, getenv('gDebug'), 'Insert Statment');
    $dbConn = db_connect();
    $res = mysqli_query($dbConn, $stmt);
    if ($res) {
        return mysqli_insert_id($dbConn);        
    } else {
        return 0;
    }
}

/*
 *  Inserts a new requester into the database from a shipcalc\ShipCalcRequest and a RequestMailer object and returns the id of the newly inserted
 *  row.
 */
function insertCarrierLead($carrier, $requesterId, $msgBody) {
    $dbConn = db_connect();
    $stmt = "insert into carriers_leads (c_id, req_id, logged, lead_email, msg_body) values(";
    $stmt .= $carrier['id'] . ", " . $requesterId . ", NOW(), '" . $carrier['lead_email'] . "', '" . mysqli_real_escape_string($dbConn, $msgBody) . "')";
    //printVarIfDebug($stmt, getenv('gDebug'), 'Insert Statment');
    $res = mysqli_query($dbConn, $stmt);
    if ($res) {
        return mysqli_insert_id($dbConn);
    } else {
        return 0;
    }
}
