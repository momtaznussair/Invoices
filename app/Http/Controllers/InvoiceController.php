<?php

namespace App\Http\Controllers;

use App\Events\InvoiceAdded;
use App\Exports\InvoicesExport;
use App\Models\Invoice;
use App\Models\InvoiceAttachments;
use App\Models\InvoiceDetails;
use App\Models\Permission;
use App\Models\Section;
use App\Models\User;
use App\Notifications\AddInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;
use Maatwebsite\Excel\Facades\Excel;

class InvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:list invoices', ['only' => ['index']]);
        $this->middleware('permission:view invoice details', ['only' => ['show']]);
        $this->middleware('permission:add invoice', ['only' => ['create','store', 'getProducts']]);
        $this->middleware('permission:edit invoice', ['only' => ['edit','update', 'getProducts']]);
        $this->middleware('permission:delete invoice', ['only' => ['destroy']]);

        $this->middleware('permission:paid invoices', ['only' => ['getPaid']]);
        $this->middleware('permission:unpaid invoices', ['only' => ['getUnpaid']]);
        $this->middleware('permission:partially paid invoices', ['only' => ['getPartiallyPaid']]);
        $this->middleware('permission:invoices archive', ['only' => ['getArchived']]);
        $this->middleware('permission:archive invoice', ['only' => ['archive', 'restore']]);
        $this->middleware('permission:export invoice', ['only' => ['export']]);
    }
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
        $invoices = Invoice::where('status_id', 3)->get();
        return view('invoices.paid_invoices', ['invoices' => $invoices]);
    }

     /**
     * Display a listing of the unpaid invoices.
     *
     * @return \Illuminate\Http\Response
     */
    public function getUnpaid()
    {
        $invoices = Invoice::where('status_id', 1)->get();
        return view('invoices.unpaid_invoices', ['invoices' => $invoices]);
    }

     /**
     * Display a listing of the partially paid invoices.
     *
     * @return \Illuminate\Http\Response
     */
    public function getPartiallyPaid()
    {
        $invoices = Invoice::where('status_id', 2)->get();
        return view('invoices.partial_paid_invoices', ['invoices' => $invoices]);
    }


     /**
     * Display a listing of the partially paid invoices.
     *
     * @return \Illuminate\Http\Response
     */
    public function getArchived()
    {
        $invoices = Invoice::onlyTrashed()->get();
        return view('invoices.archived_invoices', ['invoices' => $invoices]);
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
            'Amount_collection' => 'required|numeric|between:0,999999.99',
            'Amount_Commission' => 'required|numeric|between:0,999999.99',
            'Discount' => 'required|numeric|between:0,999999.99',
            'Rate_VAT' => 'required|numeric',
            'Value_VAT' => 'required|numeric|between:0,999999.99',
            'Total' => 'required|numeric|between:0,999999.99',
            'note' => 'string|nullable',
            'attachment' => 'nullable|mimes:pdf,png,jpeg,jpg',
        ]);

        if ($validator)
        {
            $valid_request = $request->all();
            $valid_request['status_id'] = 1;
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
                        'Created_by' => Auth::user()->name,
                    ]);
                }
            }
            
            // notify users that has a 'notifications' permission except current
            $users = User::permission('notifications')->get()->except(Auth::id());
            Notification::send($users, new AddInvoice($invoice));

            broadcast(new InvoiceAdded($invoice->id, Auth::user()->name, 'تم إضافة فاتورة')); //->toOthers();
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
    public function show($invoice_id)
    {
        $invoice = Invoice::withTrashed()->where('id', $invoice_id)->first();

        return view('invoices.invoice', ['invoice' => $invoice]);
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
            'Amount_collection' => 'required|numeric|between:0,999999.99',
            'Amount_Commission' => 'required|numeric|between:0,999999.99',
            'Discount' => 'required|numeric|between:0,999999.99',
            'Rate_VAT' => 'required|numeric',
            'Value_VAT' => 'required|numeric|between:0,999999.99',
            'Total' => 'required|numeric|between:0,999999.99',
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

    public function archive(Request $request)
    {
        $invoice_id = $request->invoice_id;
        $invoice = Invoice::findOrFail($invoice_id);

        $invoice->Delete();

        session()->flash('success', 'تم أرشفة الفاتورة بنجاح ');
        return back();
    }

    public function restore(Request $request)
    {
        $invoice_id = $request->invoice_id;
        $invoice = Invoice::withTrashed()
        ->where('id', $invoice_id)
        ->restore();

        session()->flash('success', 'تم إستعادة الفاتورة بنجاح ');
        return back();
    }

    //  get all products for a specific section
    public function getProducts($section_id)
    {
        $section = Section::findOrFail($section_id);

        if ($section) {
            return response()->json($section->products);
        }
    }

    //export invoice as excel
    public function export() 
    {
        return Excel::download(new InvoicesExport, 'invoices.xlsx');
    }

    // mark all invoices notification as read for current user

    public function mark_all_as_read()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return back();
    }

    public function read_notification($id)
    {
        auth()->user()->unreadNotifications->where('id', $id)->markAsRead();
    }


}
