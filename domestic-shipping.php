<?php
    $root = './';
    require('_includes/app_start.inc.php');
    global $headerFilter;
    $headerNo = random_int(1, HEADER_IMAGES);
    while (array_search($headerNo, $headerFilter)) {
        $headerNo = random_int(1, HEADER_IMAGES);        
    }
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <!-- Load site meta information -->
    <?php include($root . 'includes/page-head-meta.php'); ?>
    <title><?php echo PROJECT_TITLE_SHORT . ' - '; ?>Domestic Shipping</title>
    <!-- Load site CSS -->
    <?php include($root . 'includes/page-styles.php'); ?>
    <!-- Load  page top Java Script assets -->
    <?php include($root . 'includes/page-head-scripts.php'); ?>

</head>
<body>	
	
    <header>
        <?php include($root . 'includes/nav-menu.php'); ?>
    </header>
    <!-- Header -->

    <a id='backTop'>Back To Top</a>
    <!-- /Back To Top -->
    
    <div class="container-fluid carousel-form" style="background: url(<?php echo $root; ?>images//headers/header-<?php echo $headerNo; ?>.jpg) no-repeat fixed top center #ff7f00;">
        <div class="col-md-6 hidden-sm hidden-xs">
            <?php include($root . 'includes/page-head-carousel.php'); ?>
        </div>
    </div>
	
    <!-- /////////////////////////////////////////Content -->
    <div id="page-content" class="archive-page">
	
        <section class="box-content box-bg-white">
            <div class="box-post">
                <div class="heading">
                    <h2>Shipping Your Vehicle Within the Continential US</h2>
                    <div class="info">Hassle-Free Vehicle Shipping Estimate Calculations</div>
                </div>
                <div class="excerpt">
                    <p>
                        Here at <?php echo PROJECT_TITLE_SHORT; ?>, we appreciate that you may simply want a quick estimate of your vehicle shipping costs without 
                        the hassle of filling out an extensive form filled with contact information, and we also understand that many times our visitors are just browsing.  This is why you 
                        can fill out a simple form, with minimal information, to instantly see your vehicle shipping estimate.
                    </p>
                    <p>Once your estimate has been calculated, 
                        <?php echo PROJECT_TITLE_SHORT; ?> will match your specific request, based on the vehicle type and geography of the move, with some of our preferred vehicle shipping experts which are displayed
                        alongside your estimate.  If you choose to, you can then enter your email address and submit a request to have these shippers contact you with further
                        information in order to compare and finalize pricing and arrange your vehicle shipment.
                    </p>
                </div>
                <h3>Shipping Estimates</h3>
                <p>
                    Automobile shipping estimates and rates in the industry include several components that vary with each customer. The 
                    distance, the vehicle type and size, and its condition are some variables that will affect the price.
                </p>
                <p>
                    <?php echo PROJECT_TITLE_SHORT; ?>'s  instant vehicle shipping calculator generates shipping estimates using your vehicle type and size classification.  The estimate engine utilizes
                    seasonal rates and distance to calculate a base estimate assuming the most common variables, such as operational status of the vehicle 
                    and the industry standard open transport mode.
                </p>
                <blockquote>
                    <p>Additional shipping options for non-operational vehicles and requests for specialized transport modes, such as enclosed shipping, will affect your final price and can
                    be arranged directly with the shipper.</p>
                    <p>Here are some good points to remember:</p>

                    <div class="note">
                        <ol>
                            <li>Enclosed Container Transport can increase your vehicle shipping costs by as much as 35%</li>
                            <li>Operational vehicles are the least expensive to move</li>
                            <li>Non-operational vehicles introduce a number of additional variables into the price of the move.  The actual status of the vehicle will determine the final cost.</li>
                        </ol>
                        <div class="clear"></div>
                    </div>
                </blockquote>
                
                <h3>Seasonal Factors</h3>
                <p>
                    Vehicle shipping and transportation costs differ depending on the season of the year.  <?php echo PROJECT_TITLE_SHORT; ?>'s
                    sophisticated estimate engine takes seasonal factors into consideration when calculating your estimate, and our vehicle shipping
                    experts ensure that the seasonal rate information used to build your estimate is always current with the latest industry data.
                </p>
                <h3>Locality</h3>
                <p>
                    Here at <?php echo PROJECT_TITLE_SHORT; ?>, we strive to make your experience in getting your instant vehicle shipping
                    estimate the simplest and most pain-free method available on the web today!  In keeping with this goal, <?php echo PROJECT_TITLE_SHORT; ?>
                    utilizes a zip-code based <a href="https://developers.google.com/maps/" target="_blank">Google Maps Distance Matrix API</a>&trade; to calculate the most accurate
                    trip length possible, which is one of the main driving factors in your personal estimate.
                </p>
                <p>
                    The actual trip length of your vehicle transport will depend on additional factors, such as actual physical addresses, ease of accessibility for the driver when picking up and
                    delivering your vehicle, and so forth.
                    All of these minute details will be finalized with your selected vehicle shipping expert once you submitted your request to be contacted by our shippers.
                </p>
                <h3>Additional Services</h3>
                <p>
                    Be sure to check with your selected carriers for available additional services such as
                </p>
                <div class="note">
                    <ol>
                        <li>Door to Door Transport</li>
                        <li>Expedited Shipping</li>
                        <li>Enclosed Vehicle Transport</li>
                    </ol>
                    <div class="clear"></div>
                </div>
            </div>
        </section>
    </div>

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
	
