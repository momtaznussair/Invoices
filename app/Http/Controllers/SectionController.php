<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sections = Section::all();
        return view('sections.sections', ['sections' => $sections]);
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
        $validator = $request->validate([
            'section_name' => 'required|unique:sections,section_name|max:255',
            'description' => 'required',
        ],
        [
            'section_name.required' => 'يرجي ادخال اسم القسم',
            'section_name.unique' => 'اسم القسم مدخل مسبقا',
            'section_name.max' => 'الحد الاقصى لاسم القسم هو 255 حرف',
            'description.required' => 'يرجي ادخال وصف القسم',
        ]);

        if ($validator)
        {
            $section = Section::create([
                'section_name' => $request->section_name,
                'description' => $request->description,
                'created_by' => Auth::user()->name,
            ]);
            
            session()->flash('Add', 'تم اضافة القسم بنجاح ');
            return redirect('/sections');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function show(Section $section)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function edit(Section $section)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $id = $request->id;
        $validator = $request->validate([
            'section_name' => "required|max:255|unique:sections,section_name,". $id,
            'description' => 'required',
        ],
        [
            'section_name.required' => 'يرجي ادخال اسم القسم',
            'section_name.unique' => 'اسم القسم مدخل مسبقا',
            'section_name.max' => 'الحد الاقصى لاسم القسم هو 255 حرف',
            'description.required' => 'يرجي ادخال وصف القسم',
        ]);

        if ($validator)
        {
            $section = Section::find($id);
            if ($section)
            {
                $section->update([
                    'section_name' => $request->section_name,
                    'description' => $request->description,
                ]);
                
                session()->flash('Add', 'تم تعديل القسم بنجاح ');
                return redirect('/sections');
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = $request->id;
        Section::find($id)->delete();
        session()->flash('delete','تم حذف القسم بنجاح');
        return redirect('/sections');
    }
}
