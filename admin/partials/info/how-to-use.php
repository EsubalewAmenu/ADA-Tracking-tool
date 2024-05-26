<h1>How to Use</h1>

<div>
    <h2>Configuration</h2>
    <h3>Setting up Cardano Integration</h3>
    <p>Follow these steps to configure the plugin:</p>
    <ol>
        <li>Navigate to the plugin settings page in your WordPress admin panel.</li>
        <li>Enter your Blockfrost API access details.</li>
        <li>Customize the plugin settings according to your preferences.</li>
        <li>Save your changes.</li>
    </ol>

    <h3>Customization Options</h3>
    <p>Customize the plugin to fit your site's design and requirements:</p>
    <ul>
        <li>Choose the display options for transaction tracking and asset details.</li>
        <li>Customize the colors and styles to match your site's design.</li>
        <li>Enable or disable specific features based on your requirements.</li>
    </ul>
</div>

<div>
    <h2>Usage</h2>
    <h3>Real-time Tracking</h3>
    <p>Monitor Cardano transactions in real-time directly from your WordPress dashboard. View transaction details, including sender, receiver, amount, and timestamp.</p>

    <h3>Asset Details</h3>
    <p>Access detailed information about Cardano assets, including token names, supply, and transaction details.</p>

    <h3>Transaction Management</h3>
    <p>Manage Cardano transactions effortlessly, with options for filtering, searching, and exporting transaction data.</p>
</div>

<div>
    <h2>Shortcode Documentation</h2>
    <h3>Displaying Recent Transactions</h3>
    <p>The plugin provides a shortcode that allows you to display recent Cardano transactions on any page. Use the shortcode <code>[attp_transaction_history_code]</code> to enable users to view their transaction history by entering their ADA address.</p>

    <h4>Steps to Use the Shortcode:</h4>
    <ol>
        <li>Edit the page or post where you want to display the transaction history.</li>
        <li>Insert the shortcode <code>[attp_transaction_history_code]</code> at the desired location within the content.</li>
        <li>Save and publish the page or post.</li>
    </ol>

    <h4>Example:</h4>
    <p>To display recent transactions on a page, add the following shortcode:</p>
    <pre><code>[attp_transaction_history_code]</code></pre>
    <p>When users visit this page, they will see an input field where they can paste their ADA address (starting with "addr1") and click on the "Show Latest Transactions" button to fetch their transaction history.</p>

    <h3>Shortcode Options</h3>
    <p>Customize the shortcode with the following options:</p>
    <ul>
        <li><code>per_page</code>: Sets the number of transactions to display per page.</li>
        <li><code>button_text</code>: Customizes the text on the "Show Latest Transactions" button.</li>
        <li><code>load_more_text</code>: Customizes the text on the "Load More" button.</li>
    </ul>

    <h4>Complete Example:</h4>
    <pre><code>[attp_transaction_history_code per_page="15" button_text="Get Transactions" load_more_text="See More"]</code></pre>
    <p>This shortcode will display 15 transactions per page, with customized button texts.</p>
</div>

<div>
    <h2>Support and Feedback</h2>
    <h3>Getting Help</h3>
    <p>If you encounter any issues or have questions about the plugin, please reach out to our support team for assistance. You can contact us via email or through our support forum.</p>

    <h3>Providing Feedback</h3>
    <p>We value your feedback and suggestions for improving the plugin. Please share your thoughts and experiences with us to help us enhance the plugin's functionality and usability.</p>
</div>