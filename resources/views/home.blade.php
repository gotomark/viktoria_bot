@extends('layouts.admin')

@section('content')
    <body data-spy="scroll" data-target="#navSection" data-offset="100">
    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container" id="container">

        <div class="overlay"></div>
        <div class="search-overlay"></div>

    @include('sidebar')

        <!--  BEGIN CONTENT AREA  -->
        <div id="content" class="main-content">
            <div class="container-fluid">
                <div class="container-fluid">
                    <div class="row layout-top-spacing">
                        <div id="basic" class="col-lg-12 layout-spacing">
                            <div class="statbox widget box box-shadow">
                                <div class="statbox widget box box-shadow">
                                    <div class="widget-header">
                                        <div class="row">
                                            <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                                <h4>Hi! {{auth()->user()->name}}</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="widget-content widget-content-area">

                                        <div class="row">
                                            <div class="col-lg-6 col-12 mx-auto">
                                                <form method="POST" action="{{route('user.update.token')}}">
                                                    @csrf
                                                    <div class="form-group">
                                                        <p>Bot Token (autoset webhook)</p>
                                                        <input id="t-text" type="text" name="telegram_token" value="{{auth()->user()->telegram_token}}" placeholder="Token Bot" class="form-control" required="">
                                                        <input type="submit" value ="Save" name="txt" class="mt-4 btn btn-primary">
                                                    </div>
                                                </form>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if(auth()->user()->telegram_token)
                    <form name="sync" action="{{route('user.update.sync-channels')}}" method="POST">
                        @csrf
                        <div class="row layout-top-spacing" id="cancel-row">
                            <div class="widget-header col-lg-12 hideall">
                                <div class="row">
                                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                        <h5>To <-- From</h5>
                                    </div>
                                </div>
                            </div>
                            @foreach($responseData as $id=>$data)
                                @if($data)
                                <div id="fs2Basic-{{$loop->index}}" class="col-lg-12 layout-spacing row-sync">
                                    <div class="statbox widget box box-shadow">
                                        <div class="widget-header col-lg-12">
                                            <div class="row">
                                                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                                    <span class="remove-sync" style="color:#ff7777">Remove</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="widget-content widget-content-area">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <select class="form-control basic" name="data[{{$loop->index}}][to_channels]">
                                                        @foreach($channelsUser as $channel)
                                                            <option value="{{$channel->id}}" @if($channel->id == $id)selected="selected" @endif>{{$channel->title}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-lg-6">
                                                    <select class="form-control tagging" multiple="multiple" name="data[{{$loop->index}}][from_channels][]">
                                                        @foreach($headChannels as $channel)
                                                            <option value="{{$channel->id}}" @if (in_array($channel->id,$data)) selected="selected" @endif>{{$channel->title}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            @endforeach
                            <div class="row-sync"></div>
                        </div>
                        <button class="btn btn-primary" id="add_row" type="button">Add Sync Row</button>
                        <button class="btn btn-primary" type="submit">Save</button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        <!--  END CONTENT AREA  -->
    </div>
    <!-- END MAIN CONTAINER -->

    <script>
        function add_row(index) {
            var html = '';
            html += '<div id="fs2Basic-'+index+'" class="col-lg-12 layout-spacing row-sync">';

            html += '<div class="statbox widget box box-shadow">';
            html += '<div class="widget-header col-lg-12">';
            html += '<div class="row">';
            html += '<div class="col-xl-12 col-md-12 col-sm-12 col-12">';
            html += '<span class="remove-sync" style="color:#ff7777">Remove</span>';
            html += '</div>';
            html += '</div>';
            html += '</div>';
            html += '<div class="widget-content widget-content-area">';
            html += '<div class="row">';
            html += '<div class="col-lg-6">';
            html += '<select class="form-control basic" name="data['+index+'][to_channels]">';
            @foreach($channelsUser as $channel)
                html += '<option value="{{$channel->id}}">{{$channel->title}}</option>';
            @endforeach
                html += '</select>';
            html += '</div>';

            html += '<div class="col-lg-6">';
            html += '<select class="form-control tagging" multiple="multiple" name="data['+index+'][from_channels][]">';
            @foreach($headChannels as $channel)
                html += '<option value="{{$channel->id}}" >{{$channel->title}}</option>';
            @endforeach
                html += '</select>';
            html += '</div>';
            html += '</div>';

            html += '</div>';
            html += '</div>';

            html += '</div>';
        return html;
        }

    </script>
    </body>
@endsection
