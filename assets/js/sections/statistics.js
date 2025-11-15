// Load summary statistics on page load
loadSummaryStats();
totalSatQsos();
totalQsosPerYear();

// Needed for sattable header fix, will be squished without
$("a[href='#satellite']").on('shown.bs.tab', function(e) {
    $(".sattable").DataTable().columns.adjust();
});

$("a[href='#bandtab']").on('shown.bs.tab', function(e) {
    if (!($('.bandtable').length > 0)) {
        totalBandQsos();
    }
});

$("a[href='#modetab']").on('shown.bs.tab', function(e) {
    if (!($('.modetable').length > 0)) {
        totalModeQsos();
    }
});

$("a[href='#qsotab']").on('shown.bs.tab', function(e) {
    if (!($('.qsotable').length > 0)) {
        totalQsos();
    }
});

$("a[href='#uniquetab']").on('shown.bs.tab', function(e) {
    if (!($('.uniquetable').length > 0)) {
        uniqueCallsigns();
    }
});

$("a[href='#trendstab']").on('shown.bs.tab', function(e) {
    if (!($('.trends-loaded').length > 0)) {
        loadTrends();
    }
});

$("a[href='#continentstab']").on('shown.bs.tab', function(e) {
    if (!($('.continents-loaded').length > 0)) {
        loadContinents();
    }
});

$("a[href='#mostworkedtab']").on('shown.bs.tab', function(e) {
    if (!($('.mostworked-loaded').length > 0)) {
        loadMostWorked();
    }
});

function loadSummaryStats() {
    $.ajax({
        url: base_url+'index.php/statistics/get_summary_stats',
        type: 'post',
        data: getDateFilterParams(),
        success: function (data) {
            // Check if user has no QSOs
            if (data.total_qsos == 0) {
                $('#summaryCards').html('<div class="alert alert-info text-center"><h4><i class="fas fa-info-circle"></i> No QSOs Found</h4><p>You haven\'t logged any contacts yet. Start logging QSOs to see your statistics here!</p></div>');
                
                // Hide the tabs since there's no data
                $('.nav-pills').hide();
                $('.tab-content').html('<div class="alert alert-info text-center mt-3">Log your first QSO to start building your statistics.</div>');
                return;
            }
            
            var html = '';
            html += '<div class="stats-card">';
            html += '<div class="stats-card-value">' + Number(data.total_qsos).toLocaleString() + '</div>';
            html += '<div class="stats-card-label">Total QSOs</div>';
            html += '</div>';
            
            html += '<div class="stats-card">';
            html += '<div class="stats-card-value">' + Number(data.unique_callsigns).toLocaleString() + '</div>';
            html += '<div class="stats-card-label">Unique Callsigns</div>';
            html += '</div>';
            
            html += '<div class="stats-card">';
            html += '<div class="stats-card-value">' + Number(data.total_countries).toLocaleString() + '</div>';
            html += '<div class="stats-card-label">Countries Worked</div>';
            html += '</div>';
            
            html += '<div class="stats-card">';
            html += '<div class="stats-card-value">' + data.total_bands + '</div>';
            html += '<div class="stats-card-label">Bands Worked</div>';
            html += '</div>';
            
            html += '<div class="stats-card">';
            html += '<div class="stats-card-value">' + data.total_modes + '</div>';
            html += '<div class="stats-card-label">Modes Used</div>';
            html += '</div>';
            
            $('#summaryCards').html(html);
            
            // Make sure tabs are visible
            $('.nav-pills').show();
        },
        error: function() {
            $('#summaryCards').html('<div class="alert alert-danger">Failed to load summary statistics</div>');
        }
    });
}

function uniqueCallsigns() {
    $.ajax({
        url: base_url+'index.php/statistics/get_unique_callsigns',
        type: 'post',
        success: function (data) {
            if (data.length > 0) {
                $(".unique").html(data);
            }
        },
        error: function() {
            $('.unique').html('<div class="alert alert-danger">Failed to load unique callsigns</div>');
        }
    });
}

function totalQsos() {
    $.ajax({
        url: base_url+'index.php/statistics/get_total_qsos',
        type: 'post',
        success: function (data) {
            if (data.length > 0) {
                $(".qsos").html(data);
            }
        },
        error: function() {
            $('.qsos').html('<div class="alert alert-danger">Failed to load QSO statistics</div>');
        }
    });
}

function totalQsosPerYear() {
        // using this to change color of legend and label according to background color
        var color = ifDarkModeThemeReturn('white', 'grey');
    
        $.ajax({
            url: base_url+'index.php/statistics/get_year',
            type: 'post',
            data: getDateFilterParams(),
            success: function (data) {
                if (data.length > 0) {
                   
                    $(".years").html('<h2>' + lang_statistics_years + '</h2><div id="yearContainer"></div><div id="yearTable"></div>');
                    $("#yearContainer").append("<canvas id=\"yearChart\" width=\"400\" height=\"100\"></canvas>");
    
                    // appending table to hold the data
                    $("#yearTable").append('<table style="width:100%" class="yeartable table table-sm table-bordered table-hover table-striped table-condensed text-center"><thead>' +
                        '<tr>' +
                        '<td>#</td>' +
                        '<td>' + lang_statistics_year +'</td>' +
                        '<td>' + lang_statistics_number_of_qso_worked + ' </td>' +
                        '</tr>' +
                        '</thead>' +
                        '<tbody></tbody></table>');
                        
                    var labels = [];
                    var dataQso = [];
    
                    var $myTable = $('.yeartable');
                    var i = 1;
    
                    // building the rows in the table
                    var rowElements = data.map(function (row) {
    
                        var $row = $('<tr></tr>');
    
                        var $iterator = $('<td></td>').html(i++);
                        var $type = $('<td></td>').html(row.year);
                        var $content = $('<td></td>').html(row.total);
    
                        $row.append($iterator, $type, $content);
    
                        return $row;
                    });
    
                    // finally inserting the rows
                    $myTable.append(rowElements);
    
                    $.each(data, function () {
                        labels.push(this.year);
                        dataQso.push(this.total);
                    });

                    labels.reverse();
                    dataQso.reverse();
    
                    var ctx = document.getElementById("yearChart").getContext('2d');
                    var myChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: lang_statistics_number_of_qso_worked_each_year,
                                data: dataQso,
                                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 2,
                                color: color
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: true,
                            scales: {
                                y: {
                                    ticks: {
                                        beginAtZero: true,
                                        color: color
                                    }
                                },
                                x: {
                                    ticks: {
                                        color: color
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    labels: {
                                        color: color
                                    }
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            return context.dataset.label + ': ' + context.parsed.y.toLocaleString() + ' QSOs';
                                        }
                                    }
                                }
                            },
                            onClick: function(evt, activeElements) {
                                if (activeElements.length > 0) {
                                    var year = labels[activeElements[0].index];
                                    window.location.href = base_url + 'index.php/logbook?year=' + year;
                                }
                            }
                        }
                    });
                    $('.yeartable').DataTable({
                        responsive: false,
                        ordering: false,
                        "scrollY": "320px",
                        "scrollCollapse": true,
                        "paging": false,
                        "scrollX": true,
                        "language": {
                            url: getDataTablesLanguageUrl(),
                        },
                        bFilter: false,
                        bInfo: false
                    });
    
                    // using this to change color of csv-button if dark mode is chosen
                    var background = $('body').css("background-color");
    
                    if (background != ('rgb(255, 255, 255)')) {
                        $(".buttons-csv").css("color", "white");
                    }
                }
            },
            error: function() {
                $('.years').html('<div class="alert alert-danger">Failed to load year statistics</div>');
            }
        });
}

function totalModeQsos() {
    // using this to change color of legend and label according to background color
    var color = ifDarkModeThemeReturn('white', 'grey');

    $.ajax({
        url: base_url+'index.php/statistics/get_mode',
        type: 'post',
        success: function (data) {
            if (data.length > 0) {
                var labels = [];
                var dataQso = [];

                $.each(data, function () {
                    labels.push(this.mode.toUpperCase());
                    dataQso.push(this.total);
                });

                if (dataQso[0] === null && dataQso[1] === null && dataQso[2] === null && dataQso[3] === null) return;
               
                $(".mode").html('<br /><div style="display: flex;" id="modeContainer"><h2>' + lang_statistics_modes + '</h2><div style="flex: 1;"><canvas id="modeChart" width="500" height="500"></canvas></div><div style="flex: 1;" id="modeTable"></div></div><br />');
                
                // appending table to hold the data
                $("#modeTable").append('<table style="width:100%" class=\"modetable table table-sm table-bordered table-hover table-striped table-condensed text-center"><thead>' +
                    '<tr>' +
                    '<td>#</td>' +
                    '<td>' + lang_gen_hamradio_mode + ' </td>' +
                    '<td>' + lang_statistics_number_of_qso_worked + ' </td>' +
                    '</tr>' +
                    '</thead>' +
                    '<tbody></tbody></table>');


                var $myTable = $('.modetable');
                var i = 1;

                // building the rows in the table
                var rowElements = data.map(function (row) {

                    var $row = $('<tr></tr>');

                    var $iterator = $('<td></td>').html(i++);
                    var $type = $('<td></td>').html(row.mode.toUpperCase());
                    var $content = $('<td></td>').html(row.total);

                    $row.append($iterator, $type, $content);

                    return $row;
                });

                // finally inserting the rows
                $myTable.append(rowElements);

                const COLORS = ["#3366cc", "#dc3912", "#ff9900", "#109618", "#990099", "#0099c6", "#dd4477", "#66aa00", "#b82e2e", "#316395", "#994499"]

                var ctx = document.getElementById("modeChart").getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'pie',
                    plugins: [ChartPieChartOutlabels],
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Number of QSO\'s worked',
                            data: dataQso,
                            backgroundColor: ["#3366cc", "#dc3912", "#ff9900", "#109618", "#990099", "#0099c6", "#dd4477", "#66aa00", "#b82e2e", "#316395", "#994499"],
                            borderWidth: 1,
                            borderColor: 'rgba(54, 162, 235, 1)',
                        }]
                    },

                    options: {
                        layout: {
                            padding: 100
                        },
                        title: {
                            color: color,
                            fullSize: true,
                        },
                        responsive: false,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false,
                                labels: {
                                    boxWidth: 15,
                                    color: color,
                                    font: {
                                        size: 14,
                                    }
                                },
                                position: 'right',
                                align: "middle"
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        var label = context.label || '';
                                        var value = context.parsed || 0;
                                        var total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        var percentage = ((value / total) * 100).toFixed(1);
                                        return label + ': ' + value.toLocaleString() + ' QSOs (' + percentage + '%)';
                                    }
                                }
                            },
                            outlabels: {
                                backgroundColor: COLORS,
                                borderColor: COLORS,
                                borderRadius: 2, // Border radius of Label
                                borderWidth: 2, // Thickness of border
                                color: 'white',
                                stretch: 45,
                                padding: 0,
                                font: {
                                    resizable: true,
                                    minSize: 15,
                                    maxSize: 25,
                                    family: Chart.defaults.font.family,
                                    size: Chart.defaults.font.size,
                                    style: Chart.defaults.font.style,
                                    lineHeight: Chart.defaults.font.lineHeight,
                                },
                                zoomOutPercentage: 100,
                                textAlign: 'start',
                                backgroundColor: COLORS,
                              }
                            
                        }
                    }
                });

                // using this to change color of csv-button if dark mode is chosen
                var background = $('body').css("background-color");

                if (background != ('rgb(255, 255, 255)')) {
                    $(".buttons-csv").css("color", "white");
                }
            }
        },
        error: function() {
            $('.mode').html('<div class="alert alert-danger">Failed to load mode statistics</div>');
        }
    });
}

function totalBandQsos() {
    // using this to change color of legend and label according to background color
    var color = ifDarkModeThemeReturn('white', 'grey');

    $.ajax({
        url: base_url+'index.php/statistics/get_band',
        type: 'post',
        success: function (data) {
            if (data.length > 0) {
               
                $(".band").html('<br /><div style="display: flex;" id="bandContainer"><h2>' + lang_statistics_bands + '</h2><div style="flex: 1;"><canvas id="bandChart" width="500" height="500"></canvas></div><div style="flex: 1;" id="bandTable"></div></div><br />');

                // appending table to hold the data
                $("#bandTable").append('<table style="width:100%" class="bandtable table table-sm table-bordered table-hover table-striped table-condensed text-center"><thead>' +
                    '<tr>' +
                    '<td>#</td>' +
                    '<td>' + lang_gen_hamradio_band + '</td>' +
                    '<td>' + lang_statistics_number_of_qso_worked + ' </td>' +
                    '</tr>' +
                    '</thead>' +
                    '<tbody></tbody></table>');
                var labels = [];
                var dataQso = [];
                var totalQso = Number(0);

                var $myTable = $('.bandtable');
                var i = 1;

                // building the rows in the table
                var rowElements = data.map(function (row) {

                    var $row = $('<tr></tr>');

                    var $iterator = $('<td></td>').html(i++);
                    var $type = $('<td></td>').html(row.band);
                    var $content = $('<td></td>').html(row.count);

                    $row.append($iterator, $type, $content);

                    return $row;
                });

                // finally inserting the rows
                $myTable.append(rowElements);

                $.each(data, function () {
                    labels.push(this.band);
                    dataQso.push(this.count);
                    totalQso = Number(totalQso) + Number(this.count);
                });

                const COLORS = ["#3366cc", "#dc3912", "#ff9900", "#109618", "#990099", "#0099c6", "#dd4477", "#66aa00", "#b82e2e", "#316395", "#994499"]
                var ctx = document.getElementById("bandChart").getContext('2d');
                var myChart = new Chart(ctx, {
                    plugins: [ChartPieChartOutlabels],
                    type: 'doughnut',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Number of QSO\'s worked',
                            data: dataQso,
                            borderColor: 'rgba(54, 162, 235, 1)',
                            backgroundColor: ["#3366cc", "#dc3912", "#ff9900", "#109618", "#990099", "#0099c6", "#dd4477", "#66aa00", "#b82e2e", "#316395", "#994499"],
                            borderWidth: 1,
                        }]
                    },
                    options: {
                        layout: {
                            padding: 100
                        },
                        title: {
                            fontColor: color,
                            fullSize: true,
                        },
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                display: false,
                                labels: {
                                    boxWidth: 15,
                                    color: color,
                                    font: {
                                        size: 14,
                                    }
                                },
                                position: 'right',
                                align: "middle"
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        var label = context.label || '';
                                        var value = context.parsed || 0;
                                        var percentage = ((value / totalQso) * 100).toFixed(1);
                                        return label + ': ' + value.toLocaleString() + ' QSOs (' + percentage + '%)';
                                    }
                                }
                            },
                            outlabels: {
                                display: function(context) { // Hide labels with low percentage
                                    return ((context.dataset.data[context.dataIndex] / totalQso * 100) > 1)
                                },
                                backgroundColor: COLORS,
                                borderColor: COLORS,
                                borderRadius: 2, // Border radius of Label
                                borderWidth: 2, // Thickness of border
                                color: 'white',
                                stretch: 10,
                                padding: 0,
                                font: {
                                    resizable: true,
                                    minSize: 12,
                                    maxSize: 25,
                                    family: Chart.defaults.font.family,
                                    size: Chart.defaults.font.size,
                                    style: Chart.defaults.font.style,
                                    lineHeight: Chart.defaults.font.lineHeight,
                                },
                                zoomOutPercentage: 100,
                                textAlign: 'start',
                                backgroundColor: COLORS,
                              }
                        }
                    }
                });

                $('.bandtable').DataTable({
                    responsive: false,
                    ordering: false,
                    "scrollY": "330px",
                    "scrollCollapse": true,
                    "paging": false,
                    "scrollX": true,
                    "language": {
                        url: getDataTablesLanguageUrl(),
                    },
                    bFilter: false,
                    bInfo: false,
                });

                // using this to change color of csv-button if dark mode is chosen
                var background = $('body').css("background-color");

                if (background != ('rgb(255, 255, 255)')) {
                    $(".buttons-csv").css("color", "white");
                }
            }
        },
        error: function() {
            $('.band').html('<div class="alert alert-danger">Failed to load band statistics</div>');
        }
    });
}

function totalSatQsos() {
    // using this to change color of legend and label according to background color
    var color = ifDarkModeThemeReturn('white', 'grey');

    $.ajax({
        url: base_url+'index.php/statistics/get_sat',
        type: 'post',
        success: function (data) {
            if (data.length > 0) {
                $('.tabs').removeAttr('hidden');

                var labels = [];
                var dataQso = [];
                var totalQso = Number(0);

                var $myTable = $('.sattable');
                var i = 1;

                // building the rows in the table
                var rowElements = data.map(function (row) {

                    var $row = $('<tr></tr>');

                    var $iterator = $('<td></td>').html(i++);
                    var $type = $('<td></td>').html(row.sat);
                    var $content = $('<td></td>').html(row.count);

                    $row.append($iterator, $type, $content);

                    return $row;
                });

                // finally inserting the rows
                $myTable.append(rowElements);

                $.each(data, function () {
                    labels.push(this.sat);
                    dataQso.push(this.count);
                    totalQso = Number(totalQso) + Number(this.count);
                });

                const COLORS = ["#3366cc", "#dc3912", "#ff9900", "#109618", "#990099", "#0099c6", "#dd4477", "#66aa00", "#b82e2e", "#316395", "#994499"]
                var ctx = document.getElementById("satChart").getContext('2d');
                var myChart = new Chart(ctx, {
                    plugins: [ChartPieChartOutlabels],
                    type: 'doughnut',
                    data: {
                        labels: labels,
                        datasets: [{
                            borderColor: 'rgba(54, 162, 235, 1)',
                            label: 'Number of QSO\'s worked',
                            data: dataQso,
                            backgroundColor: ["#3366cc", "#dc3912", "#ff9900", "#109618", "#990099", "#0099c6", "#dd4477", "#66aa00", "#b82e2e", "#316395", "#994499"],
                            borderWidth: 1,
                            labels: labels,
                        }]
                    },

                    options: {
                        layout: {
                            padding: 100
                        },
                        title: {
                            fontColor: color,
                            fullSize: true,
                        },
                        responsive: false,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                display: false,
                                labels: {
                                    boxWidth: 15,
                                    color: color,
                                    font: {
                                        size: 14,
                                    }
                                },
                                position: 'right',
                                align: "middle"
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        var label = context.label || '';
                                        var value = context.parsed || 0;
                                        var percentage = ((value / totalQso) * 100).toFixed(1);
                                        return label + ': ' + value.toLocaleString() + ' QSOs (' + percentage + '%)';
                                    }
                                }
                            },
                            outlabels: {
                                display: function(context) { // Hide labels with low percentage
                                    return ((context.dataset.data[context.dataIndex] / totalQso * 100) > 1)
                                },
                                backgroundColor: COLORS,
                                borderColor: COLORS,
                                borderRadius: 2, // Border radius of Label
                                borderWidth: 2, // Thickness of border
                                color: 'white',
                                stretch: 10,
                                padding: 0,
                                font: {
                                    resizable: true,
                                    minSize: 12,
                                    maxSize: 25,
                                    family: Chart.defaults.font.family,
                                    size: Chart.defaults.font.size,
                                    style: Chart.defaults.font.style,
                                    lineHeight: Chart.defaults.font.lineHeight,
                                },
                                zoomOutPercentage: 100,
                                textAlign: 'start',
                                backgroundColor: COLORS,
                              }
                        }
                    }
                });

                // using this to change color of csv-button if dark mode is chosen
                var background = $('body').css("background-color");

                if (background != ('rgb(255, 255, 255)')) {
                    $(".buttons-csv").css("color", "white");
                }

                $('.sattable').DataTable({
                    responsive: false,
                    ordering: false,
                    "scrollY": "330px",
                    "scrollX": true,
                    "language": {
                        url: getDataTablesLanguageUrl(),
                    },
                    "ScrollCollapse": true,
                    "paging": false,
                    bFilter: false,
                    bInfo: false,
                });
            }
        }
    });
}

function loadTrends() {
    var color = ifDarkModeThemeReturn('white', 'grey');
    
    $.ajax({
        url: base_url+'index.php/statistics/get_trends',
        type: 'post',
        success: function (data) {
            var html = '<div class="trends-loaded">';
            html += '<h2>Activity Trends</h2>';
            
            // Summary cards for trends
            html += '<div class="row mb-4">';
            html += '<div class="col-md-4">';
            html += '<div class="stats-card">';
            html += '<div class="stats-card-icon"><i class="fas fa-calendar-day text-primary"></i></div>';
            html += '<div class="stats-card-value">' + data.this_month + '</div>';
            html += '<div class="stats-card-label">QSOs This Month</div>';
            html += '</div></div>';
            
            html += '<div class="col-md-4">';
            html += '<div class="stats-card">';
            html += '<div class="stats-card-icon"><i class="fas fa-calendar-alt text-success"></i></div>';
            html += '<div class="stats-card-value">' + data.this_year + '</div>';
            html += '<div class="stats-card-label">QSOs This Year</div>';
            html += '</div></div>';
            
            html += '<div class="col-md-4">';
            html += '<div class="stats-card">';
            html += '<div class="stats-card-icon"><i class="fas fa-calendar-week text-info"></i></div>';
            html += '<div class="stats-card-value">' + data.last_30_days + '</div>';
            html += '<div class="stats-card-label">Last 30 Days</div>';
            html += '</div></div>';
            html += '</div>';
            
            // Monthly trend chart
            html += '<div class="row">';
            html += '<div class="col-12">';
            html += '<canvas id="trendsChart" width="400" height="100"></canvas>';
            html += '</div></div></div>';
            
            $('.trends').html(html);
            
            // Create chart
            var labels = data.monthly.map(m => m.month);
            var counts = data.monthly.map(m => m.count);
            
            var ctx = document.getElementById("trendsChart").getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'QSOs per Month',
                        data: counts,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { color: color }
                        },
                        x: {
                            ticks: { color: color }
                        }
                    },
                    plugins: {
                        legend: {
                            labels: { color: color }
                        }
                    }
                }
            });
        },
        error: function() {
            $('.trends').html('<div class="alert alert-danger">Failed to load trends</div>');
        }
    });
}

function loadContinents() {
    var color = ifDarkModeThemeReturn('white', 'grey');
    
    $.ajax({
        url: base_url+'index.php/statistics/get_continents',
        type: 'post',
        success: function (data) {
            if (data.length > 0) {
                var html = '<div class="continents-loaded">';
                html += '<br /><div style="display: flex;" id="continentContainer">';
                html += '<h2>Continents</h2>';
                html += '<div style="flex: 1;"><canvas id="continentChart" width="500" height="500"></canvas></div>';
                html += '<div style="flex: 1;" id="continentTable"></div></div><br /></div>';
                
                $('.continents').html(html);
                
                // Build table
                var tableHtml = '<table style="width:100%" class="continenttable table table-sm table-bordered table-hover table-striped table-condensed text-center"><thead>';
                tableHtml += '<tr><td>#</td><td>Continent</td><td># of QSOs</td></tr></thead><tbody>';
                
                var labels = [];
                var counts = [];
                
                data.forEach((item, index) => {
                    tableHtml += '<tr><td>' + (index + 1) + '</td><td>' + item.continent + '</td><td>' + item.count + '</td></tr>';
                    labels.push(item.continent);
                    counts.push(item.count);
                });
                
                tableHtml += '</tbody></table>';
                $('#continentTable').html(tableHtml);
                
                // Create chart
                const COLORS = ["#3366cc", "#dc3912", "#ff9900", "#109618", "#990099", "#0099c6", "#dd4477"];
                var ctx = document.getElementById("continentChart").getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'QSOs per Continent',
                            data: counts,
                            backgroundColor: COLORS,
                            borderColor: COLORS,
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: false,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: { color: color }
                            },
                            x: {
                                ticks: { color: color }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            }
        },
        error: function() {
            $('.continents').html('<div class="alert alert-danger">Failed to load continent statistics</div>');
        }
    });
}

function loadMostWorked() {
    $.ajax({
        url: base_url+'index.php/statistics/get_most_worked',
        type: 'post',
        success: function (data) {
            var html = '<div class="mostworked-loaded"><h2>Most Worked</h2>';
            html += '<div class="row">';
            
            // Most worked callsigns
            html += '<div class="col-md-6">';
            html += '<h4><i class="fas fa-user text-primary"></i> Top 10 Callsigns</h4>';
            html += '<table class="table table-sm table-bordered table-hover table-striped">';
            html += '<thead><tr><th>#</th><th>Callsign</th><th>Country</th><th>QSOs</th></tr></thead><tbody>';
            
            data.callsigns.forEach((item, index) => {
                html += '<tr>';
                html += '<td>' + (index + 1) + '</td>';
                html += '<td><strong>' + item.callsign + '</strong></td>';
                html += '<td>' + (item.country || '-') + '</td>';
                html += '<td><span class="badge bg-primary">' + item.count + '</span></td>';
                html += '</tr>';
            });
            
            html += '</tbody></table></div>';
            
            // Most worked countries
            html += '<div class="col-md-6">';
            html += '<h4><i class="fas fa-flag text-success"></i> Top 10 Countries</h4>';
            html += '<table class="table table-sm table-bordered table-hover table-striped">';
            html += '<thead><tr><th>#</th><th>Country</th><th>QSOs</th></tr></thead><tbody>';
            
            data.countries.forEach((item, index) => {
                html += '<tr>';
                html += '<td>' + (index + 1) + '</td>';
                html += '<td><strong>' + item.country + '</strong></td>';
                html += '<td><span class="badge bg-success">' + item.count + '</span></td>';
                html += '</tr>';
            });
            
            html += '</tbody></table></div>';
            html += '</div></div>';
            
            $('.mostworked').html(html);
        },
        error: function() {
            $('.mostworked').html('<div class="alert alert-danger">Failed to load most worked statistics</div>');
        }
    });
}

// Date filter functions
function toggleDateFilter() {
    $('#dateFilterCard').slideToggle();
}

function applyDateFilter() {
    var startDate = $('#filterStartDate').val();
    var endDate = $('#filterEndDate').val();
    
    if (!startDate || !endDate) {
        alert('Please select both start and end dates');
        return;
    }
    
    if (new Date(startDate) > new Date(endDate)) {
        alert('Start date must be before end date');
        return;
    }
    
    // Store filter in session/local storage
    localStorage.setItem('stats_filter_start', startDate);
    localStorage.setItem('stats_filter_end', endDate);
    
    // Reload all statistics with filter
    loadSummaryStats();
    
    // Clear and reload active tab
    $('.years').html('<div class="loading-indicator"><div class="loading-spinner"></div><p>Loading filtered data...</p></div>');
    totalQsosPerYear();
    
    // Show filter is active
    $('#dateFilterCard').addClass('border-primary');
}

function clearDateFilter() {
    $('#filterStartDate').val('');
    $('#filterEndDate').val('');
    localStorage.removeItem('stats_filter_start');
    localStorage.removeItem('stats_filter_end');
    $('#dateFilterCard').removeClass('border-primary');
    
    // Reload data without filter
    loadSummaryStats();
    $('.years').html('<div class="loading-indicator"><div class="loading-spinner"></div><p>Loading data...</p></div>');
    totalQsosPerYear();
}

// Get date filter parameters for AJAX calls
function getDateFilterParams() {
    var params = {};
    var startDate = localStorage.getItem('stats_filter_start');
    var endDate = localStorage.getItem('stats_filter_end');
    
    if (startDate && endDate) {
        params.start_date = startDate;
        params.end_date = endDate;
    }
    
    return params;
}