@extends('layouts.master')
@section('css')
    <!--- Internal Select2 css-->
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <!---Internal Fileupload css-->
    <link href="{{ URL::asset('assets/plugins/fileuploads/css/fileupload.css') }}" rel="stylesheet" type="text/css" />
    <!---Internal Fancy uploader css-->
    <link href="{{ URL::asset('assets/plugins/fancyuploder/fancy_fileupload.css') }}" rel="stylesheet" />
    <!--Internal Sumoselect css-->
    <link rel="stylesheet" href="{{ URL::asset('assets/plugins/sumoselect/sumoselect-rtl.css') }}">
    <!--Internal  TelephoneInput css-->
    <link rel="stylesheet" href="{{ URL::asset('assets/plugins/telephoneinput/telephoneinput-rtl.css') }}">
        <!--Internal   Notify -->
    <link href="{{URL::asset('assets/plugins/notify/css/notifIt.css')}}" rel="stylesheet"/>
@endsection
@section('title')
   تعديل حالة الدفع
@stop

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">الفواتير</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/
                    تعديل حالة الدفع</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')

    {{-- success message --}}
    @if (session()->has('success'))
        <script>
            window.onload = function() {
            notif({
                msg: "{{session()->get('success')}}",
                type: "success"
            })
            }
        </script>
    @endif
    {{--  errors --}}
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <!-- row -->
    <div class="row">

        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('change-invoice-status')}}" method="post" enctype="multipart/form-data"
                        autocomplete="off">
                        @csrf
                        {{-- 1 --}}

                        <div class="row mb-1">
                            <div class="col">
                                <label for="invoiceNumber" class="control-label">رقم الفاتورة</label>
                                <input type="text" class="form-control" id="invoiceNumber"  value="{{$invoice->invoice_number}}"
                                    title="يرجي ادخال رقم الفاتورة" readonly>
                            </div>

                            <div class="col">
                                <label>تاريخ الفاتورة</label>
                                <input class="form-control fc-datepicker"  placeholder="YYYY-MM-DD"
                                    type="text" value="{{ $invoice->invoice_Date }}" readonly>
                            </div>

                            <div class="col">
                                <label>تاريخ الاستحقاق</label>
                                <input class="form-control fc-datepicker"  placeholder="YYYY-MM-DD" value="{{ $invoice->Due_date }}"
                                    type="text" readonly>
                            </div>

                        </div>

                        {{-- 2 --}}
                        <div class="row mb-1">

                            <div class="col">
                                <label for="inputName" class="control-label">القسم</label>
                                <select id="section" name="section_id" class="form-control" disabled>
                                    <option value="{{ $invoice->product->section->id }}" selected> {{ $invoice->product->section->section_name }}</option>
                                </select>
                            </div>


                            <div class="col">
                                <label for="inputName" class="control-label">المنتج</label>
                                <select id="product_id"  class="form-control" disabled>
                                    <option value="{{ $invoice->product->id }}" selected> {{ $invoice->product->product_name }}</option>
                                </select>
                            </div>

                            <div class="col">
                                <label for="inputName" class="control-label">مبلغ التحصيل</label>
                                <input type="text" class="form-control" id="inputName"  value="{{$invoice->Amount_collection}}"
                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" readonly>
                            </div>
                        </div>


                        {{-- 3 --}}

                        <div class="row mb-1">

                            <div class="col">
                                <label for="inputName" class="control-label">مبلغ العمولة</label>
                                <input type="text" class="form-control form-control-lg" id="Amount_Commission" value="{{$invoice->Amount_Commission}}"
                                     title="يرجي ادخال مبلغ العمولة "
                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                                    readonly>
                            </div>

                            <div class="col">
                                <label for="inputName" class="control-label">الخصم</label>
                                <input type="text" class="form-control form-control-lg" id="Discount"  value="{{$invoice->Discount}}"
                                    title="يرجي ادخال مبلغ الخصم "
                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                                    value=0 readonly>
                            </div>

                            <div class="col">
                                <label for="inputName" class="control-label">نسبة ضريبة القيمة المضافة</label>
                                <select  id="Rate_VAT" class="form-control" onchange="getTotal()" disabled>
                                    <!--placeholder-->
                                    <option value="" selected disabled>حدد نسبة الضريبة</option>
                                    <option value="5">5%</option>
                                    <option value="7">7%</option>
                                    <option value="10">10%</option>
                                    <option value="12">12%</option>
                                    <option value="15">15%</option>
                                </select>
                            </div>

                        </div>

                        {{-- 4 --}}

                        <div class="row mb-1">
                            <div class="col">
                                <label for="inputName" class="control-label">قيمة ضريبة القيمة المضافة</label>
                                <input type="text" class="form-control" id="Value_VAT"  readonly>
                            </div>

                            <div class="col">
                                <label for="inputName" class="control-label">الاجمالي شامل الضريبة</label>
                                <input type="text" class="form-control" id="Total"  readonly>
                            </div>
                        </div>

                        {{-- 5 --}}
                        <div class="row">
                            <div class="col">
                                <label for="exampleTextarea">ملاحظات</label>
                                <textarea class="form-control" id="exampleTextarea"  rows="3" readonly>{{$invoice->note}}</textarea>
                            </div>
                        </div><br>

                        {{-- 6- change status --}}
                        <div class="row justify-content-center mb-2">
                            {{-- payment status --}}
                            <div class="col-4">
                                <label for="status" class="control-label">حالة الدفع</label>
                                <select id="status" name="status_id" class="form-control">
                                    <option value="{{ $invoice->status->id }}" selected> {{ $invoice->status->status_name }}</option>
                                    @foreach ($statuses as $status)
                                        @if($status->id != $invoice->status->id)
                                            <option value="{{ $status->id }}"> {{ $status->status_name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <input type="hidden" name="invoice_id" value="{{$invoice->id}}">
                            {{-- payment date --}}
                            <div class="col-4">
                                <label>تاريخ الدفع</label>
                                <input class="form-control fc-datepicker" name="payment_date"
                                    type="text" value="{{ date('Y-m-d') }}" required>
                            </div>

                            <div class="col-4">
                                <label>ملاحظات</label>
                                <input class="form-control" name="note"
                                    type="text">
                            </div>
                            

                        </div>

                        {{-- submit --}}
                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary">تغيير حالة الدفع</button>
                        </div>


                    </form>
                </div>
            </div>
        </div>
    </div>

    </div>

    <!-- row closed -->
    </div>
    <!-- Container closed -->
    </div>
    <!-- main-content closed -->
@endsection
@section('js')
    <!-- Internal Select2 js-->
    <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <!--Internal Fileuploads js-->
    <script src="{{ URL::asset('assets/plugins/fileuploads/js/fileupload.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fileuploads/js/file-upload.js') }}"></script>
    <!--Internal Fancy uploader js-->
    <script src="{{ URL::asset('assets/plugins/fancyuploder/jquery.ui.widget.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fancyuploder/jquery.fileupload.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fancyuploder/jquery.iframe-transport.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fancyuploder/jquery.fancy-fileupload.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fancyuploder/fancy-uploader.js') }}"></script>
    <!--Internal  Form-elements js-->
    <script src="{{ URL::asset('assets/js/advanced-form-elements.js') }}"></script>
    <script src="{{ URL::asset('assets/js/select2.js') }}"></script>
    <!--Internal Sumoselect js-->
    <script src="{{ URL::asset('assets/plugins/sumoselect/jquery.sumoselect.js') }}"></script>
    <!--Internal  Datepicker js -->
    <script src="{{ URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js') }}"></script>
    <!--Internal  jquery.maskedinput js -->
    <script src="{{ URL::asset('assets/plugins/jquery.maskedinput/jquery.maskedinput.js') }}"></script>
    <!--Internal  spectrum-colorpicker js -->
    <script src="{{ URL::asset('assets/plugins/spectrum-colorpicker/spectrum.js') }}"></script>
    <!-- Internal form-elements js -->
    <script src="{{ URL::asset('assets/js/form-elements.js') }}"></script>

    <script>
        let date = $('.fc-datepicker').datepicker({
            dateFormat: 'yy-mm-dd'
        }).val();
    </script>

    <script>
        $(document).ready(function() {

            // selecting current vat rate
            $(`select[id="Rate_VAT"]>option[value="{{$invoice->Rate_VAT}}"]`).prop('selected', true);
             // get current commission after discount and tax
            getTotal();
        });
    </script>



    <script>
        // get net commission after discount and tax
        function getTotal() {
            var Amount_Commission = parseFloat(document.getElementById("Amount_Commission").value);
            var Discount = parseFloat(document.getElementById("Discount").value);
            var Rate_VAT = parseFloat(document.getElementById("Rate_VAT").value);
            var Value_VAT = parseFloat(document.getElementById("Value_VAT").value);
            var Amount_Commission2 = Amount_Commission - Discount;
            if (typeof Amount_Commission === 'undefined' || !Amount_Commission) {
                alert('يرجي ادخال مبلغ العمولة ');
            } else {
                var intResults = Amount_Commission2 * Rate_VAT / 100;
                var intResults2 = parseFloat(intResults + Amount_Commission2);
                sumq = parseFloat(intResults).toFixed(2);
                sumt = parseFloat(intResults2).toFixed(2);
                document.getElementById("Value_VAT").value = sumq;
                document.getElementById("Total").value = sumt;
            }
        }
    </script>
    <!--Internal  Notify js -->
  <script src="{{URL::asset('assets/plugins/notify/js/notifIt.js')}}"></script>
  <script src="{{URL::asset('assets/plugins/notify/js/notifit-custom.js')}}"></script>
@endsection
