<?php
$root = './';
include($root . '_includes/app_start.inc.php');
include($root . '_includes/classes/ShipCalcEstimate.php');
include($root . '_includes/classes/ShipCalcRequest.php');
include($root . '_includes/classes/Location.php');
include($root . '_includes/classes/ArrayUtil.php');

    global $headerFilter;
    $headerNo = random_int(1, HEADER_IMAGES);
    while (array_search($headerNo, $headerFilter)) {
        $headerNo = random_int(1, HEADER_IMAGES);        
    }
    
$goodToGo = true;
$subTotal = 0;
$surcharges = 0;
$vSizeId = 0;
$startLocStr ='';
$endLocStr = '';
$cost = array( 'miles' => 0);
$carrierArray = array();
$requestId = 0;
$selectedCarrierIds = array();

//printVarIfDebug($_POST, getenv('gDebug'), '$_POST on entry to test function.');
if (isset($_POST['geo-start-location'])) {
    $origin = $_POST['geo-start-location'];
    $startLocStr = $_POST['geo-start-locality'] . ', ' . $_POST['geo-start-state'] . ' ' . $_POST['geo-start-postal_code'] . '  ' . $_POST['geo-start-country_short'];
} else {
    $goodToGo = false;
}
if (isset($_POST['geo-end-location'])) {
    $destination = $_POST['geo-end-location'];
    $endLocStr = $_POST['geo-end-locality'] . ', ' . $_POST['geo-end-state'] . ' ' . $_POST['geo-end-postal_code'] . '  ' . $_POST['geo-end-country_short'];
} else {
    $goodToGo = false;
}
$vType = (isset($_POST['vehicle-type'])) ? $_POST['vehicle-type'] : null;
if (isset($_POST['vehicle-size'])) {
    $vSizeTemp = $_POST['vehicle-size'];
    $vSizeArr = explode('::', $vSizeTemp);
    $vSizeId = $vSizeArr[1];
} else {
    $goodToGo = false;
}

if ($goodToGo) {
    
    // Get the factors and rates needed to build the estimate
    $calcEstimate = new shipcalc\ShipCalcEstimate($origin, $destination, $vType, $vSizeId);
    //printvarIfDebug($calcEstimate, getenv('gDebug'), 'CalcEstimate object');
    $cost = $calcEstimate->getEstimate();
    // Build the Location Objects that will be persisted in a new ShipCalcRequest
    $startLocation = new shipcalc\Location($origin);
    $startLocation->setCity($_POST['geo-start-locality']);
    $startLocation->setState($_POST['geo-start-state']);
    $startLocation->setCountryCode($_POST['geo-start-country_short']);
    $startLocation->setZipCode($_POST['geo-start-postal_code']);
    $endLocation = new shipcalc\Location($destination);
    $endLocation->setCity($_POST['geo-end-locality']);
    $endLocation->setState($_POST['geo-end-state']);
    $endLocation->setCountryCode($_POST['geo-end-country_short']);
    $endLocation->setZipCode($_POST['geo-end-postal_code']);
    //printvarIfDebug($startLocation, getenv('gDebug'), 'Location object -> Start');
    //printvarIfDebug($endLocation, getenv('gDebug'), 'Location object -> End');
    // Build the request object, calculate the estimated cost
    $shipCalcRequest = new shipcalc\ShipCalcRequest($vType, $vSizeId, $startLocation, $endLocation);
    //printVarifDebug($shipCalcRequest, getenv('gDebug'), 'ShipCalcRequest on Instantiation');
    $shipCalcRequest->setEstimatedBaseCost($cost['miles'] * $cost['effectiveRate']['miles_rate'] * $cost['vCostFactor'] * $cost['vSizeFactor']);
    $shipCalcRequest->setEstimatedSurcharge($cost['miles']  * $cost['effectiveRate']['surcharge']);
    $shipCalcRequest->setTripLen($cost['miles']);
    //printVarifDebug($shipCalcRequest, getenv('gDebug'), 'ShipCalcRequest After Calculations');

    // Set carrier criteria based on request info
    //printVarIfDebug($carrierCriteria, getenv('gDebug'), 'Carrier Criteria before Location Build');
    $vTypeArr = getVehicleType($vType);
    $carrierCriteria[strtolower($vTypeArr['vClass'])] = 'Y';
    buildCarrierLocationCriteria($startLocation, $endLocation);
    $matchingIds = getCarrierIdsMatching($carrierCriteria);
    $arrUtil = new \shipcalc\ArrayUtil($matchingIds);
    $selectedCarrierIds = array();
    if (count($matchingIds) < CARRIER_DISPLAY_LIMIT) {
        // We'll use all of the matching carriers and then fill the rest with random carriers - non-linkable
        $selectedCarrierIds = $arrUtil->addAll();
        $newLimit = CARRIER_DISPLAY_LIMIT - count($selectedCarrierIds);
        //printVarIfDebug($selectedCarrierIds, getenv('gDebug'), 'Selected Carriers pre adding random because selected did not meet LIMIT');
        $additionalCarriers = getCarrierIdsNotIn($selectedCarrierIds, $newLimit);
        $selectedCarrierIds = array_merge($selectedCarrierIds, $additionalCarriers);
        //printVarIfDebug($newCarrierIds, getenv('gDebug'), 'New Carriers after adding random because selected did not meet LIMIT');
    } else {
        $selectedCarrierIds = $arrUtil->addRandom(CARRIER_DISPLAY_LIMIT);
    }
    $requestId = insertRequest($shipCalcRequest);

    // Update the selected carriers to show they've been shown on a request.
    updateCarrierDisplayTally($selectedCarrierIds, $requestId);
    $carrierArray = getSelectedCarriers($selectedCarrierIds);
    //printVarIfDebug($carrierArray, getenv('gDebug'), 'Full Carriers');
    //printVarIfDebug($requestId, getenv('gDebug'), 'Id of inserted row');

    //echo "=======================<br />";
    //printVarIfDebug($cost['effectiveRate'], getenv('gDebug'), 'Rates: ');
    //echo "=======================<br />";
    //echo "Trip length is " . $cost['miles'] . " MILES<br />";
    //echo "Base  mileage rate: " . $cost['effectiveRate']['miles_rate'] . " dollars per mile. Active season: " . $cost['effectiveRate']['name'] . "<br />";
    //echo "Effective fuel surcharge: " . $cost['effectiveRate']['surcharge'] . " per mile (-- Note:  this could be calculated any way, such as per gallon based on a miles per gallon avg)<br />";
    //echo "Vehicle Type Cost Factor " . $cost['vCostFactor'] . "<br />";
    //echo "Vehicle Size Cost Factor " . $cost['vSizeFactor'] . "<br />"; 
    //echo "<br />Total Cost =  Trip Length x Mileage Rate x Vehicle Type Cost Factor x Vehicle Size Cost Factor + (Trip Length x Fuel Surcharge): <br />";
    //echo "=======================<br />";
    $subTotal = $cost['miles'] * $cost['effectiveRate']['miles_rate'] * $cost['vCostFactor'] * $cost['vSizeFactor'];
    //echo "  Miles (" . $cost['miles'] . ") x Rate  (" . $cost['effectiveRate']['miles_rate'] . ") =  " .  $cost['miles'] * $cost['effectiveRate']['miles_rate']  . "<br />";
    //echo "  Miles (" . $cost['miles'] . ") x Rate  (" . $cost['effectiveRate']['miles_rate'] . ") x Base Factor (" . $cost['vCostFactor']  . ") =  " .  $cost['miles'] * $cost['effectiveRate']['miles_rate'] * $cost['vCostFactor']  . "<br />";
    //echo "  Miles (" . $cost['miles'] . ") x Rate  (" . $cost['effectiveRate']['miles_rate'] . ") x Base Factor (" . $cost['vCostFactor']  . ")  x Vehicle Size Factor (" .$cost['vSizeFactor'] . ") =  " .  $cost['miles'] * $cost['effectiveRate']['miles_rate'] * $cost['vCostFactor'] * $cost['vSizeFactor']  . "<br />";
    $surcharges = $cost['miles']  * $cost['effectiveRate']['surcharge'];
    //echo "PLUS Surcharges:  " . $cost['miles']  * $cost['effectiveRate']['surcharge'] . "<br />";
    //echo "=======================<br />";
    //echo "Total Estimate:  " . ($subTotal + $surcharges) . "<br />";
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <!-- Load site meta information -->
    <?php include($root . 'includes/page-head-meta.php'); ?>
    <title><?php echo PROJECT_TITLE_SHORT . ' - '; ?>Domestic Shipping Estimate</title>
    <!-- Load site CSS -->
    <?php include($root . 'includes/page-styles.php'); ?>
    <!-- Load  page top Java Script assets -->
    <?php include($root . 'includes/page-head-scripts.php'); ?>

</head>
<body>	
	
    <header>
        <?php include($root . 'includes/nav-menu.php'); ?>
    </header>	
    <a id='backTop'>Back To Top</a>
    
    <div class="container-fluid carousel-form" style="background: url(<?php echo $root; ?>images/headers/header-<?php echo $headerNo; ?>.jpg) no-repeat fixed top center #ff7f00;">
        <div class="col-md-6">
            <?php 
                if (!$goodToGo) {
                    include($root . 'includes/page-head-carousel.php'); 
                } else { 
            ?>
             <!-- Special Carousel for Carrier Results -->
            <!-- Carousel -->
            <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                    <!-- Wrapper for slides -->
                    <div class="carousel-inner carousel-form">
                <!-- Indicators -->
                    <ol class="carousel-indicators">
                        <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
                        <li data-target="#carousel-example-generic" data-slide-to="1"></li>
                        <li data-target="#carousel-example-generic" data-slide-to="2"></li>
                        <li data-target="#carousel-example-generic" data-slide-to="3"></li>
                    </ol>
                        <div class="item active">
                            <img src="<?php echo $root . 'images/carrier-logos/' . $carrierArray[0]['logo_file']; ?>" class="img-responsive" alt="<?php echo $carrierArray[0]['name']; ?>" />
                            <!-- Static Header -->
                            <div class="header-text">
                                <div class="text-center">
                                    <h2><a target="_blank" href="<?php echo $carrierArray[0]['site_url']; ?>"><img src="<?php echo $root . 'images/carrier-logos/' . $carrierArray[0]['logo_file']; ?>" class="img-responsive" alt="<?php echo $carrierArray[0]['name']; ?>" /></a></h2>
                                    <br />
                                    <h3><?php echo $carrierArray[0]['city'] . ' ' . $carrierArray[0]['state']; ?></h3>
                                    <h3><span style="background-color: #ff7f00"><?php  echo ($carrierArray[0]['toll_free'] == '') ? $carrierArray[0]['phone'] : $carrierArray[0]['toll_free']; ?></span></h3>
                                    <br>
                                </div>
                            </div><!-- /header-text -->
                        </div>
                <?php
                        for ($i = 1; $i <= 3; $i++) {
                ?>            
                        <div class="item">
                            <img src="<?php echo $root . 'images/carrier-logos/' . $carrierArray[$i]['logo_file']; ?>" class="img-responsive" alt="<?php echo $carrierArray[$i]['name']; ?>" />
                            <!-- Static Header -->
                            <div class="header-text">
                                <div class="text-center">
                                    <h2><a target="_blank" href="<?php echo $carrierArray[$i]['site_url']; ?>"><img src="<?php echo $root . 'images/carrier-logos/' . $carrierArray[$i]['logo_file']; ?>" class="img-responsive" alt="<?php echo $carrierArray[$i]['name']; ?>" /></a></h2>
                                    <br />
                                    <h3><?php echo $carrierArray[$i]['city'] . ' ' . $carrierArray[$i]['state']; ?></h3>
                                    <h3><span style="background-color: #ff7f00"><?php  echo ($carrierArray[$i]['toll_free'] == '') ? $carrierArray[$i]['phone'] : $carrierArray[$i]['toll_free']; ?></span></h3>
                                    <br>
                                </div>
                            </div><!-- /header-text -->
                        </div>
                
                <?php        } ?>
                    </div>
                    <!-- Controls -->
                   <!-- <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
                        <span class="glyphicon glyphicon-chevron-left"></span>
                    </a>
                    <a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
                        <span class="glyphicon glyphicon-chevron-right"></span>
                    </a> -->
                </div><!-- /carousel -->
             <?php   
                } 
            ?>
        </div>
        <div class="col-lg-4 col-md-6 estimate">
            <h3>Your Instant Estimate</h3>
            <h3><small>Your Instant Vehicle Shipping Estimate is</small>  $<?php echo number_format ( ($subTotal + $surcharges) , 0 , "." , "," ); ?></h3>
            <div>
                <div class="col-xs-6">
                    <h5>Vehicle Information</h5>
                    <div>
                        Vehicle Type:  <em><?php echo getVehicleTypeName($vType); ?></em><br />
                        Vehicle Size:  <em><?php echo getVehicleSizeName($vSizeId); ?></em>
                        <div class="clear"></div>                    
                    </div>
                </div>
                <div class="col-xs-6">
                    <h5>Trip Information</h5>
                    <div>
                        From:  <em><?php echo $startLocStr ?></em><br />
                        To:  <em><?php echo $endLocStr ?></em><br />
                        Est. Miles: <em><?php echo number_format ( $cost['miles'] , 0 , "." , "," ) ?></em>
                    </div>
                </div>
            </div>
            <p>&nbsp;<br />Visit any of our preferred Vehicle Shipping Experts for more information or enter your email address and sit back while they contact you.
                <form name="request_email" method="post" action="<?php echo HOME_LINK; ?>request-email.php" id="request_email">
                    <input type="hidden" name="__reqID__" value="<?php echo $requestId; ?>" />
                    <input type="hidden" name="__carrierIDs__" value="<?php echo implode(':', $selectedCarrierIds); ?>" />
                    <div class="form-group">
                        <input type="email" class="input-sm" style="color: #FFF;" name="requester_email" id="requester_email" placeholder="Email Address" required="required" />
                        <button type="submit" class="btn btn-2 btn-sm" name="submit" <?php if ($requestId == 0) { echo " disabled"; } ?>>Send My Information</button>
                    </div>
                </form>
            </p>
        </div>
    <div class="clear"></div>
    </div>
    <div class="clear"></div>
    <footer>
            <?php include($root . 'includes/footer.php'); ?>
            <?php include($root . 'includes/footer-tagline.php'); ?>
    </footer>

    <!-- Core JavaScript Files -->
    <?php include($root . 'includes/page-bottom-scripts.php'); ?>
	
    <script>
        $(document).ready( function() {
            $('#backTop').backTop({
                'position' : 1200,
                'speed' : 500,
                'color' : 'orange'
            });
        });
    </script>

</body>
</html>
