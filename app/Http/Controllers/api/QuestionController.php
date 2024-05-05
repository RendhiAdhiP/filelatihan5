<?php

namespace App\Http\Controllers\Api;

use App\Models\Form;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Question;

class QuestionController extends Controller
{
    public function addQuest($slug, Request $request)
    {
        $form = Form::where('slug', $slug)->with('allowedDomain', 'question')->first();

        if ($form == null) return response()->json(['message' => 'Form not found'], 404);

        $user = $request->user()->id;

        if($user != $form->creator_id) return response()->json(['message'=>'forbiden access'],403);

        $validate = $request->validate([
            'name' => 'required',
            'type' => 'required|in:short answer,paragraph,date,multiple choice,dropdown,checkboxes',
            'choices' => 'required_if:choice type,multiple choice','dropdown','multiple choice','checkboxes|array',
        ]);

        if (isset($validate['choices'])) {
            $validate['choices'] = trim(json_encode($validate['choices']), '[],"');
        }

        $quest = Question::create([
            'name' => $validate['name'],
            'type' => $validate['type'],
            'is_required' => $validate['is_required'] ?? 1,
            'choices' => $validate['choices'] ?? null,
            'form_id' => $form->id,
        ]);


        $i = [
            'name' => $quest->name,
            'choice_type' => $quest->type,
            'choices' => $quest->choices,
            'form_id' => $quest->form_id,
            'id' => $quest->id,
        ];

        return response()->json(['message' => 'Add question success', 'form' => $i], 200);

    }

    public function delete($slug, $id,Request $request){
        $form = Form::where('slug', $slug)->with('allowedDomain', 'question')->first();

        if ($form == null) return response()->json(['message' => 'Form not found'], 404);

        $quest = Question::where('id',$id)->first();

        if ($quest == null) return response()->json(['message' => 'Question not found'], 404);

        $user = $request->user()->id;

        if($user != $form->creator_id) return response()->json(['message'=>'forbiden access'],403);


        $quest->delete();

        return response()->json(['message' => 'Remove question success'], 200);



    }
}
