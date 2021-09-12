@extends('layouts.master')
@section('css')
    <!--Internal   Notify -->
    <link href="{{ URL::asset('assets/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
@section('title')
صلاحيات المستخدمين - برنامج الفواتير 
@stop


@endsection
@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">المستخدمين</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0"> /
                صلاحيات المستخدمين</span>     
        </div>
    </div>
</div>
<!-- breadcrumb -->
@endsection
@section('content')


@if (session()->has('success'))
    <script>
        window.onload = function() {
            notif({
                msg: "{{session()->get('success')}}",
                type: "success"
            });
        }
    </script>
@endif

<!-- row -->
<div class="row row-sm">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header pb-0">
                <div class="d-flex justify-content-between">
                    <div class="col-lg-12 margin-tb">
                        <div class="pull-right">
                            @can('role-create')
                                <a class="btn btn-primary btn-sm" href="{{ route('roles.create') }}">اضافة</a>
                            @endcan
                        </div>
                    </div>
                    <br>
                </div>

            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table mg-b-0 text-md-nowrap table-hover ">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>الاسم</th>
                                <th class="pr-5">العمليات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($roles as $index => $role)
                                <tr>
                                    <td>{{ ++$index }}</td>
                                    <td>{{ $role->name }}</td>
                                    <td>
                                        <a class="btn btn-success btn-sm"
                                            href="{{ route('roles.show', $role->id) }}">عرض</a>
                                        @can('role-edit')
                                            <a class="btn btn-primary btn-sm"
                                            href="{{ route('roles.edit', $role->id) }}">تعديل</a> 
                                        @endcan

                                        @can('role-delete')
                                            @if ($role->name !== 'Admin')
                                            <a class="modal-effect btn btn-sm btn-danger" data-effect="effect-scale"
                                            data-id="{{ $role->id }}"
                                            data-name="{{ $role->name }}" data-toggle="modal"
                                            href="#delete_modal" title="حذف"><i class="las la-trash"></i></a>
                                                {!! Form::close() !!}
                                            @endif 
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
    <!--/div-->

        {{-- delete modal --}}
        <div class="modal" id="delete_modal">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content modal-content-demo">
                    <div class="modal-header">
                        <h6 class="modal-title">حذف الصلاحية</h6><button aria-label="Close" class="close"
                            data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <form action="{{route('roles.destroy', 'delete')}}" method="post">
                        @method('delete')
                        @csrf
                        <div class="modal-body">
                            <p>هل انت متاكد من الصلاحية ؟</p><br>
                            <input type="hidden" name="id" id="id" value="">
                            <input class="form-control" name="name" id="name" type="text" readonly>
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
<!--Internal  Notify js -->
<script src="{{ URL::asset('assets/plugins/notify/js/notifIt.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/notify/js/notifit-custom.js') }}"></script>

{{-- dat from delete button to modal --}}
<script>
    $('#delete_modal').on('show.bs.modal', function(event) {
        let button = $(event.relatedTarget)
        let id = button.data('id')
        let name = button.data('name')
        let modal = $(this)
        modal.find('.modal-body #id').val(id);
        modal.find('.modal-body #name').val(name);
    })
</script>
@endsection