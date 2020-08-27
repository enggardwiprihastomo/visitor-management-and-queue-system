<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    //
    protected $fillable = [
        'handphone_id', 'terminal_id', 'is_counselling', 'c_type', 'c_representative'
    ];

    public function npwps()
    {
        return $this->hasMany('App\Npwp');
    }

    public function handphone()
    {
        return $this->belongsTo('App\Handphone');
    }

    public function terminal()
    {
        return $this->belongsTo('App\Terminal');
    }

    public function queue()
    {
        return $this->hasOne('App\Queue');
    }
}
