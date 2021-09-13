<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceStatus;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{
    // invoices
    public function invoices()
    {
        $statuses = InvoiceStatus::all();
        return view('reports.invoices', compact('statuses'));
    }

    public function searchInvoices(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rdio' => 'required|in:1,2',
            'invoice_number' => 'required_if:rdio,2',
            'start_at' => 'nullable|date',
            'end_at' => 'nullable|date',
        ],[
            'invoice_number.required_if' => 'الرجاء إدخال رقم الفاتورة',
        ]
    );

        if ($validator->fails()) {

            return redirect()
                        ->route('invoices-reports')
                        ->withErrors($validator)
                        ->withInput();
        }
       
        $statuses =  InvoiceStatus::all();

        if($request->rdio == 2)
        {
            $invoices = Invoice::where('invoice_number', $request->invoice_number)->get();
            session()->flashInput($request->input());
            return view('reports.invoices', ['invoices' => $invoices, 'statuses' => $statuses]);
        }else{

            $invoices = Invoice::select();
            if($request->type)
            {
                $invoices->where('status_id', $request->type);
            }
            
            if($request->start_at)
            {
                $invoices->whereDate('invoice_Date','>=', $request->start_at);
            }

            if($request->end_at)
            {
                $invoices->whereDate('invoice_Date','<=', $request->end_at);
            }

            session()->flashInput($request->input());
            return view('reports.invoices', ['invoices' => $invoices->get(), 'statuses' => $statuses]);
        }
    }


    // search by sectins and products

    public function customers()
    {
        $sections = Section::all();
        return view('reports.customers', ['sections' => $sections]);
    }

    public function searchCustomers(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'section' => 'required|exists:sections,id',
            'product' => 'nullable|exists:products,id',
            'start_at' => 'nullable|date',
            'end_at' => 'nullable|date',
        ],
        [
            'section.required' => 'الرجاء اختيار القسم',
        ]
    );
        if ($validator->fails()) {

            return redirect()
                        ->route('customers-reports')
                        ->withErrors($validator)
                        ->withInput();
        }

        if($request->product)
        {
            $invoices = Invoice::where('product_id', $request->product);
        }else{
            $products = Section::find($request->section)->products->pluck('id');
            $invoices = Invoice::whereIn('product_id', $products);
        }

        if($request->start_at)
        {
            $invoices->whereDate('invoice_Date','>=', $request->start_at);
        }

        if($request->end_at)
        {
            $invoices->whereDate('invoice_Date','<=', $request->end_at);
        }

        session()->flashInput($request->input());
        $sections = Section::all();
        $old_section = Section::find($request->section);
        return view('reports.customers', ['invoices' => $invoices->get(), 'sections' => $sections, 'old_section' => $old_section]);
    }
}
