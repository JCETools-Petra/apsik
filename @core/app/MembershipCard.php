<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MembershipCard extends Model
{
    protected $fillable = ['user_id','card_id','status'];

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}