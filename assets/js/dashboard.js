$(document).ready(function() {
    // Update Status
    $('.status-select').on('change', function() {
        let booking_id = $(this).data('id');
        let status = $(this).val();

        if (status === '') return;

        $.ajax({
            url: 'ajax_update.php',
            method: 'POST',
            data: {
                action: 'update_status',
                booking_id: booking_id,
                status: status
            },
            success: function(response) {
                if (response.success) {
                    $('#row_' + booking_id + ' .status-cell').text(status);
                    alert('✅ Status updated successfully!');
                } else {
                    alert('❌ Failed to update status.');
                }
            }
        });
    });

    // Update Location
    $('.update-location-btn').on('click', function() {
        let booking_id = $(this).data('id');
        let location = $('#row_' + booking_id + ' .location-input').val().trim();

        if (location === '') {
            alert('Please enter a location');
            return;
        }

        $.ajax({
            url: 'ajax_update.php',
            method: 'POST',
            data: {
                action: 'update_location',
                booking_id: booking_id,
                location: location
            },
            success: function(response) {
                if (response.success) {
                    $('#row_' + booking_id + ' .location-cell').text(location);
                    $('#row_' + booking_id + ' .location-input').val('');
                    alert('✅ Location updated!');
                } else {
                    alert('❌ Failed to update location.');
                }
            }
        });
    });
});
