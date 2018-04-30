<?php
    $root = './';
    require('_includes/app_start.inc.php');
    include($root . '_includes/classes/ArrayUtil.php');
    
    global $headerFilter;
    $headerNo = random_int(1, HEADER_IMAGES);
    while (array_search($headerNo, $headerFilter)) {
        $headerNo = random_int(1, HEADER_IMAGES);        
    }
    
    $carrierCriteria['ovs'] = 'Y';
    $matchingIds = getCarrierIdsMatching($carrierCriteria);
    $arrUtil = new \shipcalc\ArrayUtil($matchingIds);
    $selectedCarrierIds = $arrUtil->addAll();
    $carrierArray = getSelectedCarriers($selectedCarrierIds);

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <!-- Load site meta information -->
    <?php include($root . 'includes/page-head-meta.php'); ?>
    <title><?php echo PROJECT_TITLE_SHORT . ' - '; ?>International Shipping</title>
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
    
    <div class="container-fluid carousel-form" style="background: url(<?php echo $root; ?>images/headers/header-<?php echo $headerNo; ?>.jpg) no-repeat fixed top center #ff7f00;">
        <div class="col-md-6">
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
                        for ($i = 1; $i <= count($carrierArray) - 1; $i++) {
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
        </div>
    </div>
	
    <!-- Page content -->
    <div id="page-content" class="archive-page">
	
        <section class="box-content box-bg-white">
            <div class="box-post">
                <div class="heading">
                    <h2>International Shipping</h2>
                    <div class="info">Shipping Your Vehicle Overseas</div>
                </div>
                <div class="excerpt">
                    <p>
                        Selecting the right carrier for your international vehicle shipping needs can be a daunting task.  While <?php echo PROJECT_TITLE_SHORT; ?> does
                        not provide instant vehicle shipping estimates for overseas destinations, we do offer suggestions for respected overseas shipping experts who can
                        fulfill your vehicle shipping needs.
                    </p>
                    <p>
                        Click on the logs of any of the companys shown above to visit these carriers, or call them directly at the number listed to discuss your specific requirements.
                    </p>
                </div>
                <h3>Factors to Consider / Things You'll Need</h3>
                <blockquote>
                    <p>When choosing an international vehicle shipping carrier, here are some important factors to consider:</p>

                    <div class="note">
                        <ol>
                            <li>Roll On - Roll Off vs. Container Car Shipping</li>
                            <li>Limitaions on vehicle content will rely on shipping method</li>
                            <li>Keys, Registration and Title, Photo ID</li>
                            <li>Creditor / Lessor Notarized Statements Authorizing Transport</li>
                        </ol>
                        <div class="clear"></div>
                    </div>
                </blockquote>
                
                <h3>Transport Times</h3>
                <p>
                    Transport times indicate 'time on the water' and do not include consolidation times before sail.  Additional transshipment times, typically 5 days, are
                    required to deliver your vehicle to you once it arrives at the destination port.
                </p>
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
	
