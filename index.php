<?php
    $root = './';
    require('_includes/app_start.inc.php');
    global $headerFilter;
    $headerNo = random_int(1, HEADER_IMAGES);
    while (array_search($headerNo, $headerFilter)) {
        $headerNo = random_int(1, HEADER_IMAGES);        
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <!-- Load site meta informatoin -->
    <?php include($root . 'includes/page-head-meta.php'); ?>
    <title><?php echo PROJECT_TITLE_SHORT . ' - ' . PROJECT_TITLE; ?></title>
    <!-- Load site CSS -->
    <?php include($root . 'includes/page-styles.php'); ?>
    <!-- Load Page HEAD script files -->
    <?php include($root . 'includes/page-head-scripts.php'); ?>
</head>
<body>	
	
    <header>
        <?php include($root . 'includes/nav-menu.php'); ?>
    </header>
    <a id='backTop'>Back To Top</a>
    
    <div class="container-fluid carousel-form" style="background: url(<?php echo $root; ?>images/headers/header-<?php echo $headerNo; ?>.jpg) no-repeat fixed top center #ff7f00;">
        <div class="col-md-6 hidden-sm hidden-xs">
            <?php include($root . 'includes/page-head-carousel.php'); ?>
        </div>
        <div class="col-lg-4 col-md-6 calc-form estimate">
            <h3>Enter Your Vehicle Shipping Details</h3>
            <form action="<?php echo HOME_LINK; ?>quote.php" method="POST">
                <div id="vehicle-types">
                    <?php 
                        $vehicleTypes = getVehicleTypes();
                        $vehicleSizes = getVehicleSizes();
                    ?>
                    <select id="vehicle-type" name="vehicle-type" required>
                        <option value="" hidden selected disabled>Vehicle Type</option>
                        <?php
                            foreach($vehicleTypes as $vType) {
                        ?>        
                        <option value="<?php echo $vType['id']; ?>"><?php echo $vType['class_name']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div id="vehicle-sizes">
                    <select id="vehicle-size" name="vehicle-size" required>
                        <option value="" hidden selected disabled>Vehicle Class</option>
                    <?php
                    foreach($vehicleSizes as $vSize) {
                        ?>        
                        <option value="<?php echo $vSize['vehicle-class'] . '::' . $vSize['id']; ?>" id="<?php echo $vSize['vehicle-class']; ?>" style="display: none;" ><?php echo $vSize['option']; ?></option>
                    <?php } ?>
                    </select>                    
                </div>
                <div id="startLoc">
                        <input class="nd-placeholder cf-input" id="fromZip" type="text" placeholder="Starting Zip Code" value="" />
                        <div>
                            <input data-geo-start="location" type="hidden" name="geo-start-location" id="geo-start-location" value="" />
                            <input data-geo-start="route" type="hidden" name="geo-start-route" value="" />
                            <input data-geo-start="street_number" type="hidden" name="geo-start-street_number" value="" />
                            <input data-geo-start="postal_code" type="hidden" name="geo-start-postal_code" value="" />
                            <input data-geo-start="locality" type="hidden" name="geo-start-locality" value="" />
                            <input data-geo-start="country_short" type="hidden" name="geo-start-country_short" value="" />
                            <input data-geo-start="administrative_area_level_1" type="hidden" name="geo-start-state" value="" />
                        </div>
                </div>
                <div id="destLoc">
                    <input class="nd-placeholder cf-input" id="toZip" type="text" placeholder="Destination Zip Code" value="" />
                    <div>
                        <input data-geo-end="location" type="hidden" name="geo-end-location" value="" />
                        <input data-geo-end="route" type="hidden" name="geo-end-route" value="" />
                        <input data-geo-end="street_number" type="hidden" name="geo-end-street_number" value="" />
                        <input data-geo-end="postal_code" type="hidden" name="geo-end-postal_code" value="" />
                        <input data-geo-end="locality" type="hidden" name="geo-end-locality" value="" />
                        <input data-geo-end="country_short" type="hidden" name="geo-end-country_short" value="" />
                        <input data-geo-end="administrative_area_level_1" type="hidden" name="geo-end-state" value="" />
                    </div>
                </div>
                <br />
                <button type="submit" class="btn btn-2 btn-sm" name="submit">Get My Estimate!</button><br />
            </form>
        </div>
    </div>
	
    <!-- /////////////////////////////////////////Content -->
    <div id="page-content" class="index-page">

        <!-- ////////////Content Box 01 -->
        <section class="box-content box-1 box-bg-black">
            <div class="no-gutter">
                <div class="col-md-6 fix-right col-sm-12">
                    <div class="box-image">
                        <img class="media__image " src="<?php echo $root;?>images/woman-car-keys.jpg" />
                    </div>
                </div>
                <div class="col-md-6 col-sm-12">
                    <div class="box-text">
                        <div class="heading">
                            <span>Instantly Calculate <br>Vehicle Shipping Costs</span>
                        </div>
                        <p>
                            Here at ShipCalc, all we need is your starting location and your desired destination to instantly provide you with
                            a valid estimate of your vehicle shipping costs.  We base these estimates on data we have amassed over years of 
                            working with the nations top Vehicle Shipping Carriers.
                        </p>
                        <p>
                            To get started, fill in your zip codes and vehicle information on this simple form!  From there, you can directly contact 
                            any of our shippers, or with a little more information, let them contact you via email.
                        </p>
                    </div>
                </div>
                </div>
            <div class="clear"></div>
        </section>

        <!-- ////////////Content Box 03 -->
        <section class="box-content box-3">
            <div class="no-gutter">
                <div class="col-lg-4 col-sm-6">
                    <a href="<?php echo HOME_LINK; ?>about.php" class="portfolio-box">
                        <img src="<?php echo $root; ?>images/checkerboard-1.png" class="img-responsive" alt="" />
                        <div class="portfolio-box-caption">
                            <div class="portfolio-box-caption-content">
                                <div class="project-category text-faded">Motorcycle</div>
                                <div class="project-name">Shipping Estimates</div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-sm-6">
                    <a href="<?php echo HOME_LINK; ?>about.php" class="portfolio-box">
                        <img src="<?php echo $root; ?>images/checkerboard-5.png" class="img-responsive" alt="" />
                        <div class="portfolio-box-caption">
                            <div class="portfolio-box-caption-content">
                                <div class="project-category text-faded">Boats, RVs and ATVs</div>
                                <div class="project-name">Shipping Estimates</div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-sm-6">
                    <a href="<?php echo HOME_LINK; ?>about.php" class="portfolio-box">
                        <img src="<?php echo $root; ?>images/checkerboard-3.png" class="img-responsive" alt="" />
                        <div class="portfolio-box-caption">
                            <div class="portfolio-box-caption-content">
                                <div class="project-category text-faded">SUV</div>
                                <div class="project-name">Shipping Estimates</div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-sm-6">
                    <a href="<?php echo HOME_LINK; ?>domestic-shipping.php" class="portfolio-box">
                    <img src="<?php echo $root; ?>images/checkerboard-4.png" class="img-responsive" alt="" />
                        <div class="portfolio-box-caption">
                            <div class="portfolio-box-caption-content">
                                <div class="project-category text-faded">Domestic</div>
                                <div class="project-name">Shipping</div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-sm-6">
                    <a href="<?php echo HOME_LINK; ?>about.php" class="portfolio-box">
                        <img src="<?php echo $root; ?>images/checkerboard-2.png" class="img-responsive" alt="" />
                        <div class="portfolio-box-caption">
                            <div class="portfolio-box-caption-content">
                                <div class="project-category text-faded">Vehicle</div>
                                <div class="project-name">Shipping Estimates</div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-sm-6">
                    <a href="<?php echo HOME_LINK; ?>international-shipping.php" class="portfolio-box">
                    <img src="<?php echo $root; ?>images/checkerboard-6.png" class="img-responsive" alt="" />
                        <div class="portfolio-box-caption">
                            <div class="portfolio-box-caption-content">
                                <div class="project-category text-faded">International</div>
                                <div class="project-name">Shipping</div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="clear"></div>
        </section>

    </div>

    <footer>
        <?php include($root. 'includes/footer.php'); ?>
        <?php include($root . 'includes/footer-tagline.php'); ?>
    </footer>
    <!-- Footer -->

    <!-- Core JavaScript Files -->
    <?php require($root . 'includes/page-bottom-scripts.php'); ?>
    <script src="http://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_MAPS_APIKEY; ?>&libraries=places"></script>
    <script src="<?php echo $root; ?>js/jquery.geocomplete.js"></script>
    <script>
        $(document).ready( function() {
            $('#backTop').backTop({
                'position' : 1200,
                'speed' : 500,
                'color' : 'orange',
            });
        });

        $("#fromZip").geocomplete({
            details: "form div div",
            detailsAttribute: "data-geo-start",
            autoselect: false,
            blur: false,
            geocodeafterresult: false,
            types: ['(regions)'],
            componentRestrictions:  
                { country: 'us' }
        });

        $("#toZip").geocomplete({
            details: "form div div",
            detailsAttribute: "data-geo-end",
            autoselect: false,
            blur: false,
            geocodeafterresult: false,
            types: ['(regions)'],
            componentRestrictions:  
                { country: 'us' }
        });

    $("#vehicle-type").change(function(){
            var x = $("#vehicle-type").val();
            $('#vehicle-size').get(0).selectedIndex = 0;
            $('#vehicle-size').children('option').each(function() { 
                if ( this.id === x ) {
                    this.style.display = "block";
                } else {
                    this.style.display = "none";
                }
            });
        });

    </script>
</body>
</html>
