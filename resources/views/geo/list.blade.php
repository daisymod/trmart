@extends("body")
@section("title", "Каталог")
@section("content")
    <div class="personal-area">
        <div class="wrapper">
            <div class="top">
                <p>@lang('system.lk')</p>
                <ul class="main-menu">
                    @include("menu")
                </ul>
            </div>
            <div class="category-tree">
                <h4>@lang('system.c')</h4>
                @can("country-update-excel-load")
                    <h5>Гео лист Казахстана</h5>
                    <form class="load-form-file-form" method="POST" action="{{ route("geo.excel_load") }}" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <input type="file" required name="file" accept=".xlsx">
                        <button class="green-btn product-btn1">@lang('system.loadTable') </button>
                    </form>
                    <br>
                @endcan
                @can("country-update-excel-load")
                <h5>Гео лист Турции</h5>
                <form class="load-form-file-form" method="POST" action="{{ route("geo.tr_excel_load") }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="file" required name="file" accept=".xlsx">
                    <button class="green-btn product-btn1">@lang('system.loadTable') </button>
                </form>
                <br>
                @endcan
                <h5>Гео лист Общий</h5>
                <a href="{{ route("geo.create") }}"><i  class="fa-solid fa-plus"></i></a>
                @foreach($records as $k=>$i)
                    <div class="add-item-wrap" style="display: flex">
                        <button class="add-item">
                            {{ app()->getLocale() == 'ru' ? $i->{"name_".app()->getLocale()}  : $i->name_en }}
                            <span class="plus">+</span>
                        </button>
                        <span style="margin-left: 10px" class="edit"><a href="{{ route("city.create", $i->id) }}"><i  class="fa-solid fa-plus"></i></a></span
                        <span style="margin-left: 10px" class="edit"><a href="{{ route("geo.edit", $i->id) }}"><i  class="fa-solid fa-pen"></i></a></span>

                        <div class="add-item-content">
                            @foreach($i->city as $child)
                                <a href="{{ route("city.edit", $child->id) }}">{{ app()->getLocale() == 'ru' ? $child->{"name_".app()->getLocale()} : $child->name_en }}</a>
                            @endforeach
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>

@endsection
