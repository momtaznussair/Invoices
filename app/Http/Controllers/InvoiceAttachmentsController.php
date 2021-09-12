<?php

namespace App\Http\Controllers;

use App\Models\InvoiceAttachments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class InvoiceAttachmentsController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:add invoice attachment', ['only' => ['create','store']]);
        $this->middleware('permission:delete invoice attachment', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
            'attachment' => 'required|mimes:pdf,png,jpeg,jpg',
            'invoice_id' => 'required|exists:invoices,id',
            'invoice_number' => 'required|exists:invoices,invoice_number',
        ]);

        if($validator)
        {
            if ($request->hasFile('attachment'))
            {
                //saving attachment
                $path = Storage::putFile($request->invoice_number, $request->file('attachment'));
                InvoiceAttachments::create([
                    'invoice_id' => $request->invoice_id,
                    'file_name' => $path,
                    'created_by' => Auth::user()->name,
                ]);
            }
        }
        session()->flash('success', 'تم اضافة  المرفق بنجاح ');
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\InvoiceAttachments  $invoiceAttachments
     * @return \Illuminate\Http\Response
     */
    public function show(InvoiceAttachments $invoiceAttachments)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\InvoiceAttachments  $invoiceAttachments
     * @return \Illuminate\Http\Response
     */
    public function edit(InvoiceAttachments $invoiceAttachments)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\InvoiceAttachments  $invoiceAttachments
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InvoiceAttachments $invoiceAttachments)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\InvoiceAttachments  $invoiceAttachments
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $attachment = InvoiceAttachments::findOrFail($request->attachment_id);
        Storage::delete($attachment->file_name);
        $attachment->delete();

        session()->flash('success', 'تم حذف المرفق بنجاح');
        return back();
    }

    public function download($invoice, $attachment)
    {
        return Storage::download($invoice.'/'.$attachment);
    }
}
