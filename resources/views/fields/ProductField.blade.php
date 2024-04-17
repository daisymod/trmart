<div style="width: 100%;" class="form-group">

    <table style="border-collapse: collapse; margin-top: 20px;" class="table table-bordered" id="productTable">
        <tr>
            <th>@lang('cart.size')</th>
            <th>@lang('products.color')</th>
            <!-- <th>@lang('system.m15')</th> -->
            <th>@lang('cart.count')</th>
            <!--<th>@lang('cart.sale')</th>-->
            <td></td>
        </tr>
        @if(count($product) == 0)
            <tr style="border: 3px solid #cccccc">
                <td id="size-data">
                    <select name="addmore[0][size]" id="size-data"  class="form-control">
                        @foreach($size as $item)
                            <option value="{{$item->id}}">{{$item->{'name_'.app()->getLocale()} }} </option>
                        @endforeach
                    </select>
                </td>
                <td id="color-data">
                    <select name="addmore[0][color]"  id="color-data"  class="form-control">
                        @foreach($color as $item)
                            <option value="{{$item->id}}">{{$item->{'name_'.app()->getLocale()} }} </option>
                        @endforeach
                    </select>
                </td>
                <!--<td><input type="text" name="addmore[0][price]"  class="form-control" /></td>-->
                <td><input type="number" step="1" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" name="addmore[0][count]" class="form-control" /></td>
                <!--<td><input type="text" name="addmore[0][sale]"  class="form-control" /></td>-->
                <td>
                    <div class="field-images-box {{ $class->class ?? "" }}" data-style="{{ $class->style ?? "" }}" data-width="{{ $class->width ?? 800 }}" data-height="{{$class->height ?? 1200}}" data-filed="addmore[0][image]">
                        <div class="images-list-box">
                            <div class="image-box image-load" style="display: none">
                                <div class="image">
                                    <img src="/i/6.gif" style="width: 70px;">
                                </div>
                                <div class="name">Загрузка изображения</div>
                            </div>
                            @if($value != null)
                                @foreach($value as $info)
                                    @php $j = 0; @endphp
                                    @foreach($value[$j] as $info)
                                        <div class="image-box" data-file="{{$info["file"]}}" data-name="{{$info["name"]}}">
                                            <div class="image">
                                                <img src="{{$info["small"]}}">
                                            </div>
                                            <div class="name">
                                                {{$info["name"]}}
                                            </div>
                                            <div class="del">
                                                <i class="fas fa-trash"></i>
                                            </div>
                                            <div class="edit">
                                                <i class="fas fa-edit"></i>
                                            </div>
                                            <input  type="hidden" name="addmore[{{$index}}][{{$j}}][image]" value="{{json_encode($info)}}">
                                        </div>
                                        @php $j++; @endphp
                                    @endforeach
                                @endforeach
                            @endif
                        </div>
                        <div class="select-file btn btn-primary btn-sm">@lang('system.f5')</div>
                    </div>
                </td>
                <td></td>
            </tr>
        @else
            @php $index = 0; @endphp
            @foreach($product as $item)
                <tr style="border: 3px solid #cccccc">
                    <td id="size-data" >
                        <select name="addmore[{{$index}}][size]" class="form-control">
                            @foreach($size as $item)
                                <option @if($item->id == $product[$index]->size) selected="selected"  @endif value="{{$item->id}}">{{$item->{'name_'.app()->getLocale()} }} </option>
                            @endforeach
                        </select>
                    </td>
                    <td id="color-data" >
                        <select name="addmore[{{$index}}][color]"   class="form-control">
                            @foreach($color as $item)
                                <option @if($item->id == $product[$index]->color) selected="selected" @endif value="{{$item->id}}">{{$item->{'name_'.app()->getLocale()} }} </option>
                            @endforeach
                        </select>
                    </td>
                    <!--<td><input type="text" name="addmore[{{$index}}][price]" value="{{$product[$index]->price}}" class="form-control" /></td>-->
                    <td><input type="number" step="1" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"  name="addmore[{{$index}}][count]"  value="{{$product[$index]->count}}" class="form-control" /></td>
                    <!--<td><input type="text" name="addmore[{{$index}}][sale]" value="{{$product[$index]->sale}}" class="form-control" /></td>-->

                    <td>
                        <div class="field-images-box {{ $class->class ?? "" }}" data-style="{{ $class->style ?? "" }}" data-width="{{ $class->width ?? 800 }}" data-height="{{$class->height ?? 1200}}" data-filed="addmore[{{$index}}][image]">
                            <div class="images-list-box">
                                <div class="image-box image-load" style="display: none">
                                    <div class="image">
                                        <img src="/i/6.gif" style="width: 70px;">
                                    </div>
                                    <div class="name">Загрузка изображения</div>
                                </div>
                                @if($value != null)
                                @php $j = 0; @endphp
                                @if(isset($value[$index]))
                                        @foreach($value[$index] as $info)
                                            <div class="image-box" data-file="{{$info["file"]?? '#'}}" data-name="{{$info["name"]?? '#'}}">
                                                <div class="image">
                                                    <img src="{{$info["small"]?? '#'}}">
                                                </div>
                                                <div class="name">
                                                    {{$info["name"] ?? '#'}}
                                                </div>
                                                <div class="del">
                                                    <i class="fas fa-trash"></i>
                                                </div>
                                                <div class="edit">
                                                    <i class="fas fa-edit"></i>
                                                </div>
                                                <input  type="hidden" name="addmore[{{$index}}][{{$j}}][image]" value="{{json_encode($info)}}">
                                            </div>
                                            @php $j++; @endphp
                                        @endforeach
                                    @else
                                        @php $j++; @endphp
                                @endif

                                @endif
                            </div>
                            <div class="select-file btn btn-primary btn-sm">@lang('system.f5')</div>
                        </div>
                    </td>

                   <td><button type="button" onclick="$(this).parents('tr').remove()" class="btn red-btn remove-tr">-</button></td>


                    @php $index++; @endphp
                </tr>
            @endforeach
        @endif


    </table>
    <button type="button" style="margin-top: 20px" id="add-btn-product" class="btn green-btn">@lang('system.newColor1')</button>
</div>

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


