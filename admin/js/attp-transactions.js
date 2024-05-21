(function( $ ) {
	'use strict';

	var page = 1;
	var $loadMoreBtn = $('#load-more'); // Cache the button for easier and repeated access


	window.handleChange = function(selector) {
		var value = selector.value;
		var currentUrl = new URL(window.location.href);
		currentUrl.searchParams.set('count', value); // Set or update the 'count' parameter
		currentUrl.searchParams.set('_wpnonce', attp_ajax_object.count_nonce);

		window.location.href = currentUrl.toString(); // Reloads the page with updated URL
	}

	// Function to handle the loading of more transactions
	function loadMoreTransactions() {
		$loadMoreBtn.text('Loading...').prop('disabled', true); // Change button text and disable it

		$.ajax({
			url: attp_ajax_object.ajax_url,
			type: 'POST',
			data: {
				action: 'load_more_transactions',
				page: page,
				count: document.getElementById('tx-per-page').value,
				security: attp_ajax_object.nonce
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
					alert(JSON.stringify(response.data)); // Alert if no more data or error message from server
				}
				$loadMoreBtn.text('Load More').prop('disabled', false); // Reset button text and re-enable
			},
			error: function(response) {
				alert('Failed to parse transaction data. ' + response.data.message);
				$loadMoreBtn.text('Load More').prop('disabled', false); // Reset button text and re-enable even if error occurs
			}
		});
	}

	// Bind the click event to the load more function
	$loadMoreBtn.on('click', loadMoreTransactions);

	// Automatically load the first page of transactions when the page loads
	loadMoreTransactions();
})( jQuery );
