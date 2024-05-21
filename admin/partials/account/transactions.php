<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="wrap">
	<h1>My Transactions </h1>
	<div id="col-container">
		<div class="col-wrap">
			<?php $receiving_addresses_table->display(); ?>
		</div>

		<select id="tx-per-page" onchange="handleChange(this)">
			<option value="1" <?php if ($attp_tx_per_page == 1) echo 'selected'; ?>>1</option>
			<option value="5" <?php if ($attp_tx_per_page == 5) echo 'selected'; ?>>5</option>
			<option value="10" <?php if ($attp_tx_per_page == 10) echo 'selected'; ?>>10</option>
			<option value="25" <?php if ($attp_tx_per_page == 25) echo 'selected'; ?>>25</option>
			<option value="50" <?php if ($attp_tx_per_page == 50) echo 'selected'; ?>>50</option>
			<option value="100" <?php if ($attp_tx_per_page == 100) echo 'selected'; ?>>100</option>
		</select>
		<button id="load-more">Load More</button>
	</div>

</div>
