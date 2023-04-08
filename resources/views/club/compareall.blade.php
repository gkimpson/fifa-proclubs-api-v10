<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Player Comparision') }}
        </h2>
    </x-slot>

    <!-- Start Results -->
    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div id="container"></div>
                </div>
                <div class="py-2">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 text-gray-900 dark:text-gray-100">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>


<!-- TODO do this in the proper Laravel way -->
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
    console.log('data', data);

    let chart = Highcharts.chart('container', {

        chart: {
            polar: true,
            type: 'line'
        },

        accessibility: {
            description: 'A spiderweb chart compares the six variables of comparison for two players.'
        },

        title: {
            text: 'Club Player Comparision',
            x: -80
        },

        pane: {
            size: '90%'
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

        series: [],

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

    var series = data.chartData.players;
    console.log(series);

    // Add the series to the chart
    series.forEach(function (serie) {
        chart.addSeries(serie);
    });
</script>
<figure class="highcharts-figure">
    <div id="container"></div>
    <p class="highcharts-description">
        A spiderweb chart compares the seven variables of comparison for two players.
    </p>
</figure>
</html>
