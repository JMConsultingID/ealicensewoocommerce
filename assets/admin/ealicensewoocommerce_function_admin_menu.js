function fetchMqlAccountDetails(licenseId) {
    // Construct the API URL to fetch MQL account details
    var apiEndpoint = ealicensewoocommerce_params.api_base_endpoint + 'v1/mql-accounts/license/' + licenseId;

    // Make an AJAX request to fetch the MQL account details
    jQuery.ajax({
        url: apiEndpoint,
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + ealicensewoocommerce_params.api_authorization_key
        },
        success: function(response) {
            if (Array.isArray(response) && response.length > 0) {
                // Generate the HTML table if data exists
                var tableContent = `
                    <table class="wp-list-table widefat fixed striped">
                        <thead>
                            <tr>
                                <th>Account MQL</th>
                                <th>Status</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>`;

                response.forEach(function(account) {
                    // Format created_at and updated_at as date only
                    var createdAt = new Date(account.created_at).toLocaleDateString();
                    var updatedAt = new Date(account.updated_at).toLocaleDateString();
                    tableContent += `
                        <tr>
                            <td>${account.account_mql}</td>
                            <td>${account.status}</td>
                            <td>${createdAt}</td>
                        </tr>`;
                });

                tableContent += `
                        </tbody>
                    </table>`;

                jQuery('#mql-account-details').html(tableContent);
            } else {
                // Display "No accounts found" message inside the table if no data is found
                var noDataContent = `
                    <table class="wp-list-table widefat fixed striped">
                        <thead>
                            <tr>
                                <th>Account MQL</th>
                                <th>Status</th>
                                <th>Created At</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="5">No accounts found for this license.</td>
                            </tr>
                        </tbody>
                    </table>`;

                jQuery('#mql-account-details').html(noDataContent);
            }

            // Show the modal
            jQuery('#mqlAccountModal').fadeIn();
        },
        error: function(xhr) {
            // Handle 404 and other errors gracefully by displaying a message in the table
            var errorContent = `
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th>Account MQL</th>
                            <th>Status</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="5">No accounts found for this license.</td>
                        </tr>
                    </tbody>
                </table>`;

            jQuery('#mql-account-details').html(errorContent);
            jQuery('#mqlAccountModal').fadeIn();
        }
    });
}

function toggleLicenseStatus(licenseId, newStatus) {
    var confirmationMessage = newStatus === 'inactive'
        ? "Are you sure you want to deactivate this license?"
        : "Are you sure you want to activate this license?";

    if (!confirm(confirmationMessage)) {
        return; // Exit if user cancels
    }

    // Construct the API URL to update the license status
    var apiEndpoint = ealicensewoocommerce_params.api_base_endpoint + 'v1/licenses/' + licenseId;
    
    // Make an AJAX request to update the license status
    jQuery.ajax({
        url: apiEndpoint,
        method: 'PUT',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + ealicensewoocommerce_params.api_authorization_key
        },
        data: JSON.stringify({ status: newStatus }),
        success: function(response) {
            // On success, refresh the page to reflect the status change or update the icon dynamically
            location.reload(); // You could also dynamically update the icon here instead of reloading
        },
        error: function(xhr) {
            alert('Failed to update the license status. Please try again.');
        }
    });
}


// Modal close function
jQuery(document).ready(function($) {
    $('.mql-close').on('click', function() {
        $('#mqlAccountModal').fadeOut();
    });
});


