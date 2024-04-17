@extends("body")
@section("title", "Мерчанты")
@section("content")
    <div class="personal-area">
        <div class="wrapper">
            @include("merchant_top")
            <div class="customer-panel customer-orders__wrap">
                <div class="customer-orders__header">
                    <div class="orders__header_title">@lang('system.o13')</div>
                </div>
                <div class="customer-orders__body">
                    <div class="table-scroll-wrap">
                        <p class="info-text-gray">@lang('system.o14')</p>
                    </div>
                    <div class="table-scroll-wrap">
                        <form class="load-form-file-form" method="POST" action="{{ route("merchant.exportOrders") }}" enctype="multipart/form-data">
                            <div class="d-flex w-100 justify-content-between mb-5 mt-5" style="margin-bottom: 100px ;margin-top: 20px;display: flex; justify-content: space-between; width: 50%">
                                <label for="from">
                                    @lang('system.q1')
                                    <input type="date" name="from" class="form-control" value="{{\Carbon\Carbon::now()->subDays(7)->format('Y-m-d')}}">
                                </label>
                                <label for="from">
                                    @lang('system.q2')
                                    <input type="date" name="to" class="form-control" value="{{\Carbon\Carbon::now()->format('Y-m-d')}}">
                                </label>
                            </div>


                            {{ csrf_field() }}
                            <button class="green-btn product-btn1">@lang('system.o15') </button>
                        </form>
                    </div>
                </div>
            </div>
            @include('customer_bottom')
        </div>
    </div>
@endsection
