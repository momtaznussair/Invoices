<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:users list', ['only' => ['index']]);
        $this->middleware('permission:add user', ['only' => ['create','store']]);
        $this->middleware('permission:edit user', ['only' => ['edit','update']]);
        $this->middleware('permission:delete user', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = User::orderBy('id', 'DESC')->paginate(5);
        return view('users.index', compact('users'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::pluck('name', 'name')->all();
        return view('users.add', ['roles' => $roles]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate(
            $request,
            [
                'name' => 'required|max:255',
                'email' => 'required|max:255|unique:users,email',
                'password' => 'required|max:255|confirmed',
                'account_status' => 'required|in:active,suspended',
                'roles' => 'required|exists:roles,name'
            ],
            $this->messages()
        );

        $valid_request = $request->all();

        $valid_request['password'] = Hash::make($valid_request['password']);

        $user = User::create($valid_request);
        $user->assignRole($request->roles);
        return redirect()->route('users.index')
            ->with('success', 'تم اضافة الستخدم بنجاح');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        return view('users.show', compact('user'));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::pluck('name', 'name')->all();
        $userRole = $user->roles->pluck('name', 'name')->all();
        return view('users.edit', compact('user', 'roles', 'userRole'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate(
            $request,
            [
                'name' => 'required|max:255',
                'email' => 'required|max:255|unique:users,email,'. $id,
                'password' => 'nullable|max:255|confirmed',
                'account_status' => 'required|in:active,suspended',
                'roles' => 'required|exists:roles,name'
            ],
            $this->messages()
        );

        $valid_request = $request->all();
        if ($request->password){

             $valid_request['password'] = Hash::make($valid_request['password']);
        }else{
            $valid_request = Arr::except($valid_request, ['password']);
        }
        
        $user = User::findOrFail($id);
        $user->update($valid_request);

        DB::table('model_has_roles')->where('model_id', $id)->delete();
        $user->assignRole($request->input('roles'));
        return redirect()->route('users.index')
            ->with('success', 'تم حفظ التعديلات');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = $request->user_id;
        User::findOrFail($id)->delete();
        return redirect()->route('users.index')
            ->with('success', 'تم حذف المستخدم');
    }

    // validaton  messages
    private function messages()
    {
        return[
            'name.required' => 'يرجى ادخال اسم المستخدم',
            'name.max' => 'اسم المستخدم لا يجب ان يتجاوز 255 حرف',
            'email.required' => 'يرجي ادخال البريد الإلكتروني للمستخدم',
            'email.max' => 'البريد الإلكتروني لا يجب أن يتجاوز 255 حرف',
            'email.unique' => 'البريد الإلكتروني مسجل بالفعل',
            'password.required' => 'يرجى ادخال رقم المرور للمستخدم',
            'password.max' => 'رقم المرور لا يجب أن يتجاوز 255 حرف',
            'password.confirmed' => 'تأكيد كلمة المرور مختلف',
            'account_status.required' => 'يرجى اختيار حالة المستخدم',
            'account_status.in' => 'خطأ في حالة المستخدم',
            'roles.required' => 'يرجى اختيار نوع واحد على الأقل',
            'roles.existes' => 'خطأ في النوع',
        ];
    }
}
