<div class="container">
    <form class="form" action="" method="post">

        <div>
            <label class="form-label" for="ada_address">Paste your address here...</label>
            <input class="form-input" type="ada_address" name="ada_address" id="ada_address" placeholder='The address should start with "addr"' required>

            <button id="fetch_btn" class="fetch-button" type="button" >Show latest transactions</button>
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
        </div>

    </form>
</div>

<script>
    var ajaxurl = "<?php echo esc_attr(admin_url('admin-ajax.php')) ?>";
    const fetch_btn = document.querySelector('#fetch_btn');
    const fetchedHistoryData = document.querySelector('.fetched-history-data');
    var ada_address = '';

    function fetchTransactionHistory() {
        ada_address = document.querySelector('#ada_address').value;
        if (validateInputs()) {
        jQuery.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'load_transaction_history',
                    ada_address:ada_address,
                    page:"1",
                    security: '<?php echo esc_attr(wp_create_nonce("load_transaction_history_nonce")); ?>'
    
                },
                success: function(response) {
                    // console.log("request response is ");
                    // console.log(response);
                    
                    const res = JSON.parse(response)

                    if (res["code"] == 404) {
                        var errorMessageElement = document.querySelector('#error_message');
                        if (!errorMessageElement) {
                            errorMessageElement = document.createElement('div');
                            errorMessageElement.id = 'error_message';
                            errorMessageElement.style.color = 'red';
                            errorMessageElement.style.marginTop = '5px';
                            document.querySelector('#ada_address').parentNode.appendChild(errorMessageElement);
                        }
                        errorMessageElement.textContent = 'ADA address not found';
                        fetchedHistoryData.style.display = 'none';
                    }else if (res["code"] == 200) {
                        const tableBody = document.querySelector('#transaction-table-body');
                        var tokenList;

                        // Clear existing rows
                        tableBody.innerHTML = '';

                        // Add new rows
                        res['rows'].forEach(item => {
                            const newRow = document.createElement('tr');

                            if(item.token == 0)
                                tokenList = "ADA";
                            else{
                                tokenList = "ADA ";
                                // item.tokens
                                item.tokens['rows'].forEach(eachTokens => {
                                    tokenList += "</br>"+formatMoney(eachTokens.quantity, eachTokens.decimals) + " " +eachTokens.ticker + "";
                                });
                            }


                            newRow.innerHTML = `
                                <td>${formatMoney(item.amount, 6)} ${tokenList}</td>
                                <td>${item.tx_hash.substring(0, 15)}...</td>
                                <td>${formatDate(item.time)}</td>
                            `;
                            tableBody.appendChild(newRow);
                        });
                        fetchedHistoryData.style.display = 'block';
                    }

                }
            });
        }

    }
    function formatMoney(amount, decimalpoint) {
    // Find index of the decimal point
    let decimalIndex = amount.length - decimalpoint;
    // Insert comma and decimal point
    let formattedAmount = amount.slice(0, decimalIndex).replace(/\B(?=(\d{3})+(?!\d))/g, ",") + "." + amount.slice(decimalIndex).replace(/0+$/, "");
    return formattedAmount;
    }

    fetch_btn.addEventListener('click', fetchTransactionHistory);
    
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

    // Function to convert timestamp to human-readable date
    function formatDate(timestamp) {
        const date = new Date(timestamp * 1000);
        return date.toLocaleString(); // Adjust format as needed
    }
</script>