<?php 
    
    if ( 0 == error_reporting () ) {
        // Error reporting is currently turned off or suppressed with @
        return;
    }
    
    if (isset($GLOBALS['arrErr']) && count($GLOBALS['arrErr']) > 0) { 
        //echo "Here there be errors!";
?>
<div id="error-handler">
    <strong>The following errors were received:</strong>
    <ul style="padding: 10px; line-height: 1.1;">
        <?php foreach ($GLOBALS['arrErr'] as $strErr) { ?>
            <li><?php echo $strErr; ?></li>
        <?php } ?>
    </ul>
</div>
<?php }
