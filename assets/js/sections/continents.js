$(document).ready(function () {
    // Needed for continentstable header fix, will be squished without
    $("a[href='#continents']").on('shown.bs.tab', function(e) {
        $(".continentstable").DataTable().columns.adjust();
    });

    $('#searchForm').submit(function (e) {
        $('#searchButton').prop("disabled", true);

        $.ajax({
            url: this.action,
            type: 'post',
            data: {
                mode: this.mode.value,
                band: this.band.value,
            },
            dataType: 'json',
            success: function (data) {
                $('#searchButton').prop("disabled", false);
                totalContinentQsos(data);
            },
            error: function (data) {
                $('#searchButton').prop("disabled", false);
                BootstrapDialog.alert({
                    title: 'ERROR',
                    message: 'An error ocurred while making the request',
                    type: BootstrapDialog.TYPE_DANGER,
                    closable: false,
                    draggable: false,
                    callback: function (result) {
                    }
                });
            },
        });
        return false;
    });

    $('#searchForm').submit();
});

function totalContinentQsos(data) {
    // using this to change color of legend and label according to background color
    var color = ifDarkModeThemeReturn('white', 'grey');

            if (data.length > 0) {
                $('.continentstable > tbody').empty();
                $('.tabs').removeAttr('hidden');

                var labels = [];
                var dataQso = [];
                var totalQso = Number(0);

                var $myTable = $('.continentstable');
                var i = 1;

                // building the rows in the table
                var rowElements = data.map(function (row) {

                    var $row = $('<tr></tr>');

                    var $iterator = $('<td></td>').html(i++);
                    var $type = $('<td></td>').html(row.cont);
                    var $content = $('<td></td>').html(row.count);

                    $row.append($iterator, $type, $content);

                    return $row;
                });

                // finally inserting the rows
                $myTable.append(rowElements);

                $.each(data, function () {
                    labels.push(this.cont);
                    dataQso.push(this.count);
                    totalQso = Number(totalQso) + Number(this.count);
                });

                const COLORS = ["#3366cc", "#dc3912", "#ff9900", "#109618", "#990099", "#0099c6", "#dd4477", "#66aa00", "#b82e2e", "#316395", "#994499"]
                
                let chartStatus = Chart.getChart("continentChart"); // <canvas> id
                if (chartStatus != undefined) {
                    chartStatus.destroy();
                }
                
                var ctx = document.getElementById("continentChart").getContext('2d');
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
                            outlabels: {
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
            }
}
