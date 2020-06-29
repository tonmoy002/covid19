@extends('layouts.app')


@section('content')

    <style>

        .highcharts-credits{
            display: none;
        }
    </style>

    <div class="container">

        <div class="text-center"> <h3> Summary Information of {{$countryName}} </h3></div>
        <div class="text-center"> <h5> Total Cases : <span id="total_cases">{{$summary['total_cases']}} </span></h5></div>
        <div class="text-center"> <h5> Total Deaths : <span id="total_death" class="text-danger">{{$summary['total_death']}} </span></h5></div>

        <div class="row">

            <div class="col-md-6">
                <figure class="highcharts-figure-pie-death">
                    <div id="highcharts-figure-pie-death"></div>
                </figure>
            </div>

            <div class="col-md-6">
                <figure class="highcharts-figure-pie-death">
                    <div id="highcharts-figure-pie-totalcases"></div>
                </figure>
            </div>

        </div>

        <figure class="highcharts-figure">
            <div id="container"></div>
        </figure>





    </div>
@endsection


@section('push-script')

    <script src="https://code.highcharts.com/highcharts.js"></script>

    <script type="text/javascript">


        $(document).ready(function() {

            var dates = [];
            var cumulativeCases = [];
            var cumulativedeath = [];

            var deathInRegion   = [];
            var casesInRegion   = [];

            var country =      @json($countryName);

            getAllCovid19DataForGraph();

            function getAllCovid19DataForGraph() {

                $.ajax({
                    beforeSend: function () {

                    },
                    success: function (data) {

                        dates = data.dates;
                        cumulativeCases = data.cumulative_cases;
                        cumulativedeath = data.cumulative_death;
                        deathInRegion   = data.death_region;
                        casesInRegion   = data.cases_region;

                        initialiseLineChart();
                        initialisePieChartDeath();
                        initialisePieChartTotalCase();

                    },
                    type: 'POST',
                    url: '{{route('covid19.map.data.by.country')}}',
                    data: {

                        'country_name': country

                    },
                    cache: false,
                    dataType: 'json'
                });
            }

            var initialiseLineChart = function () {
                var chart = new Highcharts.chart('container', {

                    title: {
                        text: 'Accrual Graph'
                    },

                    yAxis: {
                        title: {
                            text: 'Number of Patients'
                        }
                    },

                    xAxis: {
                        categories: dates
                    },

                    legend: {
                        layout: 'vertical',
                        align: 'right',
                        verticalAlign: 'middle'
                    },

                    plotOptions: {
                        series: {
                            label: {
                                connectorAllowed: false
                            }
                        }
                    },

                    series: [{
                        name: 'Total cases',
                        data: cumulativeCases
                    }, {
                        name: 'Total death',
                        data: cumulativedeath
                    }],

                    responsive: {
                        rules: [{
                            condition: {
                                maxWidth: 500
                            },
                            chartOptions: {
                                legend: {
                                    layout: 'horizontal',
                                    align: 'center',
                                    verticalAlign: 'bottom'
                                }
                            }
                        }]
                    }

                });
            };


            var initialisePieChartDeath = function () {

                var chart = new Highcharts.chart('highcharts-figure-pie-death', {

                    chart: {
                        styledMode: true
                    },

                    title: {
                        text: 'Death Ratio in '+ country + ' Region'
                    },

                    tooltip: {
                        headerFormat: '<span style="font-size:11px">{point.name}</span>',
                        pointFormat: '<span style="color:{point.color}">{point.name}</span>: Total death : {point.y:.2f}<br/>'
                    },
                    series: [{
                        type: 'pie',
                        allowPointSelect: true,
                        keys: ['name', 'y', 'selected', 'sliced'],
                        data: deathInRegion,
                        showInLegend: true
                    }]
                });
            }

            var initialisePieChartTotalCase = function () {

                var chart = new Highcharts.chart('highcharts-figure-pie-totalcases', {

                    chart: {
                        styledMode: true
                    },

                    title: {
                        text: 'Cases Ratio in '+ country + ' Region'
                    },

                    tooltip: {
                        headerFormat: '<span style="font-size:11px">{point.name}</span>',
                        pointFormat: '<span style="color:{point.color}">{point.name}</span>: Total cases : {point.y:.2f}<br/>'
                    },
                    series: [{
                        type: 'pie',
                        allowPointSelect: true,
                        keys: ['name', 'y', 'selected', 'sliced'],
                        data: casesInRegion,
                        showInLegend: true
                    }]
                });
            }
        });



    </script>


@endsection
