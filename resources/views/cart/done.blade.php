@extends("body")
@section("title", "Оплата заказа")
@section("content")
    <div class="container">
        <div class="card">
            <div class="card-header">@lang('cart.payOrder')</div>
            <div class="card-body">
                @lang('cart.payOrderDone')
            </div>
        </div>
    </div>
@endsection
