<?php 
    $root = '../';
    require_once('../_includes/app_start.inc.php');
    global $headerFilter;
    $headerNo = random_int(1, HEADER_IMAGES);
    while (array_search($headerNo, $headerFilter)) {
        $headerNo = random_int(1, HEADER_IMAGES);        
    }
    require_login();
    $cid = getParam('cid');    
    
    if (isset($_POST['__confirm__save'])) {
       $update = array();
       //printVarIfDebug($_POST, getenv('gDebug'), 'POST DATA');
       // Grab all the $_POST vars to update the carrier with
       $update['id'] = $_POST['attrib_id'];
       $update['c_id'] = $cid;
       
       $update['lower_48'] = isset($_POST['lower_48']) ? 'Y' : 'N';
       $update['ak'] = isset($_POST['ak']) ? 'Y' : 'N';
       $update['hi'] = isset($_POST['hi']) ? 'Y' : 'N';
       $update['ovs'] = isset($_POST['ovs']) ? 'Y' : 'N';
       $update['pov'] = isset($_POST['pov']) ? 'Y' : 'N';
       $update['mc'] = isset($_POST['mc']) ? 'Y' : 'N';
       $update['rv'] = isset($_POST['rv']) ? 'Y' : 'N';
       $update['atvutv'] = isset($_POST['atvutv']) ? 'Y' : 'N';
       $update['boat'] = isset($_POST['boat']) ? 'Y' : 'N';
       $update['open'] = isset($_POST['open']) ? 'Y' : 'N';
       $update['enclosed'] = isset($_POST['enclosed']) ? 'Y' : 'N';       
       //printVarIfDebug($update, getenv('gDebug'), 'Update Array');
       updateCarrierAttributes($update);
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
    
    <?php 
        $thisCarrier = getCarrier($cid);
        $carrierAttributes = getCarrierAttributes($cid);
    ?>
    
    <div class="col-md-12">
        <div id="admin-header">
            <h3><?php echo PROJECT_TITLE_SHORT; ?> Carrier Management</h3>
            <p class="admin"><strong>[ Welcome <?php echo $_SESSION[SESSION_NAME]['user']['name']; ?> - <a href="<?php echo HOME_LINK; ?>login.php?confirm=logout">Logout</a> ]</strong></p>
        </div>
    </div>
    <!-- Rate Summary -->
    <div class="col-md-10 col-md-offset-1 admin-box">
        <h3><?php 
            echo $thisCarrier['name']; ?> - Service Roles</h3>
        <div>
        <form class="form-horizontal" method="post" action="<?php echo $root; ?>admin/manage-attributes.php?cid=<?php echo $cid; ?>">
            <input type="hidden" name="__confirm__save" value="true" />
            <input type="hidden" name="attrib_id" value="<?php echo $carrierAttributes['id']; ?>" />
            <table class="rates-mg" id="admin-box">
                <tr>
                    <th colspan="6">Geographical Settings</th>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td colspan="4">
                        <input type="checkbox" name="lower_48" <?php if ($carrierAttributes['lower_48'] == 'Y') { echo 'checked'; } ?> /> Lower 48&nbsp;&nbsp;&nbsp;
                        <input type="checkbox" name="ak" <?php if ($carrierAttributes['ak'] == 'Y') { echo 'checked'; } ?> /> Alaska&nbsp;&nbsp;&nbsp;
                        <input type="checkbox" name="hi" <?php if ($carrierAttributes['hi'] == 'Y') { echo 'checked'; } ?> /> Hawaii&nbsp;&nbsp;&nbsp;
                        <input type="checkbox" name="ovs" <?php if ($carrierAttributes['ovs'] == 'Y') { echo 'checked'; } ?> /> International</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <th colspan="6">Vehicle Types</th>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td colspan="4">
                        <input type="checkbox" name="pov" <?php if ($carrierAttributes['pov'] == 'Y') { echo 'checked'; } ?> /> Passenger Vehicles&nbsp;&nbsp;&nbsp;
                        <input type="checkbox" name="mc" <?php if ($carrierAttributes['mc'] == 'Y') { echo 'checked'; } ?> /> Motorcyles&nbsp;&nbsp;&nbsp;
                        <input type="checkbox" name="rv" <?php if ($carrierAttributes['rv'] == 'Y') { echo 'checked'; } ?> /> RVs&nbsp;&nbsp;&nbsp;
                        <input type="checkbox" name="atvutv" <?php if ($carrierAttributes['atvutv'] == 'Y') { echo 'checked'; } ?> /> ATVs&nbsp;&nbsp;&nbsp;
                        <input type="checkbox" name="boat" <?php if ($carrierAttributes['boat'] == 'Y') { echo 'checked'; } ?> /> Boats</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <th colspan="6">Hauling Method</th>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td colspan="4">
                        <input type="checkbox" name="open" <?php if ($carrierAttributes['open'] == 'Y') { echo 'checked'; } ?> /> Open Trailer&nbsp;&nbsp;&nbsp;
                        <input type="checkbox" name="enclosed" <?php if ($carrierAttributes['enclosed'] == 'Y') { echo 'checked'; } ?> /> Enclosed Trailer&nbsp;&nbsp;&nbsp;
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <?php
                        if ($carrierAttributes['updated_by'] == '') {
                            $updatedBy = $carrierAttributes['created_by'];
                            $updatedAt = $carrierAttributes['create_date'];
                        } else {
                            $updatedBy = $carrierAttributes['updated_by'];
                            $updatedAt = $carrierAttributes['last_updated'];
                        }
                    ?>
                    <td colspan="4">Last Updated: <?php echo $updatedAt; ?>&nbsp;&nbsp;Updated By: <?php echo $updatedBy; ?></td>
                    <td><a href="<?php echo $root . 'admin/edit-carrier.php?cid=' . $cid; ?>">[ Return ]</a></td>
                    <td align="right"><button type="submit" class="btn btn-admin btn-sm" name="submit">Save</button><br /></td>
                </tr>
            </table>
        </form>
        </div>
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
