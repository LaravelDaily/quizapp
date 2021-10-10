<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Section;
use App\Models\Question;
use App\Models\QuizHeader;

class AppUserDashboardController extends Controller
{
    public function __invoke()
    {
        $activeUsers = User::count();

        $questionsCount = Question::where('is_active', '1')->count();

        $sections = Section::withCount('questions')
            ->where('is_active', '1')
            ->orderBy('name')
            ->get();

        $quizesTaken = QuizHeader::count();

        $userQuizzes = auth()
            ->user()
            ->quizHeaders()
            ->orderBy('id', 'desc')
            ->paginate(10);

        $quizAverage = auth()->user()->quizHeaders()->avg('score');

        return view(
            'appusers.userQuizHome',
            compact(
                'sections',
                'activeUsers',
                'questionsCount',
                'quizesTaken',
                'userQuizzes',
                'quizAverage'
            )
        );
    }
}
