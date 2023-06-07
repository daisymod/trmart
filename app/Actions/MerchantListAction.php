<?php

namespace App\Actions;

use App\Forms\ExtendMerchantAdminForm;
use App\Forms\MerchantAdminForm;
use App\Forms\MerchantSearchForm;
use App\Models\Merchant;

class MerchantListAction
{
    public function __invoke(): array
    {
        $merchant = new Merchant();
        $form = new MerchantSearchForm($merchant);

        $sort = explode(".", request("sort_by", "name.asc"));
        $records = Merchant::query()
            ->when(!empty(request()->first_name),function ($q){
                $q->where('name','LIKE','%'.request()->first_name.'%');
            })
            ->when(!empty(request()->last_name),function ($q){
                $q->where('s_name','LIKE','%'.request()->last_name.'%');
            })
            ->when(!empty(request()->patronymic),function ($q){
                $q->where('m_name','LIKE','%'.request()->patronymic.'%');
            })
            ->when(!empty(request()->phone),function ($q){
                $q->where('phone','LIKE','%'.request()->phone.'%');
            })
            ->when(!empty(request()->email),function ($q){
                $q->where('phone','LIKE','%'.request()->email.'%');
            })
            ->when(!empty(request()->status),function ($q){
                $q->where('status','=',request()->status);
            })
            ->when(!empty(request()->active),function ($q){
                $q->where('active','=',request()->active);
            })
            ->when(!empty(request()->company_name),function ($q){
                $q->whereHas('company',function ($query){
                    $query->where('company_name','LIKE',"%".request()->company_name."%");
                });
            })
            ->when(!empty(request()->shop_name),function ($q){
                $q->whereHas('company',function ($query){
                    $query->where('shop_name','LIKE',"%".request()->shop_name."%");
                });
            })
            ->when(!empty(request()->tax_office),function ($q){
                $q->whereHas('company',function ($query){
                    $query->where('tax_office','=',request()->tax_office);
                });
            })
            ->when(!empty(request()->legal_address_city),function ($q){
                $q->whereHas('company',function ($query){
                    $query->where('legal_address_city','=',request()->legal_address_city);
                });
            })
            ->when(!empty(request()->legal_address_street),function ($q){
                $q->whereHas('company',function ($query){
                    $query->where('legal_address_street','=',request()->legal_address_street);
                });
            })
            ->when(!empty(request()->legal_address_office),function ($q){
                $q->whereHas('company',function ($query){
                    $query->where('legal_address_office','=',request()->legal_address_office);
                });
            })
            ->when(!empty(request()->legal_address_number),function ($q){
                $q->whereHas('company',function ($query){
                    $query->where('legal_address_number','=',request()->legal_address_number);
                });
            })
            ->when(!empty(request()->city),function ($q){
                $q->whereHas('company',function ($query){
                    $query->where('city','=',request()->city);
                });
            })
            ->when(!empty(request()->street),function ($q){
                $q->whereHas('company',function ($query){
                    $query->where('street','=',request()->street);
                });
            })
            ->when(!empty(request()->number),function ($q){
                $q->whereHas('company',function ($query){
                    $query->where('number','=',request()->number);
                });
            })

            ->when(!empty(request()->office),function ($q){
                $q->whereHas('company',function ($query){
                    $query->where('office','=',request()->office);
                });
            })
            ->when(!empty(request()->tckn),function ($q){
                $q->whereHas('company',function ($query){
                    $query->where('tckn','=',request()->tckn);
                });
            })
            ->when(!empty(request()->vkn),function ($q){
                $q->whereHas('company',function ($query){
                    $query->where('vkn','=',request()->vkn);
                });
            })
            ->when(!empty(request()->iban),function ($q){
                $q->whereHas('company',function ($query){
                    $query->where('iban','=',request()->iban);
                });
            })
            ->where("role", "merchant")
            ->orderBy($sort[0], $sort[1]);
        $records = $form->formCreateFind($records, request()->all());
        $records = $records->paginate(50);
        $form = $form->formRenderAdd();
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
