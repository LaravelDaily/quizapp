<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\User;
use App\Models\Section;
use App\Models\Question;
use App\Models\QuizHeader;

class AppUserController extends Controller
{
    public function create()
    {
        return view('appusers.quiz');
    }

    public function show(QuizHeader $quiz)
    {
        // Answers with alphabetical choice
        $choice = collect(['A', 'B', 'C', 'D']);

        //Get quiz summary record for the given quiz
        $userQuizDetails = $quiz->with('section')->first();

        //Extract question taken by the users stored as a serialized string while takeing the quiz
        $quizQuestionsList = collect(unserialize($userQuizDetails->questions_taken));

        //Get the actual quiz questiona and answers from Quiz table using quiz_header_id
        $userQuiz = Quiz::where('quiz_header_id', $userQuizDetails->id)
            ->orderBy('question_id', 'ASC')->get();
        //dd($userQuiz);
        //Get the Questions and related answers taken by the user during the quiz
        $quizQuestions = Question::whereIn('id', $quizQuestionsList)->orderBy('id', 'ASC')->with('answers')->get();

        //pass the data using compact to the view to display
        return view(
            'appusers.userQuizDetail',
            compact(
                'userQuizDetails',
                'quizQuestionsList',
                'userQuiz',
                'quizQuestions',
                'choice'
            )
        );
    }

    public function destroy(QuizHeader $quizHeader)
    {
        if (auth()->id() == $quizHeader->user_id) {
            $quizHeader->delete();
            return redirect()->back()
                ->withSuccess("Quiz deleted successfully!");
        }
        return redirect()->back()->withWarning("Can not delete quiz!");
    }
}
