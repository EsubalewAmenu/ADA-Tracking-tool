<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="wrap">
    <h2>ADA Tracking Settings</h2>

    <?php settings_errors(); ?>
    <form method="post" action="options.php">
        <?php settings_fields('ada_tracking_settings_group'); ?>
        <?php do_settings_sections('ada-tracking-plugin'); ?>
        <?php submit_button('Save Settings'); ?>
    </form>
</div>