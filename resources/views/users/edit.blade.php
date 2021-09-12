@extends('layouts.master')
@section('css')
<link href="{{URL::asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
<!-- Internal Nice-select css  -->
<link href="{{URL::asset('assets/plugins/jquery-nice-select/css/nice-select.css')}}" rel="stylesheet" />
@section('title')
تعديل مستخدم 
@stop


@endsection
@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">المستخدمين</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ تعديل
                مستخدم</span>
        </div>
    </div>
</div>
<!-- breadcrumb -->
@endsection
@section('content')
<!-- row -->
<div class="row">
    <div class="col-lg-12 col-md-12">

        @if (count($errors) > 0)
        <div class="alert alert-danger">
            <button aria-label="Close" class="close" data-dismiss="alert" type="button">
                <span aria-hidden="true">&times;</span>
            </button>
            <strong>يرجى تصحيح الآتي</strong>
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="card">
            <div class="card-body">
                <div class="col-lg-12 margin-tb">
                    <div class="pull-right">
                        <a class="btn btn-primary btn-sm" href="{{ route('users.index') }}">رجوع</a>
                    </div>
                </div><br>

                <form action="{{route('users.update', $user->id)}}" method="post" autocomplete="off">
                @method('put')
                @csrf
                <div class="">

                    <div class="row mg-b-20">
                        <div class="parsley-input col-md-6" id="fnWrapper">
                            <label>اسم المستخدم: <span class="tx-danger">*</span></label>
                            <input class="form-control form-control-sm mg-b-20" data-parsley-class-handler="#lnWrapper" type="text" name="name" id="name" required value="{{$user->name}}">
                        </div>

                        <div class="parsley-input col-md-6 mg-t-20 mg-md-t-0" id="lnWrapper">
                            <label>البريد الالكتروني: <span class="tx-danger">*</span></label>
                            <input class="form-control form-control-sm mg-b-20" data-parsley-class-handler="#lnWrapper" type="email" name="email" id="email" required value="{{$user->email}}">
                        </div>
                    </div>

                </div>

                <div class="row mg-b-20">
                    <div class="parsley-input col-md-6 mg-t-20 mg-md-t-0" id="lnWrapper">
                        <label for="password">كلمة المرور:</label>
                        <input class="form-control form-control-sm mg-b-20" data-parsley-class-handler="#lnWrapper" type="password" name="password" id="password">
                    </div>

                    <div class="parsley-input col-md-6 mg-t-20 mg-md-t-0" id="lnWrapper">
                        <label> تاكيد كلمة المرور:</label>
                        <input class="form-control form-control-sm mg-b-20" data-parsley-class-handler="#lnWrapper" type="password" name="password_confirmation" id="confirm">
                    </div>
                </div>

                <div class="row row-sm mg-b-20">
                    <div class="col">
                        <label class="form-label font-weight-bold mt-1">حالة المستخدم<span class="tx-danger mr-1">*</span></label>
                        <select class="form-control" name="account_status" id="select-beast" class="form-control  nice-select  custom-select" required>
                            @if ($user->account_status == 'active')
                                <option class="text-success" value="active" selected>مفعل</option>
                                <option class="text-danger" value="suspended">غير مفعل</option>
                            @else
                                <option class="text-success" value="active">مفعل</option>
                                <option class="text-danger" value="suspended" selected>غير مفعل</option>
                            @endif
                        </select>
                    </div>

                    <div class="col">
                        <p class="mg-b-10 font-weight-bold">نوع المستخدم<span class="tx-danger mr-1">*</span></p>
                        <select class="form-control select2" name="roles[]" multiple="multiple" required>
                            @foreach ($roles as $role)
                                    @if (in_array($role, $userRole))
                                        <option value="{{$role}}" selected>{{$role}}</option>
                                    @else
                                        <option value="{{$role}}">{{$role}}</option>
                                    @endif
                            @endforeach
                        </select>
                    </div>
                </div>

               
                <div class="mg-t-30">
                    <button class="btn btn-main-primary pd-x-20" type="submit">تحديث</button>
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

<script src="{{URL::asset('assets/plugins/select2/js/select2.min.js')}}"></script>

<!-- Internal Nice-select js-->
<script src="{{URL::asset('assets/plugins/jquery-nice-select/js/jquery.nice-select.js')}}"></script>
<script src="{{URL::asset('assets/plugins/jquery-nice-select/js/nice-select.js')}}"></script>

<!--Internal  Parsley.min js -->
<script src="{{URL::asset('assets/plugins/parsleyjs/parsley.min.js')}}"></script>
<!-- Internal Form-validation js -->
<script src="{{URL::asset('assets/js/form-validation.js')}}"></script>

@endsection
