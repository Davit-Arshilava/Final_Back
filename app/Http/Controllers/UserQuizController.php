<?php

namespace App\Http\Controllers;

use App\Http\Resources\AnswerResource;
use App\Http\Resources\QuizResource;
use App\Models\Answer;
use App\Models\Question;
use App\Models\Quiz;
use App\Scopes\FilterByAuthUser;
use Illuminate\Support\Facades\DB;

class UserQuizController extends Controller
{

    public function index()
    {
        $data = Quiz::withoutGlobalScope(FilterByAuthUser::class)
            ->with('author')
            ->where('user_id', '!=', auth()->user()->id)
            ->where('is_published', 1)->get();

        return QuizResource::collection($data);
    }

    public function start($id)
    {
        $quiz = Quiz::withoutGlobalScope(FilterByAuthUser::class)->find($id);

        $quiz->users()->attach(auth()->user()->id);
    }

    public function check($id, Question $question, Answer $answer)
    {
        if ($answer->is_correct && $answer->question_id === $question->id) {
            //increase score
            DB::table('quiz_user')
                ->where(['user_id' => auth()->user()->id, 'quiz_id' => $id])
                ->increment('total_score');
        }
    }

    public function end($id)
    {
        $user = auth()->user();
        $quiz = Quiz::withoutGlobalScope(FilterByAuthUser::class)->find($id);

        return response([
            'quiz' => $quiz->title,
            'quiz_taker' => auth()->user()->name,   //request user
            'score' => $user->other_quizzes()->withoutGlobalScope(FilterByAuthUser::class)
                ->where('quiz_id', $id)->first()->pivot->total_score
        ]);
    }
}
