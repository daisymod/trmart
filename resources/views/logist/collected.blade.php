@extends("body")
@section("title", "Покупки")
@section("content")
    <div class="personal-area">
        <div class="wrapper">
            @include("customer_top")
            <div class="customer-panel customer-orders__wrap">
                <div class="customer-orders__header">
                    <div class="orders__header_title">Форма 103</div>
                </div>
                @if(count($data))
                    <div class="customer-orders__body">
                        <div class="table-scroll-wrap">
                            <table cellspacing="1" class="merchant-goods-table">
                                <thead>
                                <tr>
                                    <th style="width: 5%">Ид</th>
                                    <th style="width: 30%">Название</th>
                                    <th style="width: 30%">Дата создание</th>
                                    <th style="width: 5%; text-align: center">Действие</th>
                                </tr>
                                </thead>
                                <tbody id="table-body">
                                @foreach($data as $item)
                                    <tr>
                                        <td><span><a href="{{route('merchant.order',['id'=> $item->id  ])}}">{{ $item->id  }}</a></span></td>
                                        <td><span>{{ $item->id.'-'.\Carbon\Carbon::parse($item->created_at)->format('d.m.Y') }}</span></td>
                                        <td><span>{{ $item->created_at }}</span></td>
                                        <td style="text-align: center">
                                            <a style="color: #37c155;" href="{{ route('logist.collected.item', ['id' => $item->id]) }}">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <div>Нет Ф103</div>
                @endif
            </div>
        </div>
    </div>
@endsection
