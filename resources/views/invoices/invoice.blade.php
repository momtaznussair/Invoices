@extends('layouts.master')
@section('css')
@endsection
@section('title')
    معاينة الفاتورة
@endsection
@section('page-header')
				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="my-auto">
						<div class="d-flex">
							<h4 class="content-title mb-0 my-auto">الفواتير</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ معاينة الفواتير</span>
						</div>
					</div>
				</div>
				<!-- breadcrumb -->
@endsection
@section('content')
				<!-- row -->
				<div class="row row-sm">
					<div class="col-md-12 col-xl-12">
						<div class=" main-content-body-invoice">
							<div class="card card-invoice">
								<div class="card-body">
									<div class="invoice-header">
										<h1 class="invoice-title">فاتورة تحصيل</h1>
										<div class="billed-from">
											<h6>BootstrapDash, Inc.</h6>
											<p>201 Something St., Something Town, YT 242, Country 6546<br>
											Tel No: 324 445-4544<br>
											Email: youremail@companyname.com</p>
										</div><!-- billed-from -->
									</div><!-- invoice-header -->
									<div class="row mg-t-20">
										<div class="col-md">
											<label class="tx-gray-600">Billed To</label>
											<div class="billed-to">
												<h6>Juan Dela Cruz</h6>
												<p>4033 Patterson Road, Staten Island, NY 10301<br>
												Tel No: 324 445-4544<br>
												Email: youremail@companyname.com</p>
											</div>
										</div>
										<div class="col-md">
											<label class="tx-gray-600">معلومات الفاتورة</label>
											<p class="invoice-info-row"><span>رقم الفاتورة</span><span>{{$invoice->invoice_number}}</span></p>
											<p class="invoice-info-row"><span>القسم</span><span>{{$invoice->product->section->section_name}}</span></p>
											<p class="invoice-info-row"><span>تاريخ الإصدار</span><span>{{$invoice->invoice_Date}}</span></p>
											<p class="invoice-info-row"><span>تاريخ الإستحقاق</span><span>{{$invoice->Due_date}}</span></p>
										</div>
									</div>
									<div class="table-responsive mg-t-40">
										<table class="table table-invoice border text-md-nowrap mb-0">
											<thead>
												<tr>
													<th class="wd-20p">المنتج</th>
													<th class="wd-40p">مبلغ التحصيل</th>
													<th class="tx-center">العمولة</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>{{$invoice->product->product_name}}</td>
													<td class="tx-12">{{"$".$invoice->Amount_collection}}</td>
													<td class="tx-center">{{"$".$invoice->Amount_Commission}}</td>
												</tr>
												<tr>
													<td class="valign-middle" colspan="2" rowspan="4">
														<div class="invoice-notes">
															<label class="main-content-label tx-13">Notes</label>
															<p class="text-dark">{{$invoice->note}}</p>
														</div><!-- invoice-notes -->
													</td>
                                                    <td class="tx-right">الخصم</td>
                                                    <td class="tx-right" colspan="2">{{"$".$invoice->Discount }}</td>
												</tr>
												
                                                <tr>
													<td class="tx-right">العمولة بعد الخصم</td>
													<td class="tx-right" colspan="2">{{"$". ($invoice->Amount_Commission -  $invoice->Discount) }}</td>
												</tr>
												<tr>
													<td class="tx-right">ضريبة القيمة المضافة ( {{"%".$invoice->Rate_VAT}} )</td>
													<td class="tx-right" colspan="2">{{"$".$invoice->Value_VAT}}</td>
												</tr>
												<tr>
													<td class="tx-right tx-uppercase tx-bold tx-inverse">الإجماي شامل الضريبة</td>
													<td class="tx-right" colspan="2">
														<h4 class="tx-primary tx-bold">{{"$".$invoice->Total}}</h4>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
									<hr class="mg-b-40">
									<a class="btn btn-purple float-left mt-3 mr-2" href="">
										<i class="mdi mdi-currency-usd ml-1"></i>Pay Now
									</a>
									<a href="#" class="btn btn-danger float-left mt-3 mr-2">
										<i class="mdi mdi-printer ml-1"></i>Print
									</a>
									<a href="#" class="btn btn-success float-left mt-3">
										<i class="mdi mdi-telegram ml-1"></i>Send Invoice
									</a>
								</div>
							</div>
						</div>
					</div><!-- COL-END -->
				</div>
				<!-- row closed -->
			</div>
			<!-- Container closed -->
		</div>
		<!-- main-content closed -->
@endsection
@section('js')
<!--Internal  Chart.bundle js -->
<script src="{{URL::asset('assets/plugins/chart.js/Chart.bundle.min.js')}}"></script>
@endsection