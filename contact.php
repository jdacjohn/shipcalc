<?php
    $root = './';
    require('_includes/app_start.inc.php');
    global $headerFilter;
    $headerNo = random_int(1, HEADER_IMAGES);
    while (array_search($headerNo, $headerFilter)) {
        $headerNo = random_int(1, HEADER_IMAGES);        
    }
    
    $text ='';
    if(isset($_POST['submitcontact'])) {
        printVarIfDebug($text, getenv('gDebug'), 'The contact form has been submitted');
        
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
        $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);

        if (isset($name) && isset($email) && isset($message)) {
            
            $to = EMAIL_CONTACT;
            $subject = "ShipCalc Contact Form Information Request";
            $message = " Name: " . $name ."\r\n Email: " . $email . "\r\n Message:\r\n" . $message;

            $from = PROJECT_TITLE_SHORT;
            $headers = "From:" . $from . "\r\n";
            $headers .= "Content-type: text/plain; charset=UTF-8" . "\r\n"; 

            if(@mail($to,$subject,$message,$headers)) {
                $text = "<span style='contact-success'>Your Message was sent successfully!</span>";
            }     else {
                $text = "<span class='contact-error'>Email could not be sent.  Please try again or <a href='mailto:" . EMAIL_ADMIN . "'>contact the site admin</a>.</span>";
            }                  
        } else {
            $text = "<span style='contact-error'>Please ensure you entered a valid email address.</span>";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <?php include($root . 'includes/page-head-meta.php'); ?>
    <title><?php echo PROJECT_TITLE_SHORT . ' - '; ?>Contact Us</title>
    <!-- Load site CSS Files -->
    <?php include($root . 'includes/page-styles.php'); ?>
    <!-- Load page top Java Script assets -->
    <?php include($root . 'includes/page-head-scripts.php'); ?>
</head>
<body>	

    <header>
        <?php include($root . 'includes/nav-menu.php'); ?>
    </header>
    <!-- Header -->
    <a id='backTop'>Back To Top</a>
    <div class="container-fluid carousel-form" style="background: url(<?php echo $root; ?>images/headers/header-<?php echo $headerNo; ?>.jpg) no-repeat fixed top center #ff7f00;">
        <div class="col-md-6 hidden-sm hidden-xs">
            <?php include($root . 'includes/page-head-carousel.php'); ?>
        </div>
    </div>

    <!-- /////////////////////////////////////////Content -->
    <div id="page-content" class="archive-page">
	
        <section class="box-content box-bg-white">
            <div class="box-form">
                <div class="heading">
                    <h2>Contact Us</h2>
                </div>
                <!--Warning-->
                <?php 
                    if (!($text === '')) {
                        echo $text ;
                    }
                ?>
                <!---->
                <form name="contactForm" method="post" action="<?php echo $root; ?>contact.php" id="ff">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" class="form-control input-lg" name="name" id="name" placeholder="Your Name" required="required" />
                            </div>
                            <div class="form-group">
                                <input type="email" class="form-control input-lg" name="email" id="email" placeholder="Your Email" required="required" />
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control input-lg" name="phone" id="phone" placeholder="Your Phone Number" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <textarea name="message" id="message" class="form-control" rows="4" cols="25" required="required" placeholder="Message"></textarea>
                            </div>	
                        </div>
                    </div>
                    <button type="submit" class="btn btn-2 btn-sm" name="submitcontact">send</button>
                </form>
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
	
