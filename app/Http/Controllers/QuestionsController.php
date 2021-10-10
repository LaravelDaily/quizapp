<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Section;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class QuestionsController extends Controller
{
    public function create(Section $section)
    {
        return view('admins.create_question', compact('section'));
    }

    public function show(Question $question)
    {
        $answers = $question->answers()->paginate(10);
        return view('admins.detail_question', compact('question', 'answers'));
    }

    public function store(Section $section, Request $request)
    {
        $data = $request->validate([
            'question' => ['required', Rule::unique('questions')],
            'explanation' => 'required',
            'is_active' => 'required',
            'answers.*.answer' => 'required',
            'answers.*.is_checked' => 'present'
        ]);

        $question = Question::create([
            'question' => $request->question,
            'explanation' => $request->explanation,
            'is_active' => $request->is_active,
            'user_id' => Auth::id(),
            'section_id' => $section->id,
        ]);

        $question->answers()->createMany($data['answers'])->push();
        return redirect()->route('sections.show', $section->id)
            ->withSuccess('Question created successfully');;
    }

    public function destroy(Question $question)
    {
        $question->delete();
        return redirect()->route('sections.show', $question->section->id)
            ->withSuccess('Question with id: ' . $question->id . ' deleted successfully');
    }
}
