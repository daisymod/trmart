<form class="row mb-3">
    @foreach($form as $k=>$i)
        {!! $i !!}
    @endforeach
{{--    <div class="col-md-2">
        <label class="col-form-label">Сортировать по</label>
        <select name="sort_by" class="form-control">
            @foreach ($sortBy as $k=>$i)
                <option value="{{ $k }}" @if($k === request("sort_by")) selected @endif >{{ $i }}</option>
            @endforeach
        </select>
    </div>--}}

    <div class="col-md-2">
        <label class="col-form-label">&nbsp;</label>
        <div class="btn btn-primary btn-block relation-find">
            @lang('item.newSet')
        </div>
    </div>
    <div class="col-md-2">
        <label class="col-form-label">&nbsp;</label>
        <div class="btn btn-link btn-block relation-reset">
            @lang('system.cancel')
        </div>
    </div>
</form>
