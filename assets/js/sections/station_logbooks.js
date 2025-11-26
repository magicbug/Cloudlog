$(document).ready( function () {
    $('#station_logbooks_table').DataTable({
        "stateSave": true,
        "language": {
            url: getDataTablesLanguageUrl(),
        }
    });
} );

$(document).ready( function () {
    // Only initialize DataTable if there are actual data rows (not just the "no data" message)
    var table = $('#station_logbooks_linked_table');
    if (table.find('tbody tr').length > 0 && table.find('tbody tr td[colspan]').length === 0) {
        table.DataTable({
            "stateSave": true,
            "paging": true,
            "language": {
                url: getDataTablesLanguageUrl(),
            }
        });
    }
} );
