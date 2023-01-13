<?php

namespace App\Http\Controllers;

use App\Http\Requests\Question\QuestionStoreRequest;
use App\Http\Requests\Question\QuestionUpdateRequest;
use App\Http\Resources\QuestionResource;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Http\Response;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $data = auth()->user()->questions;

        return response([
            'data' => QuestionResource::collection($data)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param QuestionStoreRequest $request
     * @return Response
     */
    public function store(QuestionStoreRequest $request)
    {
        $data = $request->validated();

        $quiz = Quiz::find($data['quiz_id']);
        if (!$quiz->user_id === auth()->user()->id) return response(['message' => "You don't have access"]);

        $question = $quiz->questions()->create([
            'title' => $data['title']
        ]);

        $question->answers()->createMany($data['answers']);

        return response(['message' => 'question has created']);
    }

    /**
     * Display the specified resource.
     *
     * @param Question $question
     * @return Response
     */
    public function show(Question $question)
    {
        $question = auth()->user()->questions()->where('id', $question->id);

        return response([
            'data' => new QuestionResource($question)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param QuestionUpdateRequest $request
     * @param Question $question
     * @return Response
     */
    public function update(QuestionUpdateRequest $request, Question $question)
    {
        $data = $request->validated();
        $question->update(['title' => $data['title']]);

        foreach ($data['answers'] as $answer) {
            $question->answers()->updateOrCreate(
                ['id' => $answer['id']],
                ['text' => $answer['text'], 'is_correct' => $answer['is_correct']]
            );
        }

        return response([
            'message' => 'Question has updated'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Question $question
     * @return Response
     */
    public function destroy(Question $question)
    {
        auth()->user()->questions()->where('id', $question->id)->delete();

        return response([
            'message' => 'question has deleted'
        ]);
    }
}
