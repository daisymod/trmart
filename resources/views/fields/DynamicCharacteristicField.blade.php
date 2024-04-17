@extends("fields.LanguageBasicField")
@section("content")
<div style="width: 100%" class="form-group">
    <table class="table table-bordered" id="TableCharacteristic">
        <tr>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        @if((count($characteristic) > 0))
            @php $index = 0; @endphp
            @foreach($characteristic as $values)
                <tr>
                    <td id="characteristic-data">
                    <select name="characteristic[{{$index}}]"  class="form-control">
                            @foreach($data as $item)
                                <option  @if($item->id == $characteristic[$index]->characteristic_id) selected="selected" @endif value="{{$item->id}}">{{$item->{'name_'.app()->getLocale()} }} </option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        @foreach($langList as $k=>$i)
                            <div class="@if($k == $lang) active @endif form-lang-box form-lang-box-{{ $k }}">
                                <input type="text" name="value[{{$k}}][{{$index}}]" value="{{ $values->{'name_'.$k} }}" class="form-control" />
                                <datalist id="{{ $class->field }}">
                                    @foreach($class->datalist as $k=>$i)
                                        <option>{{$i}}</option>
                                    @endforeach
                                </datalist>
                            </div>
                        @endforeach
                    </td>
                    @if($index == 0)
                        <td><button  type="button" name="add" id="add-btn-characteristic" class="btn green-btn">+</button></td>
                    @else
                        <td><button type="button" onclick="$(this).parents('tr').remove()" class="btn red-btn remove-tr">-</button></td>
                    @endif
                </tr>
                @php $index++; @endphp
            @endforeach
        @else
            <tr>
                <td id="characteristic-data"><select name="characteristic[0]"   class="form-control">
                        @foreach($data as $item)
                            <option value="{{$item->id}}">{{$item->{'name_'.app()->getLocale()} }} </option>
                        @endforeach
                    </select>
                </td>
                        <td>
                            @foreach($langList as $k=>$i)
                                <div class="@if($k == $lang) active @endif form-lang-box form-lang-box-{{ $k }}">
                                    <input type="text" name="value[{{$k}}][0]" class="form-control" />
                                    <datalist id="{{ $class->field }}">
                                        @foreach($class->datalist as $k=>$i)
                                            <option>{{$i}}</option>
                                        @endforeach
                                    </datalist>
                                </div>
                            @endforeach
                        </td>

                <td><button type="button" name="add" id="add-btn-characteristic" class="btn green-btn">+</button></td>
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
