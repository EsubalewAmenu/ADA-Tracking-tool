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
<script>


function handleChange(selector) {
    var value = selector.value;
    var currentUrl = new URL(window.location.href);
    currentUrl.searchParams.set('count', value); // Set or update the 'count' parameter
    window.location.href = currentUrl.toString(); // Reloads the page with updated URL
}
	jQuery(document).ready(function($) {
		var page = 1;
		var $loadMoreBtn = $('#load-more'); // Cache the button for easier and repeated access



		// Function to handle the loading of more transactions
		function loadMoreTransactions() {
			$loadMoreBtn.text('Loading...').prop('disabled', true); // Change button text and disable it

			$.ajax({
				url: `<?php echo admin_url('admin-ajax.php') ?>`,
				type: 'POST',
				data: {
					action: 'load_more_transactions',
					page: page,
					count: document.getElementById('tx-per-page').value,
					security: '<?php echo esc_attr(wp_create_nonce("load_more_transactions")); ?>'
				},
				success: function(response) {
					if (response.success) {
						// Check if 'No items found' row exists and remove it
						if ($('#the-list .no-items').length) {
							$('#the-list .no-items').remove();
						}
						$('#the-list').append(response.data.rows); // Append new rows
						page++; // Only increment the page if the load was successful
					} else {
						alert(response.data.message); // Alert if no more data or error message from server
					}
					$loadMoreBtn.text('Load More').prop('disabled', false); // Reset button text and re-enable
				},
				error: function() {
					alert('Error loading more transactions.');
					$loadMoreBtn.text('Load More').prop('disabled', false); // Reset button text and re-enable even if error occurs
				}
			});
		}

		// Bind the click event to the load more function
		$loadMoreBtn.on('click', loadMoreTransactions);

		// Automatically load the first page of transactions when the page loads
		loadMoreTransactions();
	});
</script>