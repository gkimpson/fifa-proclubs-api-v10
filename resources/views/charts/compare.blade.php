<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>Laravel Highcharts Demo</title>
</head>
<body>
<h1>Highcharts in Laravel Example</h1>
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
    const chartData = <?php echo json_encode($chartData)?>;
    console.log('chartData', chartData);

    Highcharts.chart('container', {

        chart: {
            polar: true
        },

        title: {
            text: 'Highcharts Polar Chart'
        },

        subtitle: {
            text: 'Also known as Radar Chart'
        },

        pane: {
            startAngle: 0,
            endAngle: 360
        },

        xAxis: {
            tickInterval: 45,
            min: 0,
            max: 360,
            labels: {
                format: '{value}°'
            }
        },

        yAxis: {
            min: 0
        },

        plotOptions: {
            series: {
                pointStart: 0,
                pointInterval: 45
            },
            column: {
                pointPadding: 0,
                groupPadding: 0
            }
        },

        series: [{
            type: 'column',
            name: 'Column',
            data: [8, 7, 6, 5, 4, 3, 2, 1],
            pointPlacement: 'between'
        }, {
            type: 'line',
            name: 'Line',
            data: [1, 2, 3, 4, 5, 6, 7, 8]
        }, {
            type: 'area',
            name: 'Area',
            data: [1, 8, 2, 7, 3, 6, 4, 5]
        }]
    });

</script>
<figure class="highcharts-figure">
    <div id="container"></div>
    <p class="highcharts-description">
        A spiderweb chart or radar chart is a variant of the polar chart.
        Spiderweb charts are commonly used to compare multivariate data sets,
        like this demo using six variables of comparison.
    </p>
</figure>
</html>
