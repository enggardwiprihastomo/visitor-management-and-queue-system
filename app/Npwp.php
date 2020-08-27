<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Npwp extends Model
{
    //

    protected $fillable = ['transaction_id', 'number'];

    public function transaction()
    {
        return $this->belongsTo('App\Transaction');
    }
}
