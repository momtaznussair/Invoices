<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Product;
use App\Models\Section;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // charts data
        $CountOfInvoices = Invoice::count();
        $TotalOfAllInvoices = Invoice::sum('Total');
        //paid
        $countOfPaid = Invoice::where('status_id', 3)->count();
        $sumOfPaid = Invoice::where('status_id', 3)->sum('Total');
        //unpaid
        $countOfUnpaid = Invoice::where('status_id', 1)->count();
        $sumOfUnpaid = Invoice::where('status_id', 1)->sum('Total');
        //partially paid
        $countOfPartiallyPaid = Invoice::where('status_id', 2)->count();
        $sumOfPartiallyPaid = Invoice::where('status_id', 2)->sum('Total');
        // percentages %
        if ($TotalOfAllInvoices > 0)
        {
            $paid = round($sumOfPaid / $TotalOfAllInvoices * 100);
            $unPaid = round($sumOfUnpaid / $TotalOfAllInvoices * 100);
            $partiallyPaid = round(($sumOfPartiallyPaid / $TotalOfAllInvoices) * 100);
        }
        // counts
        $customers = Section::count();
        $products  = Product::count();

        // bar chart
        $chartjs = app()->chartjs
        ->name('barChartTest')
        ->type('bar')
        ->size(['width' => 350, 'height' => 150])
        ->datasets([
            [
                "label" => "الفواتير الغير المدفوعة",
                'backgroundColor' => ['#ec5858'],
                'data' => [$unPaid]
            ],
            [
                "label" => "الفواتير المدفوعة",
                'backgroundColor' => ['#81b214'],
                'data' => [$paid]
            ],
            [
                "label" => "الفواتير المدفوعة جزئيا",
                'backgroundColor' => ['#ff9642'],
                'data' => [$partiallyPaid]
            ],


        ])
        ->options([]);

        // // pie chart
        $chartjs2 = app()->chartjs
        ->name('barChartTest2')
        ->type('doughnut')
        ->size(['width' => 350, 'height' => 230])
        ->labels(['الغير مدفوعة', 'المدفوعة', 'المدفوعة جزئياً'])
        ->datasets([
            [
                "label" => "My First Dataset",
                'backgroundColor' => [
                    '#ec5858',
                    '#81b214',
                    '#ff9642'
                ],
                'data' => [$unPaid, $paid, $partiallyPaid]
            ],
        ])
        ->options([
            'hoverOffset' => 4,
        ]);
        
        return view('home', compact('CountOfInvoices', 'TotalOfAllInvoices', 'countOfPaid', 'sumOfPaid', 'countOfUnpaid', 'sumOfUnpaid', 'countOfPartiallyPaid', 'sumOfPartiallyPaid', 'chartjs', 'chartjs2', 'paid', 'unPaid', 'partiallyPaid', 'customers', 'products'));
    }
}
