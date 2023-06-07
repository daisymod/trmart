@extends("body")
@section("title", "Мерчанты")
@section("content")
    <div class="container">
        <div class="card">
            <div class="card-header">@lang('system.m20')<div class="menu">@include("menu")</div></div>
            <div class="card-body table-scroll-wrap">
                <form class="row mb-3 relation-find-box">
                    @foreach($form as $k=>$i)
                        <div style="margin: 0px 5px">
                            {!! $i !!}
                        </div>
                    @endforeach
                    <div class="col-md-2">
                        <label class="col-form-label">@lang('system.sortBy')</label>
                        <select name="sort_by" class="form-control">
                            @foreach ($sortBy as $k=>$i)
                                <option value="{{ $k }}" @if($k === request("sort_by")) selected @endif >{{ $i }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="col-form-label">&nbsp;</label>
                        <button class="btn btn-primary btn-block">
                            @lang('system.takeChange')
                        </button>
                    </div>
                    <div class="col-md-2">
                        <label class="col-form-label">&nbsp;</label>
                        <a href="{{ url()->current() }}" class="btn btn-link btn-block">
                            @lang('system.cancel')
                        </a>
                    </div>
                </form>
                <table class="table table-striped table-hover table-bordered">
                    <thead>
                    <tr>
                        <th>№</th>
                        <th> @lang('system.lk9')</th>
                        <th> @lang('system.lk10')</th>
                        <th> @lang('system.m21')</th>
                        <th> @lang('system.m22')</th>
                        <th>@lang('system.m23')</th>
                        <th>@lang('system.m24')</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($records as $k=>$i)
                        <tr>
                            <td>{{ $i->id }}</td>
                            <td>{{ $i->name }} {{ $i->s_name }}</td>
                            <td>{{ $i->phone }}</td>
                            <td>
                                {{ \App\Forms\MerchantAdminForm::statusText($i->status) }}<br>
                                {!! str_replace("\n", "<br>", $i->status_text) !!}
                            </td>
                            <td>{{ $i->active }}</td>
                            <td>{{ $i->step }}</td>
                            <td>{{ $i->rating }}</td>
                            <td class="controls">
                                @can("merchant-edit", $i)
                                    <a href="{{ route("merchant.edit", $i->id) }}"><i class="fas fa-edit"></i></a>
                                    &nbsp;&nbsp;&nbsp;
                                @endcan
                                <!--@can("merchant-del", $i)
                                        <a data-question="Удалить {{ $i->name }}?" href="{{ route("merchant.del", $i->id) }}"><i class="fas fa-trash"></i></a>
                                    @endcan
                                -->
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
