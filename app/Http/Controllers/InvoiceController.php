<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceAttachments;
use App\Models\InvoiceDetails;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invoices = Invoice::all();
        return view('invoices.invoices', ['invoices' => $invoices]);
    }

     /**
     * Display a listing of the paid invoices.
     *
     * @return \Illuminate\Http\Response
     */
    public function getPaid()
    {
        $invoices = Invoice::where('status_id', 2)->get();
        return view('invoices.paid_invoices', ['invoices' => $invoices]);
    }

     /**
     * Display a listing of the unpaid invoices.
     *
     * @return \Illuminate\Http\Response
     */
    public function getUnpaid()
    {
        $invoices = Invoice::where('status_id', 0)->get();
        return view('invoices.unpaid_invoices', ['invoices' => $invoices]);
    }

     /**
     * Display a listing of the partially paid invoices.
     *
     * @return \Illuminate\Http\Response
     */
    public function getPartiallyPaid()
    {
        $invoices = Invoice::where('status_id', 1)->get();
        return view('invoices.partial_paid_invoices', ['invoices' => $invoices]);
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sections = Section::all();
        return view('invoices.add_invoice', ['sections' => $sections]);
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
            'invoice_number' => 'required|max:50|unique:invoices,invoice_number',
            'invoice_Date' => 'required|date',
            'Due_date' => 'required|date',
            'product_id' => 'required|exists:products,id',
            'Amount_collection' => 'required|regex:/^\d*(\.\d{1,2})?$/',
            'Amount_Commission' => 'required|regex:/^\d*(\.\d{1,2})?$/',
            'Discount' => 'required|regex:/^\d*(\.\d{1,2})?$/',
            'Rate_VAT' => 'required|numeric',
            'Value_VAT' => 'required|regex:/^\d*(\.\d{1,2})?$/',
            'Total' => 'required|regex:/^\d*(\.\d{1,2})?$/',
            'note' => 'string|nullable',
            'attachment' => 'nullable|mimes:pdf,png,jpeg,jpg',
        ]);

        if ($validator)
        {
            $valid_request = $request->all();
            $valid_request['status_id'] = 0;
            $valid_request['created_by'] = Auth::user()->name;

            $invoice = Invoice::create($valid_request);

            if ($invoice)
            {
                // saving invoice datails
                $invoice_details = InvoiceDetails::create([
                    'invoice_id' => $invoice->id,
                    'status_id' => $invoice->status_id,
                    'created_by' => Auth::user()->name,
                ]);

                if ($request->hasFile('attachment'))
                {
                    //saving attachment
                    $path = Storage::putFile($invoice->invoice_number, $request->file('attachment'));
                    InvoiceAttachments::create([
                        'invoice_id' => $invoice->id,
                        'file_name' => $path,
                        'created_by' => Auth::user()->name,
                    ]);
                }
            }
            session()->flash('success', 'تم اضافة  الفاتورة بنجاح ');
            return back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function show(Invoice $invoice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function edit($invoice_id)
    {
        $invoice = Invoice::findOrFail($invoice_id);

        $sections = Section::all();

        return view('invoices.edit_invoice', ['invoice' => $invoice, 'sections' => $sections]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = $request->validate([
            'invoice_number' => "required|max:50|unique:invoices,invoice_number,".$id,
            'invoice_Date' => 'required|date',
            'Due_date' => 'required|date',
            'product_id' => 'required|exists:products,id',
            'Amount_collection' => 'required|regex:/^\d*(\.\d{1,2})?$/',
            'Amount_Commission' => 'required|regex:/^\d*(\.\d{1,2})?$/',
            'Discount' => 'required|regex:/^\d*(\.\d{1,2})?$/',
            'Rate_VAT' => 'required|numeric',
            'Value_VAT' => 'required|regex:/^\d*(\.\d{1,2})?$/',
            'Total' => 'required|regex:/^\d*(\.\d{1,2})?$/',
            'note' => 'string|nullable',
        ]);

        if ($validator)
        {
            $invoice = Invoice::find($id);

            if ($invoice)
            {
                $invoice->update(
                    $request->all()
                );
                
                session()->flash('success', 'تم تعديل الفاتورة بنجاح ');
                return back();
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $invoice_id = $request->invoice_id;
        $invoice = Invoice::findOrFail($invoice_id);
        //delete attachments
        Storage::deleteDirectory($invoice->invoice_number);
        $invoice->forceDelete();

        session()->flash('success', 'تم حذف الفاتورة بنجاح ');
        return back();
    }

    public function getProducts($section_id)
    {
        $section = Section::findOrFail($section_id);

        if ($section) {
            return response()->json($section->products);
        }
    }
}
