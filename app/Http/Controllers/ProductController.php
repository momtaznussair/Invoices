<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\Section;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:products', ['only' => ['index']]);
        $this->middleware('permission:view product', ['only' => ['show']]);
        $this->middleware('permission:add product', ['only' => ['create','store']]);
        $this->middleware('permission:edit product', ['only' => ['edit','update']]);
        $this->middleware('permission:delete product', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sections = Section::all();
        $products = Product::all();
        return view('products.products', ['sections' => $sections, 'products' => $products]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = $request->validate(
            [
                'product_name' => 'required|unique:products,product_name|max:255',
                'section_id' => 'required|exists:sections,id',
                'description' => 'max:999',
            ],
            [
                'product_name.required' => 'يرجي ادخال اسم المنتج',
                'product_name.unique' => 'اسم المنتج مدخل مسبقا',
                'section_id.required' => 'يرجى اختيار القسم',
                'section_name.max' => 'الحد الاقصى لاسم القسم هو 999 حرف',
            ]
        );

        if ($validator) {
            $product = Product::create([
                'product_name' => $request->product_name,
                'section_id' => $request->section_id,
                'description' => $request->description,
            ]);

            session()->flash('Add', 'تم اضافة المنتج بنجاح ');
            return redirect('/products');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validator = $request->validate(
            [
                'product_name' => "required|string|max:255|unique:products,product_name, $request->pro_id",
                'section_id' => 'required|exists:sections,id',
                'description' => 'max:999',
            ],
            [
                'product_name.required' => 'يرجي ادخال اسم المنتج',
                'product_name.unique' => 'اسم المنتج مدخل مسبقا',
                'section_id.required' => 'يرجى اختيار القسم',
                'product_name.max' => 'الحد الاقصى لاسم المنتج هو 255 حرف',
            ]
        );

        $product = Product::findOrFail($request->pro_id);

        $product->update([
            'product_name' => $request->product_name,
            'description' => $request->description,
            'section_id' => $request->section_id,
        ]);

        session()->flash('Edit', 'تم تعديل المنتج بنجاح');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $Product = Product::findOrFail($request->id);
        $Product->delete();
        session()->flash('delete', 'تم حذف المنتج بنجاح');
        return back();
    }
}
