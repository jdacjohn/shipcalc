<?php 
    $root = '../';
    require_once('../_includes/app_start.inc.php');
    global $headerFilter;
    $headerNo = random_int(1, HEADER_IMAGES);
    while (array_search($headerNo, $headerFilter)) {
        $headerNo = random_int(1, HEADER_IMAGES);        
    }
    require_login();
    
    if (isset($_POST['__confirm__save'])) {
       $seasonIds = getSeasonIds();
       $updates = array();
       foreach ($seasonIds as $sid) {
           $tempSeason = array();
           $tempSeason['id'] = $sid;
           $tempSeason['base'] = filter_input(INPUT_POST, 'base-' . $sid, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
           $tempSeason['surcharge'] = filter_input(INPUT_POST, 'surcharge-' . $sid, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
           array_push($updates, $tempSeason);
       }
       updateSeasons($updates);
       updateActiveSeason($_POST['active']);
    }
?>    
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <?php include($root . 'includes/page-head-meta.php'); ?>
    <title><?php echo PROJECT_TITLE_SHORT . ' '; ?>Rate Management</title>
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
                <h3><?php echo PROJECT_TITLE_SHORT; ?> Rate Management</h3>
                <p class="admin"><strong>[ Welcome <?php echo $_SESSION[SESSION_NAME]['user']['name']; ?> - <a href="<?php echo HOME_LINK; ?>login.php?confirm=logout">Logout</a> ]</strong></p>
            </div>
        </div>
        <!-- Rate Summary -->
        <div class="col-md-8 col-md-offset-2 admin-box">
            <h3>Shipping Estimate Calculations</h3>
            <h3><small>Seasonal Mileage and Fuel Rates*</small></h3>
            <div>
            <form class="form-horizontal" method="post" action="<?php echo $root; ?>admin/manage-rates.php">
                <input type="hidden" name="__confirm__save" value="true" />
                <table class="rates-mg" id="admin-box">
                    <tr>
                        <th>Active</th>
                        <th>Season</th>
                        <th>Base Rate per Mile</th>
                        <th>Surcharge per Mile</th>
                        <th>Last Updated</th>
                        <th>Updated By</th>
                    </tr>
                    <?php 
                        $rates = getSeasonRates(); 
                        foreach($rates as $rate) {
                            $emStart = ($rate['active'] == 1) ? '<em><span style="color: #ff7f00;">' : '';
                            $emEnd = ($rate['active']) == 1 ? '</em></span>' : '';
                    ?>
                    <tr>
                        <td><input type="radio" name="active" value="<?php echo $rate['id']; ?>" <?php if ($rate['active'] == 1) { echo 'checked'; } ?> /></td>
                        <td><?php echo $emStart . $rate['name'] . $emEnd; ?></td>
                        <td><input type="number" min="0" step=".01" name="<?php echo 'base-' . $rate['id']; ?>" value="<?php echo number_format($rate['miles_rate'],2,".",",") ;  ?>" /></td>
                        <td><input type="number" min="0" step=".01" name="<?php echo 'surcharge-' . $rate['id']; ?>" value="<?php echo number_format($rate['surcharge'],2,".",",") ;  ?>" /></td>
                        <td><?php echo $emStart . $rate['lastUpdated'] . $emEnd; ?></td>
                        <td><?php echo $emStart . $rate['updatedBy'] . $emEnd; ?></td>                        
                    </tr>                    
                        <?php } ?>
                    <tr>
                        <td colspan="4">*Current active rate is displayed in <em>Italics</em></td>
                        <td><a href="<?php echo $root . 'admin/index.php'; ?>">[ Return ]</a>&nbsp;&nbsp;</td>
                        <td align="right"><button type="submit" class="btn btn-admin btn-sm" name="submit">Save</button><br /></td>
                    </tr>
                </table>
                
            </form>
            </div>
        </div>
    </div>
    
	
    <!-- /////////////////////////////////////////Content -->
    <div id="page-content" class="archive-page">
	
    </div>
    <footer>
        <?php include($root . 'includes/footer-tagline.php'); ?>
    </footer>

	
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
