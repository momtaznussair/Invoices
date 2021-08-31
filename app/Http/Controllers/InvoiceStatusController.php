<?php

namespace App\Http\Controllers;

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
            'description' => 'string',
        ],
        [
            'section_name.required' => 'يرجي ادخال اسم الحالة',
            'section_name.unique' => 'اسم الحالة مدخل مسبقا',
            'section_name.max' => 'الحد الاقصى لاسم الحالة هو 999 حرف',
        ]);

        if ($validator)
        {
            $status = InvoiceStatus::create([
                'status_name' => $request->status_name,
                'description' => $request->description,
                'created_by' => Auth::user()->name,
            ]);
            
            session()->flash('Add', 'تم اضافة الحالة بنجاح ');
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
                
                session()->flash('Edit', 'تم تعديل القسم بنجاح ');
                return redirect('/statuses');
            }
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
        session()->flash('delete','تم حذف الحالة بنجاح');
        return redirect('/statuses');
    }
}
