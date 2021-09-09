<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceDetails;
use App\Models\InvoiceStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $statuses = InvoiceStatus::all();
        return view('invoices.invoice_status', ['statuses' => $statuses]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = $request->validate([
            'status_name' => 'required|unique:invoice_statuses,status_name|max:999',
            'description' => 'string|nullable',
        ],
        [
            'status_name.required' => 'يرجي ادخال اسم الحالة',
            'status_name.unique' => 'اسم الحالة مدخل مسبقا',
            'status_name.max' => 'الحد الاقصى لاسم الحالة هو 999 حرف',
        ]);

        if ($validator)
        {
            $status = InvoiceStatus::create([
                'status_name' => $request->status_name,
                'description' => $request->description,
                'created_by' => Auth::user()->name,
            ]);
            
            session()->flash('success', 'تم اضافة الحالة بنجاح ');
            return redirect('/statuses');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\InvoiceStatus  $invoiceStatus
     * @return \Illuminate\Http\Response
     */
    public function show(InvoiceStatus $invoiceStatus)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\InvoiceStatus  $invoiceStatus
     * @return \Illuminate\Http\Response
     */
    public function edit(InvoiceStatus $invoiceStatus)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\InvoiceStatus  $invoiceStatus
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InvoiceStatus $invoiceStatus)
    {
        $id = $request->id;
        $validator = $request->validate([
            'status_name' => "required|max:999|unique:invoice_statuses,status_name,". $id,
            'description' => 'string|nullable',
        ],
        [
            'status_name.required' => 'يرجي ادخال اسم الحالة',
            'status_name.unique' => 'اسم الحالة مدخل مسبقا',
            'status_name.max' => 'الحد الاقصى لاسم الحالة هو 999 حرف',
        ]);

        if ($validator)
        {
            $status = InvoiceStatus::find($id);
            if ($status)
            {
                $status->update([
                    'status_name' => $request->status_name,
                    'description' => $request->description,
                ]);
                
                session()->flash('success', 'تم تعديل الحالة بنجاح ');
                return redirect('/statuses');
            }
        }
    }

    public function invoice_status($invoice_id)
    {
        $statuses = InvoiceStatus::all();
        $invoice = Invoice::findOrFail($invoice_id);

        return view('invoices.change_status_invoice', ['invoice' => $invoice, 'statuses' => $statuses]);
    }

    public function change_invoice_status(Request $request)
    {   
        $validator = $request->validate([
            'status_id' => 'required|exists:invoice_statuses,id',
            'payment_date' => 'required',
        ]);

        if($validator)
        {
            $invoice = Invoice::findOrFail($request->invoice_id);
            // if not payed 0 => change status payement_date = null
            //if payed 2 change status  payment_date
            //if partially change status - []
            $invoice->update([
                'status_id' => $request->status_id,
                'Payment_Date' => $request->payment_date,
            ]);

            // add a record of the change in invoice details

            $detail = InvoiceDetails::create([
                'invoice_id' => $invoice->id,
                'status_id' => $request->status_id,
                'Payment_Date' => $request->payment_date,
                'note' => $request->note,
                'created_by' => Auth::user()->name,
            ]);
            
            session()->flash('success','تم تعديل حالة الفاتورة بنجاح');
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\InvoiceStatus  $invoiceStatus
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = $request->id;
        InvoiceStatus::find($id)->delete();
        session()->flash('success','تم حذف الحالة بنجاح');
        return redirect('/statuses');
    }
}
