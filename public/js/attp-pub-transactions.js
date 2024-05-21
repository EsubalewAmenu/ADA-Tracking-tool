(function ($) {
    'use strict';


    $(document).ready(function () {

        const fetchedHistoryData = document.querySelector('.fetched-history-data');
        var ada_address = '';
        var page = 1;
        const tableBody = document.querySelector('#transaction-table-body');

        var fetch_btn = $('#fetch_btn');

        if (fetch_btn.length) {

            fetch_btn.on('click', function () {
                ada_address = $('#ada_address').val();

                tableBody.innerHTML = '';

                if (validateInputs()) { // Validate the input before fetching the transaction history
                    page = 1;
                    fetchTransactionHistory(); // Only call this function if the input is valid
                }

            });
        }

        window.handleChange = function (selector) {
            var value = selector.value;
            var currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('count', value); // Set or update the 'count' parameter
            currentUrl.searchParams.set('_wpnonce', attp_ajax_object.count_nonce);
            currentUrl.searchParams.set('address', ada_address);

            
            window.location.href = currentUrl.toString(); // Reloads the page with updated URL
        }



        var $loadMoreBtn = $('#load-more'); // Cache the button for easier and repeated access

        function fetchTransactionHistory() {

            $loadMoreBtn.text('Loading...').attr('disabled', true); // Change button text and disable it
            fetch_btn.text('Loading...').attr('disabled', true); // Change button text and disable it
            jQuery.ajax({
                url: attp_ajax_object.ajax_url,
                type: 'POST',
                data: {
                    action: 'load_transaction_history',
                    page: page,
                    ada_address: ada_address,
                    count: document.getElementById('tx-per-page').value,
                    security: attp_ajax_object.nonce //'<?php echo esc_attr(wp_create_nonce("load_transaction_history_nonce")); ?>'
                },
                success: function (response) {
                    try {

                        const res = JSON.parse(response)
                        res.forEach(item => {
                            const newRow = document.createElement('tr');


                            newRow.innerHTML = `
                            <td>${item.amount}</td>
                            <td>${item.tx_hash.substring(0, 15)}...</td>
                            <td>${item.time}</td>
                        `;
                            tableBody.appendChild(newRow);
                        });
                        fetchedHistoryData.style.display = 'block';
                        page++; // Only increment the page if the load was successful
                    } catch (e) {
                        // Handle errors in parsing JSON
                        alert('Failed to parse transaction data. ' + response);
                    }
                    $loadMoreBtn.text('Load More').attr('disabled', false); // Reset button text and re-enable
                fetch_btn.text('Show latest transactions').attr('disabled', false); // Reset button text and re-enable

                },
                error: function () {
                    alert('Error loading more transactions.');
                    $loadMoreBtn.text('Load More').attr('disabled', false); // Reset button text and re-enable
                    fetch_btn.text('Show latest transactions').attr('disabled', false); // Reset button text and re-enable
                }
            });



        }


        // Bind the click event to the load more function
        $loadMoreBtn.on('click', function () {
            fetchTransactionHistory();
        });


        function validateInputs() {
            var errorMessageElement = document.querySelector('#error_message');

            // Check if the value starts with "addr1"
            if (!ada_address.startsWith('addr1')) {
                // If the value doesn't start with "addr1", create and show an error message
                if (!errorMessageElement) {
                    errorMessageElement = document.createElement('div');
                    errorMessageElement.id = 'error_message';
                    errorMessageElement.style.color = 'red';
                    errorMessageElement.style.marginTop = '5px';
                    document.querySelector('#ada_address').parentNode.appendChild(errorMessageElement);
                }
                errorMessageElement.textContent = 'ADA address must start with "addr1"';
                fetchedHistoryData.style.display = 'none';
                return false; // Return false to indicate validation failure
            }

            // If the value starts with "addr1", clear any previous error message and return true
            if (errorMessageElement) {
                errorMessageElement.textContent = '';
            }
            return true; // Return true to indicate validation success
        }

    });


})(jQuery);
