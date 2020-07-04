@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="float-left ml-2">
                        <h5>Dashboard</h5>
                    </div>
                    <div class="float-right ml-2">
                        <a href="{{route('register')}}" class="btn btn-primary btn-block"> Register New Controller</a>
                    </div>

                    <div class="float-right ml-2">
                        <a href="{{route('home_page')}}" class="btn btn-primary btn-block" target="_blank"> Go to website</a>
                    </div>

                    <div class="float-right">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">Upload</button>
                    </div>
                </div>

                @if(session('error') || session('success'))
                    <div class="alert  @if(session('error')) alert-warning @else alert-success @endif  alert-dismissible fade show" role="alert">
                        @if(session('error')) {{session('error')}} @else {{session('success')}} @endif
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <div class="card-body">

                    <table class="table table-striped" id="data-table">
                        <thead class="">
                        <tr>
                            <th scope="col">Updated time</th>
                            <th scope="col">Who updated</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($historyInfo as $info)
                            <tr>
                                <td> {{date('Y M d H:i', strtotime($info->created_at))}}</td>
                                <td> {{$info->user->name}}</td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Upload Covid-19 data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('upload-data-admin')}}" method="post" enctype="multipart/form-data">
            <div class="modal-body">

                    @csrf
                    <div class="form-group">

                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="data-file-covid"name="data-file" required>
                            <label class="custom-file-label" for="customFile">Choose file</label>
                        </div>

                    </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Upload</button>
            </div>
            </form>
        </div>
    </div>
</div>


    <script type="text/javascript">

        $('#data-file-covid').change(function(e){
            var fileName = e.target.files[0].name;
            $('.custom-file-label').html(fileName);
        });

        $(document).ready( function () {
            $('#data-table').DataTable({
                "order": [[ 0, "desc" ]],

            });
        });

    </script>

@endsection
