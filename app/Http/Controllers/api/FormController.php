<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AllowedDomain;
use App\Models\Form;
use Illuminate\Http\Request;
use Mockery\Undefined;

class FormController extends Controller
{
    public function postform(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:forms,slug|not_regex:/^[a-zA-Z.-]+&/',
            'allowed_domains' => 'array',
        ]);

        
        $form = Form::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
            'limit_one_response' => $request->limit_one_response,
            'creator_id' => $request->user()->id,
        ]);
        
        if ($request->allowed_domains[0] != null) {
            $allowed = collect($request->allowed_domains);
            $allowed->map(function ($a) use ($form) {
                AllowedDomain::create([
                    'form_id' => $form->id,
                    'domain' => $a,
                ]);
            });
        }
        $i = [
            'name' => $form->name,
            'slug' => $form->slug,
            'description' => $form->description,
            'limit_one_response' => $form->limit_one_response,
            'creator_id' => $form->creator_id,
            'id' => $form->id,
        ];

        return response()->json(['message' => 'Create form success', 'form' => $i], 200);
    }

    public function getform(Request $request){
        $user = $request->user();
        $form = Form::where('creator_id',$user->id)->get();

        if($form == null)return response()->json(['message'=>'Not Have form']);

        // $i = [
        //     'id' => $form->id,
        //     'name' => $form->name,
        //     'slug' => $form->slug,
        //     'description' => $form->description,
        //     'limit_one_response' => $form->limit_one_response,
        //     'creator_id' => $form->creator_id,
        // ];

        return response()->json(['message' => 'Get all forms success', 'forms' => $form], 200);

    }


    public function getdetail($slug,Request $request){
        $user = $request->user();
        $form = Form::where('slug',$slug)->with('allowedDomain','question')->first();

        if($form == null)return response()->json(['message'=>'Form not found'],404);

        $i = [
            'id' => $form->id,
            'name' => $form->name,
            'slug' => $form->slug,
            'description' => $form->description,
            'limit_one_response' => $form->limit_one_response,
            'creator_id' => $form->creator_id,
            'allowed_domain' => $form->allowedDomain->map(function($a){
                return $a->pluck('domain');
            }),
            'qeustions'=>$form->question
        ];

        return response()->json(['message' => 'Get form success', 'form' => $i], 200);


    }
}
