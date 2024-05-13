<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="wrap">
    <h2>Transaction Checker Cron Schedule Settings</h2>
    <form method="post" action="options.php">
        <?php
        settings_fields('attp-cron-options');
        do_settings_sections('attp-cron-schedule');
        submit_button('Save Settings');
        ?>
    </form>
    <form method="post" action="">
        <?php wp_nonce_field('attp_cron_actions', 'attp_cron_nonce');

        $next_scheduled = wp_next_scheduled("attp_cron_hook");
        if (empty($next_scheduled)) {
        ?>
            <input type="submit" name="attp_start_cron" value="Start Cron Job" class="button button-primary">
        <?php } else { ?>
            <input type="submit" name="attp_stop_cron" value="Stop Cron Job" class="button button-secondary">
        <?php } ?>
    </form>
</div>