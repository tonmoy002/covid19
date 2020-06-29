@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="text-center"> <h3> COVID-19 USER INTERFACE </h3></div>
        <div class="text-center"> <h5> Total Cases : <span id="total_cases">{{$summary['total_cases']}} </span></h5></div>
        <div class="text-center"> <h5> Total Deaths : <span id="total_death" class="text-danger">{{$summary['total_death']}} </span></h5></div>


        <br/>
        <br/>
        <h4> Cases in all country :</h4>

        <table class="table table-striped" id="data-table">
            <thead>
            <tr>
                <th scope="col">Country</th>
                <th scope="col">New cases</th>
                <th scope="col">Total cases</th>
                <th scope="col">New death</th>
                <th scope="col">Total death</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>

            </tbody>
        </table>

    </div>
@endsection


@section('push-script')

    <script type="text/javascript">

        var routeLinkCovid19ByCountry       =   "";

        $(document).ready(function() {

            $('#data-table').DataTable( {
                "paging":   false,
                "processing": true,
                "serverSide": true,
                "order": [[ 2, "desc" ]],
                "ajax": {
                    "url": "{{route('covid-19-all-data')}}",
                    "type": "POST",
                    "dataType" : "json"
                },
                "columns": [
                    { "data": "name",
                        "render": function ( data, type, row, meta ) {

                            var url     = '{{url("covid19")}}' + '/' +data;
                            return '<a href="'+url+'">'+data+'</a>';
                        }

                    },
                    { "data": "new_cases" },
                    { "data": "cumulative_cases" },
                    { "data": "new_death" },
                    { "data": "cumulative_death" }
                ]
            });
            //getAllCovid19Data();

        });

        function getAllCovid19Data() {


            $.ajax({
                beforeSend: function(){

                },
                success: function(data){

                    var list = data.list;


                },
                type: 'POST',
                url: '{{route('covid-19-all-data')}}',
                data: {

                },
                cache: false,
                dataType: 'json'
            });
        }


    </script>


@endsection
