<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    //
    protected $fillable = ['transaction_id', 'queue', 'type', 'counter', 'is_calling', 'is_finished'];

    public function transaction()
    {
        return $this->belongsTo('App\Transaction');
    }

    public function call()
    {
        return $this->hasMany('App\Call');
    }
}
