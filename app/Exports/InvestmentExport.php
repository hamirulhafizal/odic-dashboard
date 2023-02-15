<?php

namespace App\Exports;

use App\Models\Investments;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;

class InvestmentExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;
    protected $visitor;

    function __construct($data) {
        $this->visitor = $data;
    }

    public function view(): View
    {
        $data = Investments::join('investment_status', 'investment_status.investment_id', '=' ,'investments.id')
        ->select('investments.*', 'investment_status.name as status')
        ->get();


        return view('exports.investment', compact('data'));
    }
}
