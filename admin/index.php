<?php 
    $root = '../';
    require_once('../_includes/app_start.inc.php');
    $headerNo = random_int(1, HEADER_IMAGES);
    while (array_search($headerNo, $headerFilter)) {
        $headerNo = random_int(1, HEADER_IMAGES);        
    }
    require_login();
?>    
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <?php include($root . 'includes/page-head-meta.php'); ?>
    <title><?php echo PROJECT_TITLE_SHORT . ' '; ?>Admin</title>
    <?php include($root . 'includes/page-styles.php'); ?>
    <style>
        body { background-color: #000; }
    </style>
        
    <?php include($root . 'includes/page-head-scripts.php'); ?>

</head>

<body bgcolor="#000000">	
	
    <header>
        <?php include($root . 'includes/nav-menu.php'); ?>
    </header>	
    <a id='backTop'>Back To Top</a>

    <div class="container-fluid carousel-form" style="background: url(<?php echo $root; ?>images/headers/header-<?php echo $headerNo; ?>.jpg) no-repeat fixed top center #ff7f00;">
        <!-- Admin welcom header -->
        <div class="col-md-12">
            <div id="admin-header">
                <h3><?php echo PROJECT_TITLE_SHORT; ?> Carrier and Rate Management</h3>
                <p class="admin"><strong>[ Welcome <?php echo $_SESSION[SESSION_NAME]['user']['login']; ?> - <a href="<?php echo HOME_LINK; ?>login.php?confirm=logout">Logout</a> ]</strong></p>
            </div>
        </div>
        <!-- Rate Summary -->
        <div class="col-md-3 col-md-offset-1 admin-box">
            <h3>Rates</h3>
            <h3><small>Current Active Rates for Estimate Calculations</small></h3>
            <div class="note">
                <ul>
                    <?php $rate = getActiveRate(); ?>
                    <li> <a href="#" title="The active season controls which rates are used in calulations">Active Season:  <em><?php echo $rate['name']; ?></em></a></li>
                    <li> <a href="#" title="The base charge per mile that is used to estimate vehicle shipping costs">Base Cost / Mile:  <em><?php echo $rate['miles_rate']; ?></em></a></li>
                    <li> <a href="#" title="An additional per mile charge that is included in the vehicle shipping estimate">Fuel Surcharge:  <em><?php echo $rate['surcharge']; ?></em></a></li>
                </ul>
                <div class="clear"></div>
            </div>
            <div class="button-container">
                    <form method="post" action="<?php echo $root; ?>admin/manage-rates.php">
                        <button type="submit" title="View or change seasonal mileage and fuel surcharge rates" class="btn btn-admin btn-sm" name="submit">Edit Rates</button><br />
                    </form>&nbsp;
                    <form method="post" action="<?php echo $root; ?>admin/manage-vehicle-mfs.php">
                        <button type="submit" title="View or change vehicle type and size multiplication factors used in estimate calculations" class="btn btn-admin btn-sm" name="submit">V-Factors</button><br />
                    </form>
            </div>
        </div>
        <!-- Requests Summary -->
        <div class="col-md-3 admin-box">
            <h3>Requests Summary</h3>
            <h3><small>Site Estimates, Click-Throughs, and Email Requests</small></h3>
            <div class="note">
                <ul>
                    <?php $reqSummary = getRequestSummary(); ?>
                    <li> <a href="#" title="The number of times site visitors have filled out the estimate request form">Simple Requests: </em><?php echo $reqSummary['requests']; ?> </em></a></li>
                    <li> <a href="#" title="The average of all estimates reported to date">Estimate Average: <em>$<?php echo number_format(($reqSummary['avgBase'] + $reqSummary['avgSurcharge']), 0, ",", ","); ?></em></a></li>
                    <li> <a href="#" title="The number of times site visitors have submitted email requests resulting in lead generation">Email Requests: </em><?php echo $reqSummary['email_requests']; ?></em></a></li>
                </ul>
                <div class="clear"></div>
            </div>
        </div>
        <!-- Carrier Summary -->
        <div class="col-md-3 admin-box">
            <h3>Carriers</h3>
            <h3><small>Summary of Carriers in the System</small></h3>
            <div class="note">
                <ul>
                    <?php $summary = getAdminCarrierSummary(); ?>
                    <li> <a href="#" title="The number of shippers in the system listed as operating in the Lower 89 States">Lower 48: <em><?php echo $summary['lower48']; ?> </em></a> </li>
                    <li> <a href="#"title="The number of continental US shippers who also deliver to Alaska and Hawaii">Lower 48 Plus AK and HI: <em> <?php echo $summary['ak_ah']; ?> </em></a> </li>
                    <li> <a href="#"title="The number of shippers listed as operating internationally">International Shippers: <em> <?php echo $summary['intl']; ?> </em></a></li>
                    <li> <a href="#"title="The number of leads emailed to shippers to date">Leads Sent: <em> <?php echo $summary['leads']; ?> </em></a></li>
                    <li> Carrier Click-Throughs: (Coming Soon)</li>
                </ul>
                <div class="clear"></div>
            </div>
            <form class="form-horizontal" method="get" action="<?php echo $root; ?>admin/manage-carriers.php">
                <button type="submit" title="Activate/deactive, view, edit, and create new carriers" class="btn btn-admin btn-sm" name="submit">Manage Carriers</button><br />
            </form>
        </div>
    <footer>
        <?php include($root . 'includes/footer-tagline.php'); ?>
    </footer>

    </div>
    
	
    <!-- /////////////////////////////////////////Content -->
    <div id="page-content" class="archive-page">
	
    </div>

	
    <!-- Core JavaScript Files -->
    <script src="<?php echo $root; ?>js/bootstrap.js"></script>
    <script src="<?php echo $root; ?>js/jquery.backTop.min.js"></script>
    <script>
        $(document).ready( function() {
            $('#backTop').backTop({
                'position' : 1200,
                'speed' : 500,
                'color' : 'orange',
            });
        });
        </script>
	
        <!-- Google Map -->
        <script>
            $('.maps').click(function () {
                $('.maps iframe').css("pointer-events", "auto");
            });
            $( ".maps" ).mouseleave(function() {
                $('.maps iframe').css("pointer-events", "none"); 
            });
        </script>
</body>
</html>
