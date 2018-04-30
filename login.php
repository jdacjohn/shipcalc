<?php
   $root = './';
    require($root . '_includes/app_start.inc.php');
    $headerNo = random_int(1, HEADER_IMAGES);
    $gDebug = getenv('gDebug');
    //define variables for form data
    $arrVars = array(
        //array(THE_VALUE, THE_TYPE, THE_DEFINED_VALUE, THE_NOT_DEFINED_VALUE, FIELD_IS_ARRAY, ALLOW_NULL_VALUE)
        newFieldArray('username'),
        newFieldArray('password'),
        newFieldArray('__login__formSubmit')
    );

    //if (getenv('gDebug')) {
    //    printvar($_GET, ' GET');
    //    printvar($_POST, ' POST');
    //    printvar($_REQUEST, 'REQUEST');
    //}

    //initialize values
    if (!isset($arrVals)) { $arrVals = array(); }
    foreach ($arrVars as $var) {
        $arrVals[$var[THE_VALUE]] = getParam($var[THE_VALUE], (bool) $var[FIELD_IS_ARRAY]);
    }

    //if($gDebug) { printvar($arrVals, 'arrVals'); }

    $url = false;
    if (isset($_REQUEST[POSTBACK_PARAMETER_PREFIX.'return']) && strlen(trim($_REQUEST[POSTBACK_PARAMETER_PREFIX.'return']))) {
        $url = $_REQUEST[POSTBACK_PARAMETER_PREFIX.'return'];
        //printVarIfDebug($url, getenv('gDebug'), 'setting URL to value of $_REQUEST[POSTBACK_PARAMETER_PREFIX.\'return\']');
    } elseif (isset($_REQUEST['return']) && strlen(trim($_REQUEST['return']))) {
        $url = $_REQUEST['return'];
        //if($gDebug) { printvar($url, 'setting URL to value of $_REQUEST[\'return\']'); }
    } elseif ($arrVals['__login__formSubmit'] != 'true' && isset($_SERVER['HTTP_REFERER']) && strlen(trim($_SERVER['HTTP_REFERER']))) {
        $url = $_SERVER['HTTP_REFERER'];
        //if($gDebug) { printvar($url, 'setting URL to value of $_SERVER[\'HTTP_REFERER\']'); }
    }
    //exclusion list:
    if (!$url || strpos($url, $_SERVER['PHP_SELF']) !== false || strpos($url, '/signup/') !== false ) {
        $url = false;
        //if($gDebug) { printvar('setting URL to false'); }
    }

    if (!isset($arrErr)) { $arrErr = array(); }
    
    if (getParam('confirm') == 'logout' && (is_logged_in())) {
        //if($gDebug) { printvar($_SESSION[SESSION_NAME], 'Session before logout block'); }
        reset_session();
        //if($gDebug) { printvar($_SESSION[SESSION_NAME], 'Session after logout block'); }
    } elseif (getParam('__login__formSubmit') == 'true') {
        //the form was submitted
        //check req'd data
        if (strlen(trim($arrVals['username'])) == 0) {
            $arrErr[] = form_error('Please enter your username.', 'username');
        }
        if (strlen(trim($arrVals['password'])) == 0) {
            $arrErr[] = form_error('Please enter your password.', 'password');
        }

        if (sizeof($arrErr) == 0) {
            //no errors returned - Check login
            $login_check = init_user(trim($arrVals['username']), trim($arrVals['password']));
            switch ($login_check) {
                case LOGIN_OK:
          //          printVarIfDebug($_SESSION, getenv('gDebug'), 'Session before redirect');
                    $dataArray = array_clean(array_clean($_POST, POSTBACK_PARAMETER_PREFIX), '__login__');
                    if ($gDebug) { 
                        $dataArray['debug'] = 1; 
                    }
                    if ($url && isset($_REQUEST[POSTBACK_PARAMETER_PREFIX.'return_method']) && $_REQUEST[POSTBACK_PARAMETER_PREFIX.'return_method'] == 'post') {
          //              printVarIfDebug($dataArray, getenv('gDebug'), 'posting to '.$url);
                        redirectByForm($url, $dataArray, (!$gDebug));
                    } else {
                        if (!$url) { 
                            $url = SITE_ROOT.'/'; 
                        }
          //              if($gDebug) { printvar($url, 'redirecting to '); }
                        $dataArray = array(
                            'confirm' => 'login',
                            'return' => $url
                        );
                        if ($gDebug) { $dataArray['debug'] = 1; }
                        redirect($_SERVER['PHP_SELF'],$dataArray,$gDebug);
                        exit();
                    }
                    break;
                case LOGIN_BAD_USERNAME:
                case LOGIN_BAD_PASSWORD:
                case LOGIN_BAD_ACCOUNT:
                    $err = form_error('The specified username or ', 'username').form_error('password is invalid. Please try again.', 'password');
                    printvar($err, 'Login Error');
                    $arrErr[] = $err;
                    reset_session();
                    break;
                case LOGIN_BAD_QUERY:
                default:
                    $arrErr[] = 'An error was encountered while trying to log in. Please try again. If the problem persists please <a href="'.SITE_ROOT.'/contact/">let us know</a>.';
                    break;
            }
        }
    //    if($gDebug) { printvar($arrErr, 'arrErr'); }
    } else {
        //overrides for any default values
        //n/a
    } //END: if (getParam('formSubmit') == 'true')
    
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <!-- load site meta information -->
    <?php include($root . 'includes/page-head-meta.php'); ?>)
    <title><?php echo PROJECT_TITLE_SHORT; ?> - Login</title>
    <!-- Load site/page CSS files -->
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
        <div class="col-lg-4 col-md-6 estimate calc-form">
        <?php if (getParam('confirm') == "login" && is_logged_in()) { ?>
                <h3>Welcome to <?php echo PROJECT_TITLE_SHORT; ?></h3>
                <span>You are now logged in. <a href="<?php echo htmlentities($url); ?>">Continue</a>.</span>
                <br />&nbsp;<br />
        <?php } elseif (getParam('confirm') == "" && is_logged_in()) { ?>
                <h3>Welcome to <?php echo PROJECT_TITLE_SHORT; ?></h3>
                <span>You are already logged in. <a href="<?php echo htmlentities(SITE_ROOT); ?>/">Return to the home page.</a></span>
                <br />&nbsp;<br />
        <?php } elseif (getParam('confirm') == "logout" && !is_logged_in()) { ?>
                <h3>Goodbye</h3>
                <span>You are now logged out. <a href="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>">Log back in</a>.</span>
                <br />&nbsp;<br />
        <?php } else { ?>
                <h3>Please log in to access this area of the site</h3>
                <br />&nbsp;<br />
            <?php include($root . '_includes/default_error_handler.php'); ?>
                <form name="form_login" method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" id="form_login">
                    <input type="hidden" name="__login__formSubmit" value="true" />
                    <?php
                        $dataArray = array_merge($_GET, $_POST, array('debug'=>$gDebug));
                        $dataArray[POSTBACK_PARAMETER_PREFIX.'return'] = $url;
                        writeHiddenFormFields($dataArray,'__login__');
                    ?>
                    <input type="text" class="col-md-6 input-lg" style="color: #FFF;" name="username" id="username" placeholder="Username" required="required" />
                    <input type="password" class="control input-lg"  style="color: #FFF;" name="password" id="password" placeholder="Password" required="required" />
                    <button type="submit" class="btn btn-2 btn-sm" name="submit">login</button><br />
                </form>
            <?php } ?>  
        </div>
    </div>

    <div id="page-content" class="archive-page">
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