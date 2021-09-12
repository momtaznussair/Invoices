@extends('layouts.master')

@section('title')
	حالات الفواتير
@endsection
@section('css')
<!-- Internal Data table css -->
<link href="{{URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
@endsection
@section('page-header')
				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="my-auto">
						<div class="d-flex">
							<h4 class="content-title mb-0 my-auto">الاعدادات</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ حالات الفواتير</span>
						</div>
					</div>
					<div class="d-flex my-xl-auto right-content">
						
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
					<div class="col-xl-12">
						<div class="card mg-b-20">
							<div class="card-header pb-0">
								<div class="d-flex justify-content-between">
									<div class="col-sm-6 col-md-4 col-xl-3">
										@can('add status')
											<a class="modal-effect btn btn-outline-primary btn-block" data-effect="effect-scale" data-toggle="modal" href="#modaldemo8">اضافة حالة</a>
										@endcan
									</div>
								</div>
							</div>
							<div class="card-body">
								<div class="table-responsive">
									<table id="example1" class="table key-buttons text-md-nowrap" data-page-length="50">
										<thead>
											<tr class="text-center">
												<th class="border-bottom-0">#</th>
												<th class="border-bottom-0">اسم الحالة</th>
												<th class="border-bottom-0">ملاحظات</th>
												<th class="border-bottom-0">ألعمليات</th>
											</tr>
										</thead>
										<tbody>
											@foreach ($statuses  as $index => $status)
											<tr class="text-center">
												<td>{{$index + 1}}</td>
												<td>{{$status->status_name}}</td>
												<td>{{$status->description}}</td>
												<td>
													@can('edit status')
														<a class="modal-effect btn btn-sm btn-info" data-effect="effect-scale"
														data-id="{{ $status->id }}" data-status_name="{{ $status->status_name }}"
														data-description="{{ $status->description }}" data-toggle="modal"
														href="#exampleModal2" title="تعديل"><i class="las la-pen"></i></a>	
													@endcan
													
													@can('delete status')
														<a class="modal-effect btn btn-sm btn-danger" data-effect="effect-scale"
														data-id="{{ $status->id }}" data-status_name="{{ $status->status_name }}"
														data-toggle="modal" href="#modaldemo9" title="حذف"><i
															class="las la-trash"></i></a>
													@endcan
												</td>
											</tr>
											@endforeach
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					{{-- add a status modal --}}
					<div class="modal fade" id="modaldemo8">
						<div class="modal-dialog modal-dialog-centered" role="document">
							<div class="modal-content modal-content-demo">
								<div class="modal-header">
									<h6 class="modal-title">اضافة حالة</h6><button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
								</div>
								<div class="modal-body">
									<form action="{{ route('statuses.store') }}" method="post" autocomplete="off">
										{{ csrf_field() }}
										
										<div class="form-group">
											<label for="exampleInputEmail1">اسم الحالة</label>
											<input type="text" class="form-control" id="status_name" name="status_name">
										</div>

										<div class="form-group">
											<label for="exampleFormControlTextarea1">ملاحظات</label>
											<textarea class="form-control" id="desc" name="description" rows="3"></textarea>
										</div>
										
										<div class="modal-footer">
											<button class="btn ripple btn-primary" type="submit">اضافة</button>
											<button class="btn ripple btn-secondary" data-dismiss="modal" type="button">الغاء</button>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
					{{--end of  add a status modal --}}


					{{-- edit a status modal --}}
					<div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
					aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="exampleModalLabel">تعديل الحالة</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">

								<form action="statuses/update" method="post" autocomplete="off">
									{{ method_field('put') }}
									{{ csrf_field() }}
									<div class="form-group">
										<input type="hidden" name="id" id="id" value="">
										<label for="recipient-name" class="col-form-label">اسم الحالة:</label>
										<input class="form-control" name="status_name" id="old_status_name" type="text">
									</div>
									<div class="form-group">
										<label for="message-text" class="col-form-label">ملاحظات:</label>
										<textarea class="form-control" id="description" name="description"></textarea>
									</div>
							</div>
							<div class="modal-footer">
								<button type="submit" class="btn btn-primary">تاكيد</button>
								<button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
							</div>
							</form>
						</div>
					</div>
				</div>

				{{-- end of edit a status modal --}}


				{{-- delete modal --}}
				 <div class="modal" id="modaldemo9">
					<div class="modal-dialog modal-dialog-centered" role="document">
						<div class="modal-content modal-content-demo">
							<div class="modal-header">
								<h6 class="modal-title">حذف الحالة</h6><button aria-label="Close" class="close" data-dismiss="modal"
									type="button"><span aria-hidden="true">&times;</span></button>
							</div>
							<form action="statuses/destroy', $status->id)}}" method="post">
								{{ method_field('delete') }}
								{{ csrf_field() }}
								<div class="modal-body">
									<p>هل انت متاكد من عملية الحذف ؟</p><br>
									<input type="hidden" name="id" id="product_id" value="">
									<input class="form-control" name="status_name" id="del_status_name" type="text" readonly>
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
<!-- Internal Select2 js-->
<script src="{{URL::asset('assets/plugins/select2/js/select2.min.js')}}"></script>
<!-- Internal Modal js-->
<script src="{{URL::asset('assets/js/modal.js')}}"></script>


{{-- modals data --}}
{{-- edit --}}
<script>
    $('#exampleModal2').on('show.bs.modal', function(event) {
        let button = $(event.relatedTarget)
        let id = button.data('id')
        let status_name = button.data('status_name')
        let description = button.data('description')
        let modal = $(this)
        modal.find('.modal-body #id').val(id);
        modal.find('.modal-body #old_status_name').val(status_name);
        modal.find('.modal-body #description').val(description);
    })
</script>
{{-- delete --}}

<script>
    $('#modaldemo9').on('show.bs.modal', function(event) {
        let button = $(event.relatedTarget)
        let id = button.data('id')
        let status_name = button.data('status_name')
        let modal = $(this)
        modal.find('.modal-body #product_id').val(id);
        modal.find('.modal-body #del_status_name').val(status_name);
    })
</script>
@endsection