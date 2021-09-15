@extends('layouts.master')
@section('title')
قائمة الفواتير المدفوعة جزئياً
@stop
@section('css')
<!-- Internal Data table css -->
<link href="{{URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
<!--Internal   Notify -->
<link href="{{URL::asset('assets/plugins/notify/css/notifIt.css')}}" rel="stylesheet"/>
@endsection
@section('page-header')
				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="my-auto">
						<div class="d-flex">
							<h4 class="content-title mb-0 my-auto">الفواتير</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ قائمة الفواتير المدفوعة جزئياً
                            </span>
						</div>
					</div>
				</div>	
				<!-- breadcrumb -->
@endsection
@section('content')
				 {{-- store errors --}}
				 @if ($errors->any())
					<div class="alert alert-danger">
						<ul>
							@foreach ($errors->all() as $error)
								<li>{{ $error }}</li>
							@endforeach
						</ul>
					</div>
			 	@endif
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
				<!-- row -->
				<div class="row">
					<div class="col-xl-12">
						<div class="card mg-b-20">
							<div class="card-body">
								<div class="table-responsive">
									<table id="example" class="table key-buttons text-lg-nowrap" data-page-length="50">
										<thead>
											<tr>
												<th class="border-bottom-0">#</th>
												<th class="border-bottom-0">رقم الفاتورة</th>
												<th class="border-bottom-0">تاريخ الفاتورة</th>
												<th class="border-bottom-0">تاريخ الاستحقاق</th>
												<th class="border-bottom-0">المنتج</th>
												<th class="border-bottom-0">القسم</th>
												<th class="border-bottom-0">الخصم</th>
												<th class="border-bottom-0">نسبة الضريبة</th>
												<th class="border-bottom-0">قيمة الضريبة</th>
												<th class="border-bottom-0">الإجمالي</th>
												<th class="border-bottom-0">الحالة</th>
												{{-- <th class="border-bottom-0">ملاحظات</th> --}}
												<th class="border-bottom-0">العمليات</th>
											</tr>
										</thead>
										<tbody>
											@foreach ($invoices as $index => $invoice)
											<tr class="text-center">
												<td>{{$index + 1}}</td>
												<td>
													<a href="{{route('invoice_details.show', $invoice->id)}}">{{ $invoice->invoice_number }}</a>
												</td>
												<td>{{$invoice->invoice_Date}}</td>
												<td>{{$invoice->Due_date}}</td>
												<td>{{$invoice->product->product_name}}</td>
												<td>{{$invoice->product->section->section_name}}</td>
												<td>{{"$" . $invoice->Discount}}</td>
												<td>{{$invoice->Rate_VAT . "%"}}</td>
												<td>{{"$" . $invoice->Value_VAT}}</td>
												<td>{{"$" . $invoice->Total}}</td>

												@if ($invoice->status_id == 3)
													<td><span
															class="badge badge-pill badge-success">{{ $invoice->status->status_name }}</span>
													</td>
												@elseif($invoice->status_id == 1)
													<td><span
															class="badge badge-pill badge-danger">{{ $invoice->status->status_name }}</span>
													</td>
												@else
													<td><span
															class="badge badge-pill badge-warning">{{ $invoice->status->status_name }}</span>
													</td>
												@endif

												{{-- <td>{{$invoice->note}}</td> --}}

												{{-- العمليات --}}
												<td>
													<div class="dropdown">
														<button aria-expanded="false" aria-haspopup="true"
															class="btn ripple btn-primary btn-sm" data-toggle="dropdown"
															type="button">العمليات<i class="fas fa-caret-down ml-1"></i></button>
														<div class="dropdown-menu tx-13">
																<a class="dropdown-item"
																	href="{{route('invoices.edit', $invoice->id)}}">تعديل
																	الفاتورة</a>
		
																<a class="dropdown-item" href="#delete_model" data-invoice_id="{{ $invoice->id }}" data-effect="effect-scale"
																	data-toggle="modal" data-target="#delete_modal"><i
																		class="text-danger fas fa-trash-alt pl-2"></i>حذف
																	الفاتورة</a>
		
																<a class="dropdown-item"
																	href="{{route('invoice-status', $invoice->id)}}"><i
																		class=" text-success fas fa-money-bill pl-2"></i>تغير
																	حالة
																	الدفع</a>
		
																<a class="dropdown-item" href="#" data-invoice_id="{{ $invoice->id }}"
																	data-toggle="modal" data-target="#Transfer_invoice"><i
																		class="text-warning fas fa-exchange-alt pl-2"></i>نقل الي
																	الارشيف</a>
		
																<a class="dropdown-item" href="#"><i
																		class="text-success fas fa-print pl-2"></i>طباعة
																	الفاتورة
																</a>
														</div>
													</div>
												</td>

											</tr>
											@endforeach
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>

					
					 {{-- delete modal --}}
					 <div class="modal" id="delete_modal">
						<div class="modal-dialog modal-dialog-centered" role="document">
							<div class="modal-content modal-content-demo">
								<div class="modal-header">
									<h6 class="modal-title">حذف الفاتورة</h6><button aria-label="Close" class="close"
										data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
								</div>
								<form action="{{route('invoices.destroy', 'delete')}}" method="post">
									@method('delete')
									@csrf
									<div class="modal-body">
										<p>هل انت متاكد من عملية الحذف ؟</p><br>
										<input type="hidden" name="invoice_id" id="invoice_id" value="">
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-secondary" data-dismiss="modal">الغاء</button>
										<button type="submit" class="btn btn-danger">تاكيد</button>
									</div>
							</div>
							</form>
						</div>
					</div>
					{{-- end of delete modal --}}
			
					
				</div>
				<!-- row closed -->
			</div>
			<!-- Container closed -->
		</div>
		<!-- main-content closed -->
@endsection
@section('js')

{{-- delete an invoice --}}
<script>
	$('#delete_modal').on('show.bs.modal', function(event) {
            let button = $(event.relatedTarget)
            let invoice_id = button.data('invoice_id')
            let modal = $(this)
			console.log(invoice_id);
            modal.find('.modal-body #invoice_id').val(invoice_id);
        })
</script>

<!-- Internal Data tables -->
<script src="{{URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/jquery.dataTables.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/jszip.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/pdfmake.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/vfs_fonts.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.html5.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.print.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js')}}"></script>
<!--Internal  Datatable js -->
<script src="{{URL::asset('assets/js/table-data.js')}}"></script>

<!--Internal  Notify js -->
<script src="{{URL::asset('assets/plugins/notify/js/notifIt.js')}}"></script>
<script src="{{URL::asset('assets/plugins/notify/js/notifit-custom.js')}}"></script>
@endsection