$(document).ready( function () {
    $('#station_logbooks_table').DataTable({
        "stateSave": true,
        "language": {
            url: getDataTablesLanguageUrl(),
        }
    });
} );

$(document).ready( function () {
    $('#station_logbooks_linked_table').DataTable({
        "stateSave": true,
        "paging": true,
        "language": {
            url: getDataTablesLanguageUrl(),
        }
    });
} );
