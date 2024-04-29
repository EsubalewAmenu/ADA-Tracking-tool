<div class="wrap">
    <h2>Transaction Checker Cron Schedule Settings</h2>
    <form method="post" action="options.php">
        <?php
        settings_fields('att-cron-options');
        do_settings_sections('att-cron-schedule');
        submit_button('Save Settings');
        ?>
    </form>
    <form method="post" action="">
        <?php wp_nonce_field('att_cron_actions', 'att_cron_nonce');

        $next_scheduled = wp_next_scheduled("att_cron_hook");
        if (empty($next_scheduled)) {
        ?>
            <input type="submit" name="att_start_cron" value="Start Cron Job" class="button button-primary">
        <?php } else { ?>
            <input type="submit" name="att_stop_cron" value="Stop Cron Job" class="button button-secondary">
        <?php } ?>
    </form>
</div>