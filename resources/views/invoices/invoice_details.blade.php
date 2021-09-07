@extends('layouts.master')
@section('title')
    تفاصيل فاتورة
@stop
@section('css')
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">قائمة الفواتير</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/
                    تفاصيل فاتورة</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')

@if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('success') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
@endif

        <!-- row -->
        <div class="row">
            <div class="panel panel-primary tabs-style-2 col">
                <div class=" tab-menu-heading">
                    <div class="tabs-menu1">
                        <!-- Tabs -->
                        <ul class="nav panel-tabs main-nav-line">
                            <li><a href="#tab4" class="nav-link active" data-toggle="tab">معلومات الفاتورة</a></li>
                            <li><a href="#tab5" class="nav-link" data-toggle="tab">تفاصيل الفاتورة</a></li>
                            <li><a href="#tab6" class="nav-link" data-toggle="tab">مرفقات الفاتورة</a></li>
                        </ul>
                    </div>
                </div>
                
                <div class="panel-body tabs-menu-body main-content-body-right border w-100">
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab4">
                            <div class="table-responsive mt-15">
    
                                <table class="table table-striped" style="text-align:center">
                                    <tbody>
                                        <tr>
                                            <th scope="row">رقم الفاتورة</th>
                                            <td>{{ $invoice->invoice_number }}</td>
    
                                            <th scope="row">تاريخ الاصدار</th>
                                            <td>{{ $invoice->invoice_Date }}</td>
    
                                            <th scope="row">تاريخ الاستحقاق</th>
                                            <td>{{ $invoice->Due_date }}</td>
    
                                            <th scope="row">القسم</th>
                                            <td>{{ $invoice->product->section->section_name }}</td>
    
                                            <th scope="row">المنتج</th>
                                            <td>{{ $invoice->product->product_name }}</td>
                                        </tr>
    
                                        <tr>
                                            <th scope="row">مبلغ التحصيل</th>
                                            <td>{{ "$" . $invoice->Amount_collection }}</td>
    
                                            <th scope="row">مبلغ العمولة</th>
                                            <td>{{ "$" . $invoice->Amount_Commission }}</td>
    
                                            <th scope="row">الخصم</th>
                                            <td>{{ "$" . $invoice->Discount }}</td>
    
                                            <th scope="row">نسبة الضريبة</th>
                                            <td>{{ $invoice->Rate_VAT . '%' }}</td>
    
                                            <th scope="row">قيمة الضريبة</th>
                                            <td>{{ "$" . $invoice->Value_VAT }}</td>
                                        </tr>
    
                                        <tr>
                                            <th scope="row">الاجمالي مع الضريبة</th>
                                            <td>{{ "$" . $invoice->Total }}</td>
    
                                            <th scope="row">الحالة الحالية</th>
    
                                            @if ($invoice->status_id == 2)
                                                <td>
                                                    <span class="badge badge-pill badge-success">{{ $invoice->status->status_name }}</span>
                                                </td>
                                            @elseif($invoice->status_id == 0)
                                                <td>
                                                    <span class="badge badge-pill badge-danger">{{ $invoice->status->status_name }}</span>
                                                </td>
                                            @else
                                                <td>
                                                    <span class="badge badge-pill badge-warning">{{ $invoice->status->status_name }}</span>
                                                </td>
                                            @endif
    
                                            @if ($invoice->status_id == 2)
                                                <th scope="row">تاريخ الدفع</th>
                                                <td>{{ $invoice->Payment_Date }}</td>
                                            @endif
                                            <th scope="row">المستخدم</th>
                                            <td>{{ $invoice->created_by }}</td>
                                            <th scope="row">ملاحظات</th>
                                            <td>{{ $invoice->note }}</td>
                                        </tr>
                                    </tbody>
                                </table>
    
                            </div>
                        </div>
                        {{-- end of tab 4 --}}
                        <div class="tab-pane" id="tab5">
                            <div class="table-responsive mt-15">
                                <table class="table center-aligned-table mb-0 table-hover" style="text-align:center">
                                    <thead>
                                        <tr class="text-dark">
                                            <th>#</th>
                                            <th>حالة الدفع</th>
                                            <th>تاريخ الدفع </th>
                                            <th>ملاحظات</th>
                                            <th>تاريخ الاضافة </th>
                                            <th>المستخدم</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($invoice->details as $index => $detail)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                @if ($detail->status_id == 2)
                                                    <td><span
                                                            class="badge badge-pill badge-success">{{ $detail->status->status_name }}</span>
                                                    </td>
                                                @elseif($detail->status_id == 0)
                                                    <td><span
                                                            class="badge badge-pill badge-danger">{{ $detail->status->status_name }}</span>
                                                    </td>
                                                @else
                                                    <td><span
                                                            class="badge badge-pill badge-warning">{{ $detail->status->status_name }}</span>
                                                    </td>
                                                @endif
                                                <td>{{ $detail->Payment_Date ?? '--' }}</td>
                                                <td>{{ $detail->note ?? '--' }}</td>
                                                <td>{{ $detail->created_at->format('Y-m-d') }}</td>
                                                <td>{{ $detail->created_by }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <div class="tab-pane" id="tab6">

                            {{-- add an attachment  --}}
                            <div class="card-body">
                                <p class="text-danger">* صيغة المرفق pdf, jpeg ,.jpg , png </p>
                                <h5 class="card-title">اضافة مرفقات</h5>
                                <form method="post" action="{{ route('invoice_attachments.store') }}" 
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input"
                                            name="attachment" required>
                                        <input type="hidden" name="invoice_number"
                                            value="{{ $invoice->invoice_number }}">
                                        <input type="hidden" id="invoice_id" name="invoice_id"
                                            value="{{ $invoice->id }}">
                                        <label class="custom-file-label" for="customFile">حدد
                                            المرفق</label>
                                    </div><br><br>
                                    <button type="submit" class="btn btn-primary btn-sm "
                                        name="uploadedFile">تاكيد</button>
                                </form>
                            </div>
                            {{--end of  add an attachment  --}}


                            <div class="table-responsive mt-15">
                                
                                <table class="table center-aligned-table mb-0 table table-hover" style="text-align:center">
                                    <thead>
                                        <tr class="text-dark">
                                            <th scope="col">م</th>
                                            <th scope="col">اسم الملف</th>
                                            <th scope="col">قام بالاضافة</th>
                                            <th scope="col">تاريخ الاضافة</th>
                                            <th scope="col">العمليات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($invoice->attachments as $index => $attachment)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $attachment->file_name }}</td>
                                                <td>{{ $attachment->Created_by }}</td>
                                                <td>{{ $attachment->created_at }}</td>
                                                <td colspan="2">
    
                                                    <a class="btn btn-outline-success btn-sm"
                                                        href="{{ url(asset('attachments')) }}/{{ $attachment->file_name }}"
                                                        role="button"><i class="fas fa-eye"></i>&nbsp;
                                                        عرض</a>
    
                                                    <a class="btn btn-outline-info btn-sm"
                                                        href="{{ url('download') }}/{{ $attachment->file_name }}"
                                                        role="button"><i class="fas fa-download"></i>&nbsp;
                                                        تحميل</a>
    
                                                    <button class="btn btn-outline-danger btn-sm" data-toggle="modal"
                                                        data-attachment_id="{{ $attachment->id }}"
                                                        data-target="#delete_file">
                                                        حذف</button>
    
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    {{-- end of tab content --}}
                </div>
                {{-- end of panel body --}}
            </div>
            {{-- end of panel --}}

            
            {{-- delete modal --}}
				 <div class="modal" id="delete_file">
					<div class="modal-dialog modal-dialog-centered" role="document">
						<div class="modal-content modal-content-demo">
							<div class="modal-header">
								<h6 class="modal-title">حذف المرفق</h6><button aria-label="Close" class="close" data-dismiss="modal"
									type="button"><span aria-hidden="true">&times;</span></button>
							</div>
							<form action="{{route('invoice_attachments.destroy', 1)}}" method="post">
								{{ method_field('delete') }}
								{{ csrf_field() }}
								<div class="modal-body">
									<p>هل انت متاكد من حذف المرفق ؟</p><br>
									<input type="hidden" name="attachment_id" id="attachment_id" value="">
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-dismiss="modal">الغاء</button>
									<button type="submit" class="btn btn-danger">تاكيد</button>
								</div>
						</div>
						</form>
					</div>
				</div>
				{{--end of delete modal --}}
        </div>
        <!-- row closed -->
    </div>
<!-- Container closed -->
</div>
<!-- main-content closed -->

    
@endsection
@section('js')
    <script>
        $('#delete_file').on('show.bs.modal', function(event) {
            let button = $(event.relatedTarget)
            let attachment_id = button.data('attachment_id')
            let modal = $(this)
            modal.find('.modal-body #attachment_id').val(attachment_id);
        })
    </script>
@endsection
