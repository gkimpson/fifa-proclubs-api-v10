<!DOCTYPE html>
<body>
<div id="container"></div>
</body>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/highcharts-more.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<style>
    .highcharts-figure,
    .highcharts-data-table table {
        min-width: 320px;
        max-width: 700px;
        margin: 1em auto;
    }

    .highcharts-data-table table {
        font-family: Verdana, sans-serif;
        border-collapse: collapse;
        border: 1px solid #ebebeb;
        margin: 10px auto;
        text-align: center;
        width: 100%;
        max-width: 500px;
    }

    .highcharts-data-table caption {
        padding: 1em 0;
        font-size: 1.2em;
        color: #555;
    }

    .highcharts-data-table th {
        font-weight: 600;
        padding: 0.5em;
    }

    .highcharts-data-table td,
    .highcharts-data-table th,
    .highcharts-data-table caption {
        padding: 0.5em;
    }

    .highcharts-data-table thead tr,
    .highcharts-data-table tr:nth-child(even) {
        background: #f8f8f8;
    }

    .highcharts-data-table tr:hover {
        background: #f1f7ff;
    }

</style>
<script type="text/javascript">
    const data = <?php echo json_encode($data)?>;

    Highcharts.chart('container', {

        chart: {
            polar: true,
            type: 'line'
        },

        accessibility: {
            description: 'A spiderweb chart compares the six variables of comparison for two players.'
        },

        title: {
            text: data.chartData.player1.name + ' vs ' + data.chartData.player2.name,
            x: -80
        },

        pane: {
            size: '100%'
        },

        xAxis: {
            categories: ['Shooting', 'Passing', 'Dribbling', 'Defending', 'Physical', 'Pace', 'Goalkeeping'],
            tickmarkPlacement: 'on',
            lineWidth: 0
        },

        yAxis: {
            gridLineInterpolation: 'polygon',
            lineWidth: 0,
            min: 0
        },

        tooltip: {
            shared: true,
            pointFormat: '<span style="color:{series.color}">{series.name}: <b>{point.y:,.0f}/99</b><br/>'
        },

        legend: {
            align: 'right',
            verticalAlign: 'middle',
            layout: 'vertical'
        },

        series: [{
            name: data.chartData.player1.name,
            data: [data.chartData.player1.averages.shooting,
                data.chartData.player1.averages.passing,
                data.chartData.player1.averages.dribbling,
                data.chartData.player1.averages.defending,
                data.chartData.player1.averages.physical,
                data.chartData.player1.averages.pace,
                data.chartData.player1.averages.goalkeeping],
            pointPlacement: 'on'
        }, {
            name: data.chartData.player2.name,
            data: [data.chartData.player2.averages.shooting,
                data.chartData.player2.averages.passing,
                data.chartData.player2.averages.dribbling,
                data.chartData.player2.averages.defending,
                data.chartData.player2.averages.physical,
                data.chartData.player2.averages.pace,
                data.chartData.player2.averages.goalkeeping],
            pointPlacement: 'on'
        }],

        responsive: {
            rules: [{
                condition: {
                    maxWidth: 500
                },
                chartOptions: {
                    legend: {
                        align: 'center',
                        verticalAlign: 'bottom',
                        layout: 'horizontal'
                    },
                    pane: {
                        size: '70%'
                    }
                }
            }]
        },
    });
</script>
<figure class="highcharts-figure">
    <div id="container"></div>
    <p class="highcharts-description">
        A spiderweb chart compares the six variables of comparison for two players.
    </p>
</figure>
</html>
