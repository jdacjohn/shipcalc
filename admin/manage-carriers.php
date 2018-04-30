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
       $carrierIds = getCarrierIdsNotIn(array(0), 500);
       printVarIfDebug($carrierIds, getenv('gDebug'), 'Carrier Ids resulting from sending getCarrierIdsNotIn() with empty array');
       printVarIfDebug($_POST, getenv('gDebug'), 'POST VARS');
       $updates = array();
       foreach ($carrierIds as $cid) {
           $tempCarrier = array();
           $tempCarrier['id'] = $cid;
           $tempCarrier['active'] = (isset($_POST['active-' . $cid])) ? 'Y' : 'N';
           array_push($updates, $tempCarrier);
       }
       printVarIfDebug($updates, getenv('gDebug'), 'Update Array');
       updateCarrierActive($updates);
    }
?>    
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <?php include($root . 'includes/page-head-meta.php'); ?>
    <title><?php echo PROJECT_TITLE_SHORT . ' '; ?>Carrier Management</title>
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
                <h3><?php echo PROJECT_TITLE_SHORT; ?> Carrier Management</h3>
                <p class="admin"><strong>[ Welcome <?php echo $_SESSION[SESSION_NAME]['user']['name']; ?> - <a href="<?php echo HOME_LINK; ?>login.php?confirm=logout">Logout</a> ]</strong></p>
            </div>
        </div>
        <!-- Rate Summary -->
        <div class="col-md-8 col-md-offset-2 admin-box">
            <h3>Carriers</h3>
            <div>
            <form class="form-horizontal" method="post" action="<?php echo $root; ?>admin/manage-carriers.php">
                <input type="hidden" name="__confirm__save" value="true" />
                <table class="rates-mg" id="admin-box">
                    <tr>
                        <th>Carrier</th>
                        <th>Contact</th>
                        <th>Displays</th>
                        <th>Leads</th>
                        <th>Updated</th>
                        <th>Active</th>
                    </tr>
                    <?php 
                        $carriers = getAllCarriers(); 
                        foreach($carriers as $carrier) {
                            if ($carrier['active'] == 'Y') {
                                $emStart = '';
                                $emEnd = '';
                            } else {
                                $emStart = '<span style="color: #667;">';
                                $emEnd = '</span>';
                            }
                    ?>
                    <tr>
                        <td><?php if ($carrier['active'] == 'Y') { ?><a href="<?php echo SITE_ROOT . '/admin/edit-carrier.php?cid=' . $carrier['id']; ?>" title="View / Edit this Carrier"><?php } ?><?php echo $emStart . $carrier['name'] . $emEnd; ?><?php if ($carrier['active'] == 'Y') { ?></a><?php } ?></td>
                        <td><?php echo $emStart . $carrier['contact'] . $emEnd; ?></td>
                        <td><?php echo $emStart . $carrier['displays'] . $emEnd; ?></td>
                        <td><?php echo $emStart . $carrier['leads'] . $emEnd; ?></td>
                        <td><?php echo $emStart . (($carrier['last_updated'] == '0000-00-00 00:00:00') ? '' : $carrier['last_updated']) . $emEnd; ?></td>
                        <td><input type="checkbox" name="active-<?php echo $carrier['id']; ?>" <?php if ($carrier['active'] == 'Y') { echo 'checked'; } ?> /></td>                        
                    </tr>                    
                        <?php } ?>
                    <tr>
                        <td colspan="4">Inactive carriers are <span style="color: #667">greyed out</span>. Activate the carrier to view or edit.</td>
                        <td><a href="<?php echo $root . 'admin/index.php'; ?>">[ Return ]</a>&nbsp;&nbsp;<a href="<?php echo $root . 'admin/edit-carrier.php?cid=0'; ?>" />[ Add New ]</a></td>
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
