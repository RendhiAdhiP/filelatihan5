<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Form;
use App\Models\Response;
use Illuminate\Http\Request;

class ResponseController extends Controller
{
    // public function response($slug, Request $request){
    //     $email = $request->user()->email;
    //     $domain= explode('@',$email);

    //     $form = Form::where('slug', $slug)->with('allowedDomain', 'question')->first();
    //     if ($form == null) return response()->json(['message' => 'Form not found'], 404);
    //     $formDomain = $form->allowedDomain->map(function($a){
    //         return $a->pluck('domain');
    //     });

    //     if($formDomain != null){
    //         if($domain != $form->domainn){
    //             return response()->json(['message'=>'gk bisa'],403);
    //         }
    //     }

    //     return response()->json(['message' => 'Remove question success',$domain[1],$formDomain], 200);

    //}

    public function responseAnswer($slug,Request $request){
        $validate = $request->validate([
            'answer'=>'array|required',
            'answer.*.question_id'=>'required_with:answer.*'
        ]);
        
        $form = Form::where('slug',$slug)->with('question')->first();
        
        $response = Response::create([
            'form_id'=>$form->id,
            'user_id'=>$request->user()->id,
            'date'=>now(),
        ]);

        $answer = collect($request->answer);


                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   
        // $answer->each(function($a)use($response){
        //     $response->answer()->create([
        //         'question_id'=>$a['question_id'],
        //         'value'=>$a['value'],
        //     ]);
        // });

        // $request->user()->response()->attach(intval($form->id),['date'=>now()]);

        return response()->json(['message' => 'Remove question success',$validate,], 200);



    }
}
