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
    <title><?php echo PROJECT_TITLE_SHORT . ' - '; ?>About Us</title>
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
        <div class="col-md-6 hidden-sm hidden-xs">
            <?php include($root . 'includes/page-head-carousel.php'); ?>
        </div>
    </div>
	
    <!-- /////////////////////////////////////////Content -->
    <div id="page-content" class="archive-page">
	
        <!-- ////////////Content Box -->
        <section class="box-content box-bg-white">
            <div class="box-post">
                <div class="heading">
                    <h2>About <?php echo PROJECT_TITLE_SHORT; ?></h2>
                    <div class="info">By <a href="#">Danny</a> on April 14, 2015</div>
                </div>
                <img src="<?php echo $root; ?>images/15.jpg" alt="">
                <div class="excerpt">
                    <p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum exercitation ullamco laboris nisi ut aliquip.</p></div>
                    <p>
                        Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat,
                        sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Consetetur sadipscing elitr, sed diam nonumy eirmod tempor 
                        invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum.
                    </p>
                    <blockquote><p>Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet vultatup duista.</p></blockquote>
                    <p>
                        Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis
                        at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril.
                    </p>
                    <h3>Heading 1</h3>
                    <p>
                        Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. 
                        Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse lorem ipsum dolor sit amet.
                    </p>
                    <h3>Heading 2</h3>
                    <p>Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis. 
                        At vero eos et accusam et justo.
                    </p>
                    <h3>Heading 3</h3>
                    <p>
                        Consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. 
                        At vero eos et accusam et justo duo dolores et ea rebum hendrerit in vulputate velit esse molestie.
                    </p>
                    <p>
                        Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, 
                        sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum.
                    </p>
                    <p>
                        Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, 
                        sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.
                    </p>
                    <div class="note">
                        <ol>
                            <li>Lorem ipsum</li>
                            <li>Sit amet vultatup nonumy</li>
                            <li>Duista sed diam</li>
                        </ol>
                        <div class="clear"></div>
                    </div>
                    <p>
                        Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at 
                        vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi. 
                        Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.
                    </p>
                    <p>
                        Consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores.
                    </p>
            </div>
        </section>

    </div>

    <footer>
            <?php include($root . 'includes/footer.php'); ?>
            <?php include($root . 'includes/footer-tagline.php'); ?>
    </footer>
    <!-- Footer -->
	
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
