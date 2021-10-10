<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class SectionsController extends Controller
{
    public function index()
    {
        $sections = Section::withCount('questions')->paginate(10);
        //$sections = Section::where('is_active', '1')->paginate(5);
        return view('admins.list_sections', compact('sections'));
    }

    public function create()
    {
        return view('admins.create_section');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'section.*' => 'required',
        ]);
        auth()->user()->sections()->createMany($data);
        return redirect()->route('sections.index')->with('success', 'Section created successfully!');
    }

    public function edit(Section $section)
    {
        return view('admins.edit_section', compact('section'));
    }

    public function update(Section $section, Request $request)
    {
        $data = $request->validate([
            'name' => 'required|min:5|max:255',
            'description' => 'required|min:5|max:255',
            'is_active' => 'required',
            'details' =>    'required|min:10|max:1024',
        ]);

        $section->update($data);

        session()->flash('success', 'Section saved successfully!');
        return redirect()->route('sections.index');
    }

    public function show(Section $section)
    {
        $questions = $section->questions()->paginate(10);
        return view('admins.detail_sections', compact('questions', 'section'));
    }

    public function destroy(Section $section)
    {
        //$sections = Section::paginate(10);
        $section->delete();
        return redirect()->back()->withSuccess('Section with id: ' . $section->id . ' deleted successfully');
    }
}
