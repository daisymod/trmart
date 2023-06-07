<div class="form-group">
    @foreach($langList as $k=>$i)
        <div class="@if($k == $lang) active @endif form-lang-box form-lang-box-{{ $k }}">
            <div style="display: flex">
                <input type="text" name="size">

            </div>
        </div>
    @endforeach
</div>
