<?php
/*
* shipcalc
* request-email
*
* Description - Enter a description of the file and its purpose.
*
* Author:      John Arnold <john@jdacsolutions.com>
    * Link:           https://jdacsolutions.com
    *
    * Created:             Apr 23, 2018 11:01:23 PM
    * Last Updated:    Date 
    * Copyright            Copyright 2018 JDAC Computing Solutions All Rights Reserved
    */

    $root = './';
    require($root . '_includes/app_start.inc.php');
    require($root . '_includes/classes/RequestMailer.php');
    require($root . '_includes/classes/ShipCalcRequest.php');
    require($root . '_includes/classes/Location.php');
    
    // Get out form post values
    $requestId = $_POST['__reqID__'];
    $carrierIds = $_POST['__carrierIDs__'];
    $recipient = filter_input(INPUT_POST, 'requester_email', FILTER_SANITIZE_EMAIL);
    //printVarIfDebug($recipient, getenv('gDebug'), 'Recipient');
    $reqArr = getRequestDetail($requestId);
    //printVarIfDebug($reqArr, getenv('gDebug'), 'Request Row from DB');
    // Build the start and end locations for the request
    $startLoc = new shipcalc\Location($reqArr['from_lat'] . ',' . $reqArr['from_lon']);
    $endLoc = new shipcalc\Location($reqArr['to_lat'] . ',' . $reqArr['to_lon']);
    $startLoc->setCity($reqArr['from_city']);
    $startLoc->setState($reqArr['from_state']);
    $startLoc->setCountryCode($reqArr['from_cc']);
    $startLoc->setZipCode($reqArr['from_zip']);
    $endLoc->setCity($reqArr['to_city']);
    $endLoc->setState($reqArr['to_state']);
    $endLoc->setCountryCode($reqArr['to_cc']);
    $endLoc->setZipCode($reqArr['to_zip']);
    //printVarIfDebug($startLoc, getenv('gDebug'), 'Start Location Object');
    //printVarIfDebug($endLoc, getenv('gDebug'), 'End Location Object');
    // Build the request
    // Build the request object, calculate the estimated cost
    $request = new shipcalc\ShipCalcRequest($reqArr['vehicle_class'], $reqArr['vehicle_size'], $startLoc, $endLoc);
    //printVarifDebug($shipCalcRequest, getenv('gDebug'), 'ShipCalcRequest on Instantiation');
    $request->setEstimatedBaseCost($reqArr['base_quote']);
    $request->setEstimatedSurcharge($reqArr['surcharge']);
    $request->setTripLen($reqArr['est_units']);
    $request->setRequestId($requestId);
    $request->setVClassName(getVehicleTypeName($request->getVehicleClass()));
    $request->setVClassSizeName(getVehicleSizeName($request->getVehicleClassSize()));
    //printVarifDebug($request, getenv('gDebug'), 'ShipCalcRequest from DB');
    //printVarIfDebug($carrierIds, getenv('gDebug'), 'Carrier Id POST Values');
    $carriers = getSelectedCarriers(explode(':', $carrierIds));
    //printVarIfDebug($carriers, getenv('gDebug'), 'Full Carriers');
    
    // Process the mailings
    $msgArr = array(
        'content-header' => 'Thank You!',
        'content' => 'Your request has been submitted.  Please check your inbox as our Vehicle Shipping Experts will contact you shortly'
    );
    $mailer = new shipcalc\RequestMailer($request, $carriers, $recipient);
    //printVarIfDebug($mailer, getenv('gDebug'), 'Request Mailer Object');
    $requesterId = insertRequester($request, $mailer);
    // Sends notification to system contact with all request  and carrier identifcation information.
    $msg = $mailer->sendNotify();
    if ($msg == 'Error occurred while attempting to send mail') {
        $msgArr['content-header'] = "We're Sorry";
        $msgArr['content'] = 'An error occurred while processing your request.  Please try again or <a href="' . HOME_LINK . 'contact.php">Contact Us</a>';
    }
    $msg = $mailer->sendAck();
    if ($msg == 'Error occurred while attempting to send mail') {
        $msgArr['content-header'] = "We're Sorry";
        $msgArr['content'] = 'An error occurred while processing your request.  Please try again or <a href="' . HOME_LINK . 'contact.php">Contact Us</a>';
    }
    // Send emails to each of the carriers.
    foreach($carriers as $carrier) {
        $carrierArr = array();
        array_push($carrierArr, $carrier);        
        $mailer->setActiveCarriers($carrierArr);
        $msg = $mailer->sendInfoRequest();
        if ($msg == 'Error occurred while attempting to send mail') {
            $msgArr['content-header'] = "We're Sorry";
            $msgArr['content'] = 'An error occurred while processing your request.  Please try again or <a href="' . HOME_LINK . 'contact.php">Contact Us</a>';
        }
        insertCarrierLead($carrier, $requesterId, $msg);
    }

    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <!-- load site meta information -->
    <?php include($root . 'includes/page-head-meta.php'); ?>)
    <title><?php echo PROJECT_TITLE_SHORT; ?> - Thank You</title>
    <!-- Load site/page CSS files -->
    <?php include($root . 'includes/page-styles.php'); ?>
    <!-- Load Page HEAD script files -->
    <?php include($root . 'includes/page-head-scripts.php'); ?>
</head>
<body>	
	
    <!-- Navigation -->
    <?php include($root . 'includes/nav-menu.php'); ?>
	
    <!-- <header> -->
    <!-- Carousel -->
        <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
            <!-- Wrapper for slides -->
            <div class="carousel-inner">
                <div class="item active">
                    <img src="<?php echo $root; ?>images/slider-3.jpg" alt="Thank you for your Vehicle Shipping Request">
                    <!-- Static Header -->
                    <div class="login-header-text">
                        <div class="col-md-12 text-center">
                            <p>&nbsp;</p>
                            <h3><big><?php echo $msgArr['content-header']; ?></big></h3>
                            <div class="admin">
                            <h3><small><?php echo $msgArr['content']; ?></small></h3>
                            <h5><a href="<?php echo SITE_ROOT; ?>">Get Another Request</a></h5>
                            </div>
                            <br />
                        </div>
                    </div><!-- /header-text -->
                </div>
            </div>
        </div><!-- /carousel -->    <div id="page-content" class="archive-page">
        <br />
    </div>

    <footer>
        <?php include($root . 'includes/footer-tagline.php'); ?>
    </footer>

    <!-- Core JavaScript Files -->
    <?php include($root . 'includes/page-bottom-scripts.php'); ?>
    <script>
        $(document).ready( function() {
            $('#backTop').backTop({
                'position' : 1200,
                'speed' : 500,
                'color' : 'orange',
            });
        });
    </script>

</body>
</html>