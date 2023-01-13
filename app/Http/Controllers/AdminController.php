<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminQuizUpdateRequest;
use App\Http\Resources\QuizResource;
use App\Models\Quiz;
use App\Scopes\FilterByAuthUser;

class AdminController extends Controller
{
    public function index()
    {
        $data = Quiz::withoutGlobalScope(FilterByAuthUser::class)->get();

        return QuizResource::collection($data);
    }

    public function update(AdminQuizUpdateRequest $request, $id)
    {
        $validated = $request->validated();

        $quiz = Quiz::withoutGlobalScope(FilterByAuthUser::class)->where('id', $id);
        $quiz->update(['is_published' => $validated['is_published']]);

        return response(['message' => 'Quiz has updated']);
    }
}
