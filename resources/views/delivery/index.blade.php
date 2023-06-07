@extends("body")
@section("title", "Доставка")
@section("content")
    <div class="container">
        <div class="card">
            <div class="card-header">@lang('system.currency')<div class="menu">@include("menu")</div></div>
            <div class="card-body table-scroll-wrap">
                <table class="table table-striped table-hover table-bordered">
                    <thead>
                    <tr>
                        <th>Страна доставки</th>
                        <th>кг</th>
                        <th>гр</th>
                        <th>Стоимость 1 кг</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($items as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->kg_price }} тг</td>
                        <td>{{ $item->gr_price }} тг</td>
                        <td>
                            <form method="GET" action="{{ route("delivery.save") }}">
                                <input type="number" name="d_sum" value="{{ $item->kg_price }}">
                                <input type="hidden" name="id" value="{{ $item->id }}">
                                <input type="submit" value="Сохранить">
                            </form>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
