<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    public $hidden =['updated_at','created_at'] ;

    public function user(){
        return $this->belongsTo(User::class, 'creator_id','id');
    }

    public function allowedDomain(){
        return $this->hasMany(AllowedDomain::class, 'form_id','id');
    }

    public function question(){
        return $this->hasMany(Question::class, 'form_id','id');
    }

    public function response(){
        return $this->belongsToMany(User::class, 'responses', 'form_id', 'user_id')->withPivot(['date','form_id','user_id']);
    }
}
