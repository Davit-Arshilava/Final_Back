<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuizRequest;
use App\Http\Resources\QuizResource;
use App\Models\Quiz;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class QuizController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        $data = Quiz::select()->get();
        return QuizResource::collection($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param QuizRequest $request
     * @return Response
     */
    public function store(QuizRequest $request)
    {
        auth()->user()->quizzes()->create($request->validated());

        return response([
            'message' => 'Quiz has created'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Quiz $quiz
     * @return Application|ResponseFactory|Response
     */
    public function show(Quiz $quiz)
    {
        return response([
            'data' => new QuizResource($quiz)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param QuizRequest $request
     * @param Quiz $quiz
     * @return Response
     */
    public function update(QuizRequest $request, Quiz $quiz)
    {
        $quiz->update($request->validated());

        return response([
            'message' => 'Quiz has updated'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Quiz $quiz
     * @return Response
     */
    public function destroy(Quiz $quiz)
    {
        $quiz->delete();

        return response([
            'message' => 'Quiz has deleted'
        ]);
    }
}
