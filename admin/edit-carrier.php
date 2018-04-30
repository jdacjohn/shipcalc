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
    use Gregwar\Image\Image;
    
    if (isset($_POST['__confirm__save'])) {
       $update = array();
       $update['logo_file'] = $_POST['current_logo_file'];
       //printVarIfDebug($carrierIds, getenv('gDebug'), 'Carrier Ids resulting from sending getCarrierIdsNotIn() with empty array');
       printVarIfDebug($_POST, getenv('gDebug'), 'POST VARS');
       printVarIfDebug($_FILES, getenv('gDebug'), 'FILES VARS');
       if (isset($_FILES['logo_file']['name']) && $_FILES['logo_file']['name'] != '') {
           printvar('We have a new logo file.');
           $update['logo_file'] = $_FILES['logo_file']['name'];
           $logo_dir = $root . "images/carrier-logos/";
           $logo_file = $logo_dir . basename($_FILES["logo_file"]["name"]);
           printVarIfDebug($logo_file, true, 'New Logo File Name');
           $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($logo_file,PATHINFO_EXTENSION));
            // Check if image file is a actual image or fake image
            $check = getimagesize($_FILES["logo_file"]["tmp_name"]);
            if($check !== false) {
                $uploadOk = 1;
            } else {
               $update['logo_file'] = $_POST['current_logo_file'];
                $uploadOk = 0;
            }
            // Check if file already exists
            if (file_exists($logo_file)) {
               $update['logo_file'] = $_POST['current_logo_file'];
                $uploadOk = 0;
            }
            if ($uploadOk == 1) {
                if (!move_uploaded_file($_FILES["logo_file"]["tmp_name"], $logo_file)) {
                    $update['logo_file'] = $_POST['current_logo_file'];
                } else {
                    // Resize the image to acceptable logo porportions  in SM, NORMAL, and LG sizes
                    printVarIfDebug('Attempting to resize image', getenv('gDebug'), 'Moved new logo file.  Attempting Resize');
                    $logoFileName = random_int(100000, 5000000);
                    // Create the small thumbnail img file
                    $baseName = $logoFileName . '-sm';
                    printVarIfDebug($logo_dir, getenv('gDebug'), 'DIR Name =>');
                    printVarIfDebug($baseName, getenv('gDebug'), 'File Name =>');
                    Image::open($logo_file)
                     ->resize(LOGO_WIDTH[LOGO_THUMBNAIL], LOGO_HEIGHT[LOGO_THUMBNAIL])
                    ->save($logo_dir . $baseName . '.png', 'png');
                    // Now merge the new logo file onto the transparent bordered background
                    Image::open($logo_dir . 'carrier-logos-bg-sm.png')
                        ->merge(Image::open($logo_dir . $baseName . '.png'))
                        ->save($logo_dir . $baseName . '.png', 'png');
                    // Create the medi (default) img file
                    $baseName = $logoFileName . '-md';
                    printVarIfDebug($logo_dir, getenv('gDebug'), 'DIR Name =>');
                    printVarIfDebug($baseName, getenv('gDebug'), 'File Name =>');
                    Image::open($logo_file)
                     ->resize(LOGO_WIDTH[LOGO_MED], LOGO_HEIGHT[LOGO_MED])
                    ->save($logo_dir . $baseName . '.png', 'png');
                    // Set the new logo file to this default medium logo.
                    $update['logo_file'] = $baseName . '.png';
                    // Now merge the new logo file onto the transparent bordered background
                    Image::open($logo_dir . 'carrier-logos-bg-md.png')
                        ->merge(Image::open($logo_dir . $baseName . '.png'))
                        ->save($logo_dir . $baseName . '.png', 'png');
                    // Create the large img file
                    $baseName = $logoFileName . '-lg';
                    printVarIfDebug($logo_dir, getenv('gDebug'), 'DIR Name =>');
                    printVarIfDebug($baseName, getenv('gDebug'), 'File Name =>');
                    Image::open($logo_file)
                     ->resize(LOGO_WIDTH[LOGO_LG], LOGO_HEIGHT[LOGO_LG], 'transparent')
                    ->save($logo_dir . $baseName . '.png', 'png');
                    printVarIfDebug($logo_file, getenv('gDebug'), 'File to Delete =>');
                    // Now merge the new logo file onto the transparent bordered background
                    Image::open($logo_dir . 'carrier-logos-bg-lg.png')
                        ->merge(Image::open($logo_dir . $baseName . '.png'))
                        ->save($logo_dir . $baseName . '.png', 'png');
                    // Delete the originally uploaded file
                    unlink($logo_file);
                }
            }            
       }
       // Grab all the $_POST vars to update the carrier with
       $update['id'] = $cid;
       $update['name'] = filter_input(INPUT_POST, 'carrier_name', FILTER_SANITIZE_STRING);
       $update['contact'] = filter_input(INPUT_POST, 'contact', FILTER_SANITIZE_STRING);
       $update['address_1'] = filter_input(INPUT_POST, 'address_1', FILTER_SANITIZE_STRING);
       $update['address_2'] = filter_input(INPUT_POST, 'address_2', FILTER_SANITIZE_STRING);
       $update['city'] = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_STRING);
       $update['state'] = filter_input(INPUT_POST, 'state', FILTER_SANITIZE_STRING);
       $update['zip'] = filter_input(INPUT_POST, 'zip', FILTER_SANITIZE_NUMBER_INT);
       $update['phone'] = preg_replace('/[^0-9()-]/', '', filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING));
       $update['toll_free'] = preg_replace('/[^0-9()-]/', '', filter_input(INPUT_POST, 'toll_free', FILTER_SANITIZE_STRING));
       $update['fax'] = preg_replace('/[^0-9()-]/', '', filter_input(INPUT_POST, 'fax', FILTER_SANITIZE_STRING));
       $update['contact_email'] = filter_input(INPUT_POST, 'contact_email', FILTER_SANITIZE_EMAIL);
       $update['lead_email'] = filter_input(INPUT_POST, 'lead_email', FILTER_SANITIZE_EMAIL);
       $update['pitch'] = filter_input(INPUT_POST, 'pitch', FILTER_SANITIZE_STRING);
       $update['site_url'] = filter_input(INPUT_POST, 'site_url', FILTER_SANITIZE_URL);
       
       printVarIfDebug($update, getenv('gDebug'), 'Update Array');
       if (!$cid == 0) {
           updateCarrier($update);           
       } else {
           $cid = insertCarrier($update);
       }
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
    
    <?php $thisCarrier = getCarrier($cid); ?>
    
    <div class="col-md-12">
        <div id="admin-header">
            <h3><?php echo PROJECT_TITLE_SHORT; ?> Carrier Management</h3>
            <p class="admin"><strong>[ Welcome <?php echo $_SESSION[SESSION_NAME]['user']['name']; ?> - <a href="<?php echo HOME_LINK; ?>login.php?confirm=logout">Logout</a> ]</strong></p>
        </div>
    </div>
    <!-- Rate Summary -->
    <div class="col-md-10 col-md-offset-1 admin-box">
        <h3><?php 
            if ($cid == 0) {
                $thisCName = 'Add New Carrier';
            } else {
                $thisCName = $thisCarrier['name'];
            }
            echo $thisCName; ?></h3>
        <div>
        <form class="form-horizontal" method="post" action="<?php echo $root; ?>admin/edit-carrier.php?cid=<?php echo $cid; ?>" enctype="multipart/form-data">
            <input type="hidden" name="__confirm__save" value="true" />
            <input type="hidden" name="current_logo_file" value="<?php echo $thisCarrier['logo_file']; ?>" />
            <table class="rates-mg" id="admin-box">
                <tr>
                    <th colspan="6">Carrier Name</th>
                </tr>
                <tr>
                    <td>Name</td>
                    <td colspan="5"><input type="text" name="carrier_name" placeholder="Carrier Name" value="<?php echo $thisCarrier['name']; ?>" <?php if($cid > 0) { echo 'disabled'; } ?> /></td>
                </tr>
                <tr>
                    <th colspan="6">Contact Information</th>
                </tr>
                <tr>
                    <td>Name</td>
                    <td colspan="5"><input type="text" name="contact" placeholder="Carrier Contact Name" value="<?php echo $thisCarrier['contact']; ?>" /></td>
                </tr>
                <tr>
                    <th colspan="6">Address Information</th>
                </tr>
                <tr>
                    <td colspan="3"><input type="text" name="address_1" placeholder="Address 1" value="<?php echo $thisCarrier['address_1']; ?>" /></td>
                    <td colspan="3"><input type="text" name="address_2" placeholder="Address 2" value="<?php echo $thisCarrier['address_2']; ?>" /></td>
                </tr>
                <tr>
                    <td colspan="2"><input type="text" name="city" placeholder="City" value="<?php echo $thisCarrier['city']; ?>" /></td>
                    <td colspan="2">
                        <select name="state" placeholder="State Abbr" value="<?php echo $thisCarrier['state']; ?>" >
                            <?php 
                            foreach (STATES as $key => $value) {
                                $selected = ($thisCarrier['state'] == $key) ? 'selected' : '';
                                echo '<option value="' . $key . '" ' . $selected . '>' . $value . '</option>';
                            }
                            ?>
                        </select>
                    </td>
                    <td colspan="2"><input type="text" name="zip" placeholder="Zip Code" pattern="[0-9]{5}" maxlength="5" value="<?php echo $thisCarrier['zip']; ?>" /></td>
                </tr>
                <tr>
                    <th colspan="6">Telephone Numbers</th>
                </tr>
                <tr>
                    <td>Main:</td>
                    <td><input type="text" name="phone" maxlength="14" pattern="[0-9()- " placeholder="(555) 555-5555" value="<?php echo $thisCarrier['phone']; ?>" /></td>
                    <td>Toll-Free:</td>
                    <td><input type="text" name="toll_free" maxlength="14" placeholder="(800) 555-1212" value="<?php echo $thisCarrier['toll_free']; ?>" /></td>
                    <td>Fax:</td>
                    <td><input type="text" name="fax" maxlength="14" placeholder="(123) 456-7890" value="<?php echo $thisCarrier['fax']; ?>" /></td>
                </tr>
                <tr>
                    <th colspan="6">Email Addresses</th>
                </tr>
                <tr>
                    <td colspan="3">Contact: <input type="email" name="contact_email" placeholder="Contact Email" value="<?php echo $thisCarrier['contact_email']; ?>" /></td>
                    <td colspan="3">Leads: <input type="email" name="lead_email" placeholder="Leads Email" value="<?php echo $thisCarrier['lead_email']; ?>" /></td>
                </tr>
                <tr>
                    <th colspan="3">Ad Line</th>
                    <th colspan="3">Logo:</th>                    
                    
                </tr>
                <tr>
                    <td colspan="3"><textarea name="pitch" rows="4"><?php echo $thisCarrier['pitch']; ?></textarea></td>
                    <td colspan="1"><input type="file" accept="image/*" name="logo_file"  id="logo_file" value="<?php echo $thisCarrier['logo_file']; ?>" /></td>
                    <td colspan="2"><img src="<?php echo $root . 'images/carrier-logos/' . $thisCarrier['logo_file']; ?>" width="200" height="108" /></td>
                </tr>
                <tr>
                    <th colspan="1">Web Address</th>
                    <th colspan="5"><input type="url" name="site_url" placeholder="http://mycompany.com" value="<?php echo $thisCarrier['site_url']; ?>" /></th>
                </tr>
                <tr>
                    <td colspan="4">Sometimes you feel like a nut.  Sometimes you don't.</td>
                    <td><a href="<?php echo $root . 'admin/manage-carriers.php'; ?>">[ Return ]</a>&nbsp;&nbsp;<a href="<?php echo $root . 'admin/manage-attributes.php?cid=' . $cid; ?>">[ Carrier Attributes ]</a></td>
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
