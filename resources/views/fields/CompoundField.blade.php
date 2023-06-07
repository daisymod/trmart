@extends("fields.LanguageBasicField")
@section("content")
<div style="width: 100%" class="form-group">
    <table style="width: 1000px" class="table table-bordered" id="dynamicAddRemove">
        <tr>
            <th>@lang('products.squad')</th>
            <th>%</th>
            <th></th>
        </tr>
        @if(count($compound) > 0)
            @php $index = 0; @endphp
            @foreach($compound as $item)
                <tr>
                    <td width="300px">
                        @foreach($langList as $k=>$i)
                            <div class="@if($k == $lang) active @endif form-lang-box form-lang-box-{{ $k }}">
                                <input type="text" name="compound[{{$k}}][{{$index}}]" value="{{$item->{'name_'.$k} }}" class="form-control" />
                                <datalist id="{{ $class->field }}">
                                    @foreach($class->datalist as $k=>$i)
                                        <option>{{$i}}</option>
                                    @endforeach
                                </datalist>
                            </div>
                        @endforeach
                    </td>
                    <td width="100px"><input type="number" min="0" max="100" name="percent[{{$index}}]"  value="{{$item->percent}}" class="form-control" /></td>
                    @if($index == 0)
                    <td><button  type="button" name="add"  style="width: 200px  !important;" id="add-btn" class="btn green-btn">@lang('system.y1')</button></td>
                    @else
                        <td><button type="button" onclick="$(this).parents('tr').remove()" class="btn red-btn remove-tr">-</button></td>
                    @endif
                </tr>
                @php $index++; @endphp
            @endforeach

        @else
            <tr>
                <td width="300px">
                    @foreach($langList as $k=>$i)
                        <div class="@if($k == $lang) active @endif form-lang-box form-lang-box-{{ $k }}">
                            <input type="text" name="compound[{{ $k }}][0]"  class="form-control" />
                            <datalist id="{{ $class->field }}">
                                @foreach($class->datalist as $k=>$i)
                                    <option>{{$i}}</option>
                                @endforeach
                            </datalist>
                        </div>
                    @endforeach
                </td>
                <td width="100px"><input type="number" min="0" max="100" name="percent[0]"  class="form-control" /></td>
                <td><button type="button" name="add" id="add-btn" style="width: 200px !important;" class="btn green-btn">@lang('system.y1')</button></td>
            </tr>
        @endif


    </table>

</div>
@endsection
<script src="https://code.jquery.com/jquery-3.6.3.js"></script>
<script>
    function removeTr() {
        $(this).parents('tr').remove();
    }
</script>

<style>

   #add_more_fields {
        font-family: 'Noto Sans';
        font-style: normal;
        font-weight: 400;
        font-size: 13px;
        line-height: 18px;
        color: #37C155;
    }

</style>
