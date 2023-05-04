<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;

class CommisionExport implements FromView
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
        $start_date = '2024-05-01';
        $end_date = '2024-05-31';
        $sql = 'SELECT * FROM investments WHERE dividen_date BETWEEN ? AND ? AND username = ?';
        $data = DB::select($sql, [$start_date, $end_date, $this->visitor["username"]]);
        $total = 0;
        foreach($data as $d){
            $total += $d->total_direct_sales;
        }


       return view('exports.commision', compact('data'));
   }
    
}
