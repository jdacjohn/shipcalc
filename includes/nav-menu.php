    <!-- Standard Bootstrap nav-bar with 2 child elements - No Form used  -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <!-- Child Element 1 - Standard Bootstrap Navbar header -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Show/Hide Menu</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">
                    <img src="<?php echo $root; ?>images/logo.png" class="hidden-xs" alt="">
                    <h3 class="visible-xs"><?php echo PROJECT_TITLE_SHORT; ?></h3>
                </a>
            </div>
            <!-- Element 2 - nav-bar options -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li><a class="page-scroll" href="<?php echo SITE_ROOT . '/'; ?>index.php">Home</a></li>
                    <li><a class="page-scroll" href="<?php echo SITE_ROOT . '/'; ?>domestic-shipping.php">Domestic Shipping</a></li>
                    <li><a class="page-scroll" href="<?php echo SITE_ROOT . '/'; ?>international-shipping.php">International Shipping</a></li>
                    <li><a class="page-scroll" href="<?php echo SITE_ROOT . '/'; ?>about.php">About</a></li>
                    <li><a class="page-scroll" href="<?php echo SITE_ROOT . '/'; ?>contact.php">Contact Us</a></li>
                    <?php
                        if ( isset($_SESSION[SESSION_NAME]['user']['logged_in']) && $_SESSION[SESSION_NAME]['user']['logged_in'] == true) {
                    ?>
                    <li><a class="page-scroll" href="<?php echo SITE_ROOT . '/'; ?>admin/index.php"><span style="color: #ff7f00">Manage</span></a></li>
                        <?php } ?>
                </ul>
            </div>
            <!-- Search Form would typicall go here -->
        </div>
    </nav>	
