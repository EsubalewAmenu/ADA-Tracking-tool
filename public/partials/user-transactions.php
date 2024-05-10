<div class="container">
    <form class="form" action="" method="post">

        <div>
            <label class="form-label" for="ada_address">Paste your address here...</label>
            <input class="form-input" type="ada_address" name="ada_address" id="ada_address" placeholder='The address should start with "addr"' required>

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

<script>
    var ajaxurl = "<?php echo esc_attr(admin_url('admin-ajax.php')) ?>";
    const fetch_btn = document.querySelector('#fetch_btn');
    const fetchedHistoryData = document.querySelector('.fetched-history-data');
    var ada_address = '';
    var page = 1;
    const tableBody = document.querySelector('#transaction-table-body');

    function handleChange(selector) {
        var value = selector.value;
        var currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('count', value); // Set or update the 'count' parameter
        window.location.href = currentUrl.toString(); // Reloads the page with updated URL
    }

    jQuery(document).ready(function($) {

        var $loadMoreBtn = $('#load-more'); // Cache the button for easier and repeated access

        function fetchTransactionHistory() {
            $loadMoreBtn.text('Loading...').attr('disabled', true); // Change button text and disable it

                jQuery.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'load_transaction_history',
                        page: page,
                        ada_address: ada_address,
                        count: document.getElementById('tx-per-page').value,
                        security: '<?php echo esc_attr(wp_create_nonce("load_transaction_history_nonce")); ?>'
                    },
                    success: function(response) {
                        console.log("response started");
                        console.log(response);
                        console.log("response done");

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
                        //////////////
                        $loadMoreBtn.text('Load More').attr('disabled', false); // Reset button text and re-enable
                    },
                    error: function() {
                        alert('Error loading more transactions.');
                        $loadMoreBtn.text('Load More').attr('disabled', false); // Reset button text and re-enable
                    }
                });

            

        }


        // Bind the click event to the load more function
        $loadMoreBtn.on('click', function() {
            fetchTransactionHistory();
        });


        fetch_btn.addEventListener('click', function() {
            ada_address = document.querySelector('#ada_address').value;

            // Clear existing rows
            tableBody.innerHTML = '';

            if (validateInputs()) { // Validate the input before fetching the transaction history
                fetchTransactionHistory(); // Only call this function if the input is valid
            }
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
</script>