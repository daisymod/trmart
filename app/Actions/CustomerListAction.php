<?php

namespace App\Actions;

use App\Forms\CustomerAdminForm;
use App\Forms\ExtendCustomerAdminForm;
use App\Models\Customer;

class CustomerListAction
{
    public function __invoke(): array
    {
        $form = new ExtendCustomerAdminForm(new Customer());
        $sort = explode(".", request("sort_by", "name.asc"));
        $records = Customer::query()
            ->where("role", "merchant")
            ->orderBy($sort[0], $sort[1]);
        $records = $form->formCreateFind($records, request()->all());
        $records = $records->paginate(50);
        $form = $form->formRenderFind(request()->all());
        $sortBy = [
            "name.asc" => trans('system.p1'),
            "name.desc" => trans('system.p2'),
            "phone.asc" => trans('system.p3'),
            "phone.desc" => trans('system.p4'),
            "status.asc" => trans('system.p5'),
            "status.desc" => trans('system.p6'),
            "rating.asc" => trans('system.p7'),
            "rating.desc" => trans('system.p8'),
        ];
        return compact("records", "form", "sortBy");
    }
}
