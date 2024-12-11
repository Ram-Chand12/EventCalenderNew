window.onload = function () {
    CKEDITOR.replace('event_description');
};

jQuery(document).ready(function() {

    $('body').on('click', '.get_hostory', function() {

        var refId = $(this).attr('data-ref-id');
        var entityType = $(this).attr('data-entity-type');
        var modalBody = document.querySelector('#importModal .modal-body tbody');
        
        // Make an Ajax request
        $.ajax({
            url: '/synchistory',
            type: 'GET',
            data: {
                'ref_id': refId,
                'entity_type': entityType
            },
            dataType: 'json',
            success: function(response) {
                // Clear previous content
                modalBody.innerHTML = '';
                console.log(response.length,'----');
                if (response.length === 0) {
                    // If no data is found, show a message
                    var row = document.createElement('tr');
                    var messageCell = document.createElement('td');
                    messageCell.setAttribute('colspan', '7'); // Adjust colspan based on your table structure
                    messageCell.textContent = 'No Record Found';
                    messageCell.style.textAlign = 'center';
                    row.appendChild(messageCell);
                    modalBody.appendChild(row);
                } else {
                    // Append fetched data to modal body
                    response.forEach(function(entry) {
                        var row = document.createElement('tr');

                        var clubNameCell = document.createElement('td');
                        clubNameCell.textContent = entry.club_name;
                        row.appendChild(clubNameCell);

                        var messageTypeCell = document.createElement('td');
                        messageTypeCell.textContent = entry.created_at;
                        row.appendChild(messageTypeCell);

                        var messageCell = document.createElement('td');
                        messageCell.textContent = entry.updated_at;
                        row.appendChild(messageCell);

                        var noOfTriesCell = document.createElement('td');
                        noOfTriesCell.textContent = entry.message;
                        row.appendChild(noOfTriesCell);

                        var lastSycnCell = document.createElement('td');
                        lastSycnCell.textContent = entry.no_of_tries;
                        row.appendChild(lastSycnCell);

                        var statusCell = document.createElement('td');
                        // Check status and set message accordingly
                        if (entry.status == 1) {
                            statusCell.textContent = 'Not Sync';
                        } else if (entry.status == 2) {
                            statusCell.textContent = 'Pending for Update';
                        } else if (entry.status == 3) {
                            statusCell.textContent = 'Complete'; // Handle other status values if needed
                        } else {
                            statusCell.textContent = entry.status;
                        }
                        row.appendChild(statusCell);

                        modalBody.appendChild(row);
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
        
    });

});


$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('.mark-as-read').on('click', function() {
        var id = $(this).data('id');
        $(this).hide();
        $(this).siblings('.mark').show();

        $.ajax({
            type: 'POST',
            url: '/update-notification', 
            data: {id: id, read: 1},
            success: function(data) {
                console.log('Marked as read successfully');
            }
        });
    });

    $('.mark').on('click', function() {
        var id = $(this).data('id');
        $(this).hide();
        $(this).siblings('.mark-as-read').show();

        $.ajax({
            type: 'POST',
            url: '/update-notification', 
            data: {id: id, read: 0},
            success: function(data) {
                console.log('Marked successfully');
            }
        });
    });
});