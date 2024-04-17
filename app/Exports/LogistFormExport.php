<?php

namespace App\Exports;

use App\Models\Order;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class LogistFormExport implements FromView
{

    protected $orders;

    function __construct($orders) {
        $this->orders = $orders;
    }

    public function view(): View
    {
        $items = Order::query()
            ->whereIn('id', $this->orders)
            ->get();

        return view('exports.logist-export', compact('items'));
    }
}
