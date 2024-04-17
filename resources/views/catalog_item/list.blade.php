@extends("body")
@section("title", "Товары")
@section("content")

    <div class="personal-area">
        <div class="wrapper">
            <div class="top">
                <p>@lang('menu.lk') @endlang</p>
                <ul class="main-menu">
                    @include("menu")
                </ul>
            </div>
            <div class="merchant-wrap pb25">
                <h4>@lang('item.cartItem') <span> @lang('item.items')</span></h4>
                <p>@lang('item.createdItem')</p>
                <div class="product-style-wrap">
                    <div class="product-style-block">
                        <img src="/img/info-icon.svg" alt="">
                        <p>@lang('system.warning-10')</p>
                    </div>
                    <div class="product-style-block">
                        <img src="/img/info-icon.svg" alt="">
                        <p>@lang('item.createdItemWord')
                            <a href="#">@lang('cabinet.instruction')</a></p>
                    </div>
                </div>
                <div class="table-top merchant-goods-table-top">
                    <div class="buttons-1" style="display: flex;width: 700px">
                        @can("catalog-item-add")
                            <div class="d-flex flex-wrap" style="flex-wrap: wrap;width: 233px">
                                <a href="{{ route("catalog_item.add") }}" class="green-btn product-btn1">@lang('system.addProduct')</a>
                            </div>
                        @endcan

                        @can("catalog-item-excel-load")
                                @if(empty($job->id))
                                    <div class="d-flex flex-wrap" style="flex-wrap: wrap;width: 233px;">
                                        <div class="custom-file-upload" style="display: flex;justify-content: flex-start;flex-wrap: wrap;">
                                            <input type="file" name="file" id="fileUpload" accept=".xlsx" />
                                        </div>
                                        <button id="upload" class="green-btn product-btn1">@lang('system.loadTable') </button>
                                    </div>
                                @endif



                        @endcan
                            <div class="d-flex flex-wrap" style="flex-wrap: wrap;width: 233px">
                                <span style="width: 135px; margin: 18px 0px;display: flex;justify-content: center;">@lang('system.exportTable') </span>
                                <form class="load-form-file-form" method="POST" action="{{ route("catalog_item.export") }}" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    <input type="hidden" readonly name="category_id" id="category_id_form">
                                    <input type="hidden" readonly name="user_id" value="{{\Illuminate\Support\Facades\Auth::user()->id}}" >
                                    <button class="green-btn product-btn1">@lang('system.exportTable') </button>
                                </form>
                            </div>

                    </div>


                    <form method="GET" action="{{route('catalog_item.list')}}" class="mini-search-form">
                        <input type="hidden" id="limit-catalog-item" name="limit" value="{{$_GET['limit'] ?? 100}}">
                        @foreach($form as $k=>$i)
                            {!! $i !!}
                        @endforeach
                        <div class="form-group">
                            <label class="col-form-label">@lang('system.sortBy')</label>
                            <select name="sort_by" class="form-control">
                                @foreach ($sortBy as $k=>$i)
                                    <option value="{{ $k }}" @if($k === request("sort_by")) selected @endif >{{ $i }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="col-form-label">&nbsp;</label>
                            <button class="btn btn-primary">
                                @lang('system.takeChange')
                            </button>
                        </div>

                        @if(\Illuminate\Support\Facades\Auth::user()->role == 'admin')
                            <div class="form-group">
                                <input type="submit" name="clear" class="btn btn-primary" value=" @lang('system.clear')">
                            </div>
                        @endif
                    </form>
                </div>
                <form method="POST">
                    @csrf
                    <div class="bottom-line">
                        <div class="delete-products">
                            <div class="select-wrap dark">
                                <select name="action" id="">
                                    <option value="verification">@lang('item.verifyItem')</option>
                                    <option value="active_y">@lang('item.sellItem')</option>
                                    <option value="active_n">@lang('item.notActive')</option>
                                    @if(\Illuminate\Support\Facades\Auth::user()->role == "admin")
                                        <option value="status_2">@lang('item.verifyActive')</option>
                                        <option value="gpt">@lang('item.gpt')</option>
                                    @endif
                                </select>
                            </div>
                            <button type="submit" disabled="" id="button-action-catalog_item" class="gray-btn">@lang('item.newSet')</button>
                        </div>
                        <div class="show-entries">
                            <span>@lang('item.showList')</span>
                            <div class="select-wrap dark">
                                <select id="pagination-list" >
                                    <option   value="100" >@lang('system.i6')  100</option>
                                    <option @if(isset($_GET['limit']) && $_GET['limit'] == 10) selected @endif value="10">@lang('system.i6') 10</option>
                                    <option @if(isset($_GET['limit']) && $_GET['limit'] ==50) selected @endif value="50">@lang('system.i6')  50</option>

                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="table-scroll-wrap">
                        <table cellspacing="1" class="merchant-goods-table">
                            <thead>
                            <tr>
                                <th style="width: 4%">
                                    <input type="checkbox" class="select-all">
                                </th>
                                <th style="width: 5%">@lang('item.photo')</th>
                                <th style="width: 14%">@lang('item.name')</th>
                                <th style="width: 8%">@lang('item.mark')</th>
                                @if(\Illuminate\Support\Facades\Auth::user()->role == 'admin')
                                    <th style="width: 8%">@lang('merchant.form.company_name')</th>
                                @endif
                                <th style="width: 7%">@lang('item.article')</th>
                                <th style="width: 11%">@lang('item.article2')</th>
                                <th style="width: 11%">@lang('item.code')</th>
                                <th style="width: 14%">@lang('system.m22')</th>
                                <th style="width: 10%">@lang('item.count')</th>
                                <th style="width: 10%">@lang('item.status')</th>
                                <th style="width: 9%">@lang('item.created_at')</th>
                                <th style="width: 7%"></th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($records as $k=>$i)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="id-checkbox" name="ids[]" value="{{ $i->id }}">
                                    </td>
                                    <td>
                                        <div class="img-wrap"><img src="{{ $i->image() }}" style="height: 50px;"></div>
                                    </td>
                                    <td>
                                        @can("catalog-item-edit", $i)
                                            <a href="{{ route("catalog_item.edit", $i->id) }}" class="name">{{ $i->lang("name") }}</a>
                                        @endcan
                                    </td>

                                    <td><span>{{ $i->brand }}</span></td>
                                    @if(\Illuminate\Support\Facades\Auth::user()->role == 'admin')
                                    <td><span>{{$i->user->company->company_name ?? ''}}</span></td>
                                    @endif
                                    <td><span>{{ $i->article }}</span></td>
                                    <td><span>{{ $i->catalog->name ?? ""}}</span></td>
                                    <td><span>{{ $i->barcode }}</span></td>
                                    <td>
                                        <div class="size-color">
                                            <span>{{ $i->active }}</span>
                                        </div>
                                    </td>
                                    <td><input type="text" class="table-input" value="{{ $i->stock }}"></td>
                                    <td>
                                        <span class="red-text">{{ \App\Forms\CatalogItemMerchantForm::statusText($i->status) }}</span>
                                    </td>
                                    <td><span>{{ date("d.m.Y", strtotime($i->created_at)) }}</span></td>
                                    <td>
                                        <div class="buttons">
                                            @can("catalog-item-edit", $i)
                                                <a href="{{ route("catalog_item.edit", $i->id) }}" class="arrow"><img src="/img/arrow-right-small.svg" alt=""></a>
                                            @endcan
                                                @if(\Illuminate\Support\Facades\Auth::user()->role == 'admin')
                                                    <a data-question="Удалить {{ $i->name_tr }}?"  href="{{ route("catalog_item.del", $i->id) }}" class="arrow"><i class=" fa fa-trash"></i></a>
                                                @endif

                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>

                        <div class="Pager2">
                            {{ $records->withQueryString()->links( "pagination::bootstrap-4") }}
                        </div>

                    </div>

                </form>
            </div>
            <div class="info-items">
                <a href="{{route('catalog_item.add')}}" class="info-item">
                    <img src="/img/icon1.svg" alt="">
                    <span>@lang('cabinet.add')</span>
                </a>
                <a href="{{route('merchant.orders')}}" class="info-item">
                    <img src="/img/icon2.svg" alt="">
                    <span>@lang('cabinet.MyOrder')</span>
                </a>
                <a href="{{route('merchant.orders')}}" class="info-item">
                    <img src="/img/icon3.svg" alt="">
                    <span>@lang('cabinet.AddPay')</span>
                </a>
            </div>
        </div>
    </div>
@endsection


