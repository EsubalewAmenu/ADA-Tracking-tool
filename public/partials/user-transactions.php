<?php if (!defined('ABSPATH')) exit; ?>
<div class="container">
    <form class="form" action="" method="post">

        <div>
            <label class="form-label" for="ada_address">Paste your address here...</label>
            <input class="form-input" type="ada_address" name="ada_address" id="ada_address" placeholder='The address should start with "addr"' value="<?php echo isset($address) ? esc_attr(sanitize_text_field($address)) : ''?>" required>

            <button id="fetch_btn" class="fetch-button" type="button">Show latest transactions</button>
        </div>


        <div class="fetched-history-data" style="display: none;">
            <table id="transaction-table">
                <thead>
                    <tr>
                        <th>Amount</th>
                        <th>TX hash</th>
                        <th>TX time</th>
                    </tr>
                </thead>
                <tbody id="transaction-table-body">
                    <!-- Table rows will be dynamically added here -->
                </tbody>
            </table>

            <select id="tx-per-page" onchange="handleChange(this)">
                <option value="1" <?php if ($attp_tx_per_page == 1) echo 'selected'; ?>>1</option>
                <option value="5" <?php if ($attp_tx_per_page == 5) echo 'selected'; ?>>5</option>
                <option value="10" <?php if ($attp_tx_per_page == 10) echo 'selected'; ?>>10</option>
                <option value="25" <?php if ($attp_tx_per_page == 25) echo 'selected'; ?>>25</option>
                <option value="50" <?php if ($attp_tx_per_page == 50) echo 'selected'; ?>>50</option>
                <option value="100" <?php if ($attp_tx_per_page == 100) echo 'selected'; ?>>100</option>
            </select>
            <button id="load-more" type="button">Load More</button>
        </div>

    </form>
</div>
