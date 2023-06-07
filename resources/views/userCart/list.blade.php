@extends("body")
@section("title", "User Cart")
@section("content")
    <div class="container">
        <div class="card">
            <div class="card-header"><div class="menu">@include("menu")</div></div>

            <div class="row">
                <label for="name">
                    @lang('system.mySearch')
                    <input style="border: 1px solid #000000; border-radius: 4px;" id="search-fields" name="search" type="text">
                </label>

            </div>


            <form method="GET" action="{{ route("userCart.list") }}">
                <div>
                    <div class="d-flex w-100 justify-content-between mb-5 mt-5" style="margin-bottom: 100px ;margin-top: 20px;display: flex; justify-content: space-between; width: 50%">
                        <label for="from">
                            @lang('system.q1')
                            @if( isset($_GET['from']))
                                <input type="date" name="from" class="form-control" value="{{\Carbon\Carbon::parse($_GET['from'])->format('Y-m-d')}}">
                            @else
                                <input type="date" name="from" class="form-control" value="{{ \Carbon\Carbon::now()->subDays(7)->format('Y-m-d')}}">
                            @endif
                        </label>
                        <label for="to">
                            @lang('system.q2')
                            @if( isset($_GET['to']))
                                <input type="date" name="to" class="form-control" value="{{\Carbon\Carbon::parse($_GET['to'])->format('Y-m-d')}}">
                            @else
                                <input type="date" name="to" class="form-control" value="{{ \Carbon\Carbon::now()->format('Y-m-d')}}">
                            @endif
                        </label>
                    </div>

                    <button class="green-btn product-btn1">@lang('system.find') </button>
                </div>
            </form>

            <div class="card-body table-scroll-wrap">
                <table id="myTable" class="table table-striped table-hover table-bordered">
                    <thead>
                    <tr>
                        <th>@lang('system.b1')</th>
                        <th>@lang('system.b2')</th>
                        <th>@lang('system.b3')</th>
                        <th></th>
                        @if(Auth::user()->role =='admin')
                            <th>@lang('system.z1')</th>
                        @endif
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($records as $k=>$i)
                        <tr>
                            <td>{{ $i->user->name ?? ''  }} {{ $i->user->surname ?? ''  }}</td>
                            <td>{{ $i->count }}</td>
                            <td>{{ $i->price }}</td>
                            <th>{{ \Carbon\Carbon::parse($i->created_at)->format('Y-m-d H:i:s') }}</th>
                            <th>
                                @if($i->is_checked == 1)
                                    Yes
                                @else
                                    No
                                @endif
                            </th>

                            <td class="controls">
                                @if($i->is_checked == 0)
                                    <form method="POST" action="{{ route("userCart.update", $i->id) }}">
                                        @csrf
                                        <input type="submit" style="cursor: pointer" class="btn btn-green" value="@lang('system.z2')">
                                    </form>
                                @endif
                               <a href="{{ route("userCart.edit", $i->id) }}"><i class="fas fa-edit"></i></a>

                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
@endsection
