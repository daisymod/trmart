<div class="form-group">
    <div class="form-label">{{ __($class->name) }}</div>
    <div class="relation-form-box"  data-url="/field/catalog"
         data-field="{{ $class->field }}"
         data-multiple="{{ $class->multiple }}"
         data-parent-id="{{ $class->parentId }}"
         data-level1-only="{{ $class->level1Only }}"
         data-select-parent="{{ $class->selectParent }}"
         data-ignore-id="{{ $class->ignoreId }}"
    >
        <div class="relation-ids">
            @foreach($value as $k=>$i)
                <div class="relation-id" data-id="{{ $k }}" data-name="{{ $i }}">
                    <input type="hidden" name="{{ $class->field }}[]" value="{{ $k }}">{{ $i }}
                    <div class="close">
                        <i class="fas fa-times-circle"></i>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="btn btn-primary btn-sm @if ($action == "find") relation-form-open @else relation-form-open-catalog @endif">@lang('system.f8')</div>
    </div>

    {{--        <input list="{{ $class->field }}" name="{{ $class->field }}" class="form-control" value="{{ $value }}">--}}
    <div class="error-field-text error-{{ $class->field }}"></div>

    @if(\Request::route()->getName() == 'merchant.self')
        <span>@lang('system.qq1')</span>
    @endif
</div>

