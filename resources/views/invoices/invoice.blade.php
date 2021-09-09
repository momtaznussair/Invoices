@extends('layouts.master')
@section('css')
	<style>
		@media print {
			#print_button{
				display: none;
			}
		}
	</style>
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
						<div class="main-content-body-invoice">
							<div class="card card-invoice">
								<div class="card-body" id="printContent">
									<div class="invoice-header">
										<h1 class="invoice-title">فاتورة تحصيل</h1>
										<div class="billed-from">
											<h6>MomtazNussair, Inc.</h6>
											<p>201 dev St., faraskour, Damietta, Egypt<br>
											Tel No: +2 1015 447-889<br>
											Email: momtaznussair97@gmail.com</p>
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
													<th class="" colspan="2">المنتج</th>
													<th class="">مبلغ التحصيل</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td colspan="2">{{$invoice->product->product_name}}</td>
													<td class="tx-12">{{"$".$invoice->Amount_collection}}</td>
												</tr>
												<tr>
													<td class="valign-middle" colspan="2" rowspan="5">
														<div class="invoice-notes">
															<label class="main-content-label tx-13">ملاحظات</label>
															<p class="text-dark">{{$invoice->note}}</p>
														</div><!-- invoice-notes -->
													</td>

                                                    <th class="tx-right">العمولة</th>
													<td class="">{{"$".$invoice->Amount_Commission}}</td>
												</tr>
												<tr>
                                                    <th class="tx-right">الخصم</th>
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
									<a href="#" class="btn btn-danger float-left ml-5 mb-1" id="print_button" onclick="printInvoice()">
										<i class="mdi mdi-printer ml-1"></i>Print
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
<script>
	// $(document).ready(function () {

		function printInvoice() {
		let content = document.getElementById('printContent').innerHTML;
		let WholePage = document.body.innerHTML;
		document.body.innerHTML = content;
		window.print();
		document.body.innerHTML = WholePage;
		location.reload();
	}		
	// });
</script>
@endsection