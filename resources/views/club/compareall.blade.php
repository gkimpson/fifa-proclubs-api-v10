<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Player Comparision') }}
        </h2>
    </x-slot>

    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">

                <x-bladewind.tab-group name="staff-loans">
                    <x-slot name="headings">
                        @foreach ($chartData['players'] as $player)
                        <x-bladewind.tab-heading
                            name="{{ $player['name'] }}"
                            active="false"
                            label="{{ $player['name'] }}" />
                        @endforeach
                    </x-slot>
                    <x-bladewind.tab-body>
                        @foreach ($chartData['players'] as $player)
                        <x-bladewind.tab-content name="{{ $player['name'] }}" active="false">
                            <x-bladewind.table
                                striped="true"
                                divider="thin" class="w-1/2">

                                <div class="grid grid-flow-col justify-stretch">
                                    <div class="">
                                        <div>Shooting</div>
                                        <div class="flex flex-row text-xs">
                                            <div class="basis-3/4">Finishing</div>
                                            <div class="basis-1/4">
                                                <div class="text-xs font-semibold inline-block font-mono whitespace-nowrap mb-1.5 px-2 py-1 rounded text-white {{ \App\Helpers\PlayerAttributesHelper::getAttributeTailwindCssClass($player['mapped']['finishing']) }} rounded-2">{{ $player['mapped']['finishing'] }}</div>
                                            </div>
                                        </div>
                                        <div class="flex flex-row text-xs">
                                            <div class="basis-3/4">Heading Accuracy</div>
                                            <div class="basis-1/4">
                                                <div class="text-xs font-semibold inline-block font-mono whitespace-nowrap mb-1.5 px-2 py-1 rounded text-white {{ \App\Helpers\PlayerAttributesHelper::getAttributeTailwindCssClass($player['mapped']['heading_accuracy']) }} rounded-2">{{ $player['mapped']['heading_accuracy'] }}</div>
                                            </div>
                                        </div>
                                        <div class="flex flex-row text-xs">
                                            <div class="basis-3/4">Shot Power</div>
                                            <div class="basis-1/4">
                                                <div class="text-xs font-semibold inline-block font-mono whitespace-nowrap mb-1.5 px-2 py-1 rounded text-white {{ \App\Helpers\PlayerAttributesHelper::getAttributeTailwindCssClass($player['mapped']['shot_power']) }} rounded-2">{{ $player['mapped']['shot_power'] }}</div>
                                            </div>
                                        </div>
                                        <div class="flex flex-row text-xs">
                                            <div class="basis-3/4">Long Shots</div>
                                            <div class="basis-1/4">
                                                <div class="text-xs font-semibold inline-block font-mono whitespace-nowrap mb-1.5 px-2 py-1 rounded text-white {{ \App\Helpers\PlayerAttributesHelper::getAttributeTailwindCssClass($player['mapped']['long_shots']) }} rounded-2">{{ $player['mapped']['long_shots'] }}</div>
                                            </div>
                                        </div>
                                        <div class="flex flex-row text-xs">
                                            <div class="basis-3/4">Volleys</div>
                                            <div class="basis-1/4">
                                                <div class="text-xs font-semibold inline-block font-mono whitespace-nowrap mb-1.5 px-2 py-1 rounded text-white {{ \App\Helpers\PlayerAttributesHelper::getAttributeTailwindCssClass($player['mapped']['volleys']) }} rounded-2">{{ $player['mapped']['volleys'] }}</div>
                                            </div>
                                        </div>
                                        <div class="flex flex-row text-xs">
                                            <div class="basis-3/4">Penalties</div>
                                            <div class="basis-1/4">
                                                <div class="text-xs font-semibold inline-block font-mono whitespace-nowrap mb-1.5 px-2 py-1 rounded text-white {{ \App\Helpers\PlayerAttributesHelper::getAttributeTailwindCssClass($player['mapped']['penalties']) }} rounded-2">{{ $player['mapped']['penalties'] }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="">
                                        <div>Passing</div>
                                        <div class="flex flex-row text-xs">
                                            <div class="basis-3/4">Vision</div>
                                            <div class="basis-1/4">
                                                <div class="text-xs font-semibold inline-block font-mono whitespace-nowrap mb-1.5 px-2 py-1 rounded text-white {{ \App\Helpers\PlayerAttributesHelper::getAttributeTailwindCssClass($player['mapped']['vision']) }} rounded-2">{{ $player['mapped']['vision'] }}</div>
                                            </div>
                                        </div>
                                        <div class="flex flex-row text-xs">
                                            <div class="basis-3/4">Crossing</div>
                                            <div class="basis-1/4">
                                                <div class="text-xs font-semibold inline-block font-mono whitespace-nowrap mb-1.5 px-2 py-1 rounded text-white {{ \App\Helpers\PlayerAttributesHelper::getAttributeTailwindCssClass($player['mapped']['crossing']) }} rounded-2">{{ $player['mapped']['crossing'] }}</div>
                                            </div>
                                        </div>
                                        <div class="flex flex-row text-xs">
                                            <div class="basis-3/4">Long Pass</div>
                                            <div class="basis-1/4">
                                                <div class="text-xs font-semibold inline-block font-mono whitespace-nowrap mb-1.5 px-2 py-1 rounded text-white {{ \App\Helpers\PlayerAttributesHelper::getAttributeTailwindCssClass($player['mapped']['long_pass']) }} rounded-2">{{ $player['mapped']['long_pass'] }}</div>
                                            </div>
                                        </div>
                                        <div class="flex flex-row text-xs">
                                            <div class="basis-3/4">Short Pass</div>
                                            <div class="basis-1/4">
                                                <div class="text-xs font-semibold inline-block font-mono whitespace-nowrap mb-1.5 px-2 py-1 rounded text-white {{ \App\Helpers\PlayerAttributesHelper::getAttributeTailwindCssClass($player['mapped']['short_pass']) }} rounded-2">{{ $player['mapped']['short_pass'] }}</div>
                                            </div>
                                        </div>
                                        <div class="flex flex-row text-xs">
                                            <div class="basis-3/4">Curve</div>
                                            <div class="basis-1/4">
                                                <div class="text-xs font-semibold inline-block font-mono whitespace-nowrap mb-1.5 px-2 py-1 rounded text-white {{ \App\Helpers\PlayerAttributesHelper::getAttributeTailwindCssClass($player['mapped']['curve']) }} rounded-2">{{ $player['mapped']['curve'] }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="">
                                        <div>Dribbling</div>
                                        <div class="flex flex-row text-xs">
                                            <div class="basis-3/4">Agility</div>
                                            <div class="basis-1/4">
                                                <div class="text-xs font-semibold inline-block font-mono whitespace-nowrap mb-1.5 px-2 py-1 rounded text-white {{ \App\Helpers\PlayerAttributesHelper::getAttributeTailwindCssClass($player['mapped']['agility']) }} rounded-2">{{ $player['mapped']['agility'] }}</div>
                                            </div>
                                        </div>
                                        <div class="flex flex-row text-xs">
                                            <div class="basis-3/4">Balance</div>
                                            <div class="basis-1/4">
                                                <div class="text-xs font-semibold inline-block font-mono whitespace-nowrap mb-1.5 px-2 py-1 rounded text-white {{ \App\Helpers\PlayerAttributesHelper::getAttributeTailwindCssClass($player['mapped']['balance']) }} rounded-2">{{ $player['mapped']['balance'] }}</div>
                                            </div>
                                        </div>
                                        <div class="flex flex-row text-xs">
                                            <div class="basis-3/4">Attack Position</div>
                                            <div class="basis-1/4">
                                                <div class="text-xs font-semibold inline-block font-mono whitespace-nowrap mb-1.5 px-2 py-1 rounded text-white {{ \App\Helpers\PlayerAttributesHelper::getAttributeTailwindCssClass($player['mapped']['attack_position']) }} rounded-2">{{ $player['mapped']['attack_position'] }}</div>
                                            </div>
                                        </div>
                                        <div class="flex flex-row text-xs">
                                            <div class="basis-3/4">Ball Control</div>
                                            <div class="basis-1/4">
                                                <div class="text-xs font-semibold inline-block font-mono whitespace-nowrap mb-1.5 px-2 py-1 rounded text-white {{ \App\Helpers\PlayerAttributesHelper::getAttributeTailwindCssClass($player['mapped']['ball_control']) }} rounded-2">{{ $player['mapped']['ball_control'] }}</div>
                                            </div>
                                        </div>
                                        <div class="flex flex-row text-xs">
                                            <div class="basis-3/4">Dribbling</div>
                                            <div class="basis-1/4">
                                                <div class="text-xs font-semibold inline-block font-mono whitespace-nowrap mb-1.5 px-2 py-1 rounded text-white {{ \App\Helpers\PlayerAttributesHelper::getAttributeTailwindCssClass($player['mapped']['dribbling']) }} rounded-2">{{ $player['mapped']['dribbling'] }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="">
                                        <div>Defending</div>
                                        <div class="flex flex-row text-xs">
                                            <div class="basis-3/4">Interceptions</div>
                                            <div class="basis-1/4">
                                                <div class="text-xs font-semibold inline-block font-mono whitespace-nowrap mb-1.5 px-2 py-1 rounded text-white {{ \App\Helpers\PlayerAttributesHelper::getAttributeTailwindCssClass($player['mapped']['interceptions']) }} rounded-2">{{ $player['mapped']['interceptions'] }}</div>
                                            </div>
                                        </div>
                                        <div class="flex flex-row text-xs">
                                            <div class="basis-3/4">Marking</div>
                                            <div class="basis-1/4">
                                                <div class="text-xs font-semibold inline-block font-mono whitespace-nowrap mb-1.5 px-2 py-1 rounded text-white {{ \App\Helpers\PlayerAttributesHelper::getAttributeTailwindCssClass($player['mapped']['marking']) }} rounded-2">{{ $player['mapped']['marking'] }}</div>
                                            </div>
                                        </div>
                                        <div class="flex flex-row text-xs">
                                            <div class="basis-3/4">Stand Tackle</div>
                                            <div class="basis-1/4">
                                                <div class="text-xs font-semibold inline-block font-mono whitespace-nowrap mb-1.5 px-2 py-1 rounded text-white {{ \App\Helpers\PlayerAttributesHelper::getAttributeTailwindCssClass($player['mapped']['stand_tackle']) }} rounded-2">{{ $player['mapped']['stand_tackle'] }}</div>
                                            </div>
                                        </div>
                                        <div class="flex flex-row text-xs">
                                            <div class="basis-3/4">Slide Tackle</div>
                                            <div>
                                                <div class="text-xs font-semibold inline-block font-mono whitespace-nowrap mb-1.5 px-2 py-1 rounded text-white {{ \App\Helpers\PlayerAttributesHelper::getAttributeTailwindCssClass($player['mapped']['slide_tackle']) }} rounded-2">{{ $player['mapped']['slide_tackle'] }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="">
                                        <div>Physical</div>
                                        <div class="flex flex-row text-xs">
                                            <div class="basis-3/4">Jumping</div>
                                            <div class="basis-1/4">
                                                <div class="text-xs font-semibold inline-block font-mono whitespace-nowrap mb-1.5 px-2 py-1 rounded text-white {{ \App\Helpers\PlayerAttributesHelper::getAttributeTailwindCssClass($player['mapped']['jumping']) }} rounded-2">{{ $player['mapped']['jumping'] }}</div>
                                            </div>
                                        </div>
                                        <div class="flex flex-row text-xs">
                                            <div class="basis-3/4">Stamina</div>
                                            <div class="basis-1/4">
                                                <div class="text-xs font-semibold inline-block font-mono whitespace-nowrap mb-1.5 px-2 py-1 rounded text-white {{ \App\Helpers\PlayerAttributesHelper::getAttributeTailwindCssClass($player['mapped']['stamina']) }} rounded-2">{{ $player['mapped']['stamina'] }}</div>
                                            </div>
                                        </div>
                                        <div class="flex flex-row text-xs">
                                            <div class="basis-3/4">Strength</div>
                                            <div class="basis-1/4">
                                                <div class="text-xs font-semibold inline-block font-mono whitespace-nowrap mb-1.5 px-2 py-1 rounded text-white {{ \App\Helpers\PlayerAttributesHelper::getAttributeTailwindCssClass($player['mapped']['strength']) }} rounded-2">{{ $player['mapped']['strength'] }}</div>
                                            </div>
                                        </div>
                                        <div class="flex flex-row text-xs">
                                            <div class="basis-3/4">Reactions</div>
                                            <div class="basis-1/4">
                                                <div class="text-xs font-semibold inline-block font-mono whitespace-nowrap mb-1.5 px-2 py-1 rounded text-white {{ \App\Helpers\PlayerAttributesHelper::getAttributeTailwindCssClass($player['mapped']['reactions']) }} rounded-2">{{ $player['mapped']['reactions'] }}</div>
                                            </div>
                                        </div>
                                        <div class="flex flex-row text-xs">
                                            <div class="basis-3/4">Aggression</div>
                                            <div class="basis-1/4">
                                                <div class="text-xs font-semibold inline-block font-mono whitespace-nowrap mb-1.5 px-2 py-1 rounded text-white {{ \App\Helpers\PlayerAttributesHelper::getAttributeTailwindCssClass($player['mapped']['aggression']) }} rounded-2">{{ $player['mapped']['aggression'] }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="">
                                        <div>Pace</div>
                                        <div class="flex flex-row text-xs">
                                            <div class="basis-3/4">Acceleration</div>
                                            <div class="basis-1/4">
                                                <div class="text-xs font-semibold inline-block font-mono whitespace-nowrap mb-1.5 px-2 py-1 rounded text-white {{ \App\Helpers\PlayerAttributesHelper::getAttributeTailwindCssClass($player['mapped']['acceleration']) }} rounded-2">{{ $player['mapped']['acceleration'] }}</div>
                                            </div>
                                        </div>
                                        <div class="flex flex-row text-xs">
                                            <div class="basis-3/4">Sprint Speed</div>
                                            <div class="basis-1/4">
                                                <div class="text-xs font-semibold inline-block font-mono whitespace-nowrap mb-1.5 px-2 py-1 rounded text-white {{ \App\Helpers\PlayerAttributesHelper::getAttributeTailwindCssClass($player['mapped']['sprint_speed']) }} rounded-2">{{ $player['mapped']['sprint_speed'] }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="">
                                        <div>Goalkeeping</div>
                                        <div class="flex flex-row text-xs">
                                            <div class="basis-3/4">GK Diving</div>
                                            <div class="basis-1/4">
                                                <div class="text-xs font-semibold inline-block font-mono whitespace-nowrap mb-1.5 px-2 py-1 rounded text-white {{ \App\Helpers\PlayerAttributesHelper::getAttributeTailwindCssClass($player['mapped']['gk_diving']) }} rounded-2">{{ $player['mapped']['gk_diving'] }}</div>
                                            </div>
                                        </div>
                                        <div class="flex flex-row text-xs">
                                            <div class="basis-3/4">GK Handling</div>
                                            <div class="basis-1/4">
                                                <div class="text-xs font-semibold inline-block font-mono whitespace-nowrap mb-1.5 px-2 py-1 rounded text-white {{ \App\Helpers\PlayerAttributesHelper::getAttributeTailwindCssClass($player['mapped']['gk_handling']) }} rounded-2">{{ $player['mapped']['gk_handling'] }}</div>
                                            </div>
                                        </div>
                                        <div class="flex flex-row text-xs">
                                            <div class="basis-3/4">GK Kicking</div>
                                            <div class="basis-1/4">
                                                <div class="text-xs font-semibold inline-block font-mono whitespace-nowrap mb-1.5 px-2 py-1 rounded text-white {{ \App\Helpers\PlayerAttributesHelper::getAttributeTailwindCssClass($player['mapped']['gk_kicking']) }} rounded-2">{{ $player['mapped']['gk_kicking'] }}</div>
                                            </div>
                                        </div>
                                        <div class="flex flex-row text-xs">
                                            <div class="basis-3/4">GK Reflexes</div>
                                            <div class="basis-1/4">
                                                <div class="text-xs font-semibold inline-block font-mono whitespace-nowrap mb-1.5 px-2 py-1 rounded text-white {{ \App\Helpers\PlayerAttributesHelper::getAttributeTailwindCssClass($player['mapped']['gk_reflexes']) }} rounded-2">{{ $player['mapped']['gk_reflexes'] }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </x-bladewind.table>
                        </x-bladewind.tab-content>
                        @endforeach
                    </x-bladewind.tab-body>
                </x-bladewind.tab-group>

            </div>
        </div>
    </div>

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

@include('js.highcharts')

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
        max-width: 100%;
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

    .highcharts-data-table th.highcharts-text[scope="col"] {
        background-color: black;
    }

    .highcharts-data-table thead tr,
    .highcharts-data-table tr:nth-child(even) {
        background-color: #9ca3af;
    }

    .highcharts-data-table tr:hover th.highcharts-text[scope="col"] {
        cursor: pointer;
    }

    .highcharts-data-table th.highcharts-text[scope="col"] {
        color: lightslategrey;
        font-weight: bolder;
    }
</style>
<script type="text/javascript">
    const data = <?php echo json_encode($chartData)?>;
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
            min: 0,
            max: 99
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

        exporting: {
            showTable: true,
            tableCaption: false,
            enabled: false
        },
    });

    const series = data.players;
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
