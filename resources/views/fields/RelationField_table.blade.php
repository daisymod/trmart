@yield("table")
<div class="relation-save-pagination">
    <div class="pagination-wrap">
        {{--    {{ $records->appends(request()->all())->links() }}--}}
        @for($i = 1; $i <= $records->lastPage(); $i++)
            <div class="page-item @if($i ==  $records->currentPage()) active @endif relation-set-page" data-page="{{$i}}">
                <span class="page-link">{{$i}}</span>
            </div>
        @endfor
    </div>
    <div class="relation-save-box">
        <div class="btn btn-primary btn-sm relation-save">@lang('system.save')</div>
    </div>

</div>
