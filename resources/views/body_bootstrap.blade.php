<!doctype html>
<html lang="{{ App::currentLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="format-detection" content="telephone=no">
    <meta name="author" content="@NBaskoff">
    <meta name="generator" content="Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})">
    <title>@yield('title') — TurkiyeMart </title>

    {{--    <!-- Favicons -->
        <link rel="apple-touch-icon" href="/docs/5.0/assets/img/favicons/apple-touch-icon.png" sizes="180x180">
        <link rel="icon" href="/docs/5.0/assets/img/favicons/favicon-32x32.png" sizes="32x32" type="image/png">
        <link rel="icon" href="/docs/5.0/assets/img/favicons/favicon-16x16.png" sizes="16x16" type="image/png">
        <link rel="manifest" href="/docs/5.0/assets/img/favicons/manifest.json">
        <link rel="mask-icon" href="/docs/5.0/assets/img/favicons/safari-pinned-tab.svg" color="#7952b3">
        <link rel="icon" href="/docs/5.0/assets/img/favicons/favicon.ico">
        <meta name="theme-color" content="#7952b3">--}}

    <link href="{{ mix("/css/app.css") }}" rel="stylesheet">
</head>
<body>

<div id="image-model-dialog" class="model-dialog">
    <div class="modal-box">
        <div class="container" id="modalCropper">
            <div class="modal-title">
                Редактирование изображения
                <div class="modal-close"><i class="fas fa-window-close"></i></div>
            </div>
            <div class="modal-body">
                <div class="modal-image">
                    <img id="image">
                </div>
            </div>
            <div class="modal-footer">
                <div class="docs-buttons">
                    <div class="btn-group">
                        <div class="btn btn-primary zoom-in">
                            <i class="fa fa-search-plus"></i>
                        </div>
                        <div class="btn btn-primary zoom-out">
                            <i class="fa fa-search-minus"></i>
                        </div>

                        <div class="btn btn-primary move-left">
                            <i class="fa fa-arrow-left"></i>
                        </div>
                        <div class="btn btn-primary move-right">
                            <i class="fa fa-arrow-right"></i>
                        </div>
                        <div class="btn btn-primary move-up">
                            <i class="fa fa-arrow-up"></i>
                        </div>
                        <div class="btn btn-primary move-down">
                            <i class="fa fa-arrow-down"></i>
                        </div>

                        <div class="btn btn-primary rotate-left">
                            <i class="fa fa-undo-alt"></i>
                        </div>
                        <div class="btn btn-primary rotate-right">
                            <i class="fa fa-redo-alt"></i>
                        </div>
                    </div>
                    {{--<div class="btn-group">
                        <div class="btn btn-primary ">
                            <i class="fa fa-arrows-alt-h"></i>
                        </div>
                        <div class="btn btn-primary">
                            <i class="fa fa-arrows-alt-v"></i>
                        </div>
                    </div>--}}
                    <div class="btn btn-primary btn-block save">
                        <i class="fa fa-save"></i> Сохранить
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">TurkiyeMart</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="{{ route("index") }}">{{ __("menu.index") }}</a>
                    </li>
                    @can("user-list")
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route("user.list") }}">{{ __("menu.user.list") }}</a>
                        </li>
                    @endcan
                    @can("merchant-list")
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route("merchant.list") }}">{{ __("menu.merchant.list") }}</a>
                        </li>
                    @endcan
                    @can("admin-list")
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route("admin.list") }}">{{ __("menu.admin.list") }}</a>
                        </li>
                    @endcan
                    @can("news-list")
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route("news.list") }}">{{ __("menu.news.list") }}</a>
                        </li>
                    @endcan
                    @can("slider-list")
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route("slider.list") }}">{{ __("menu.slider.list") }}</a>
                        </li>
                    @endcan
                    @can("catalog-list")
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route("catalog.list", 0) }}">{{ __("menu.catalog.list") }}</a>
                        </li>
                    @endcan
                    @can("catalog-item-list")
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route("catalog_item.list", 0) }}">{{ __("menu.catalog_item.list") }}</a>
                        </li>
                    @endcan
                    @can("shipping-method-list")
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route("shipping_method.list", 0) }}">{{ __("menu.shipping_method.list") }}</a>
                        </li>
                    @endcan
                    @can("page-list")
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route("page.list") }}">{{ __("menu.page.list") }}</a>
                        </li>
                    @endcan
                    @can("currency-list")
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route("currency.list") }}">{{ __("menu.currency.list") }}</a>
                        </li>
                    @endcan
                    @can("catalog-item-stock-list")
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route("catalog_item.stock.list") }}">{{ __("menu.catalog_item.stock") }}</a>
                        </li>
                    @endcan
                </ul>
                <div class="form-inline my-2 my-lg-0">

                    <span style="margin-right: 10px;">
                        <a href="{{ route("lang", "ru") }}">RU</a> <a href="{{ route("lang", "tr") }}">TR</a>
                    </span>
                    @auth
                        <span style="margin-right: 10px;">
                            <a href="{{ route("user.lk") }}">
                                {{ __("menu.goodDay") }}{{ Auth::user()->name }}.
                            </a>
                        </span>
                        <a href="{{ route("user.exit") }}" class="btn btn-primary">
                            <i class="fas fa-sign-out-alt"></i> {{ __("menu.user.exit") }}
                        </a>
                    @else
                        <span style="margin-right: 10px;"><a href="{{ route("merchant.reg") }}">{{ __("menu.merchant.reg") }}</a></span>
                        <span style="margin-right: 10px;"><a href="{{ route("user.reg") }}">{{ __("menu.user.reg") }}</a></span>
                        <a href="{{ route("user.login") }}" class="btn btn-primary">
                            <i class="fa-solid fa-right-to-bracket"></i> {{ __("menu.user.login") }}
                        </a>
                    @endauth

                </div>
            </div>
        </div>
    </nav>
</div>

@yield("content")

<script src="{{ mix("/js/app.js") }}"></script>

</body>
</html>
