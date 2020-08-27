<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Call extends Model
{
    //
    protected $fillable = ['queue_id', 'counter', 'is_calling', 'is_called'];

    public function queue()
    {
        return $this->belongsTo('App\Queue');
    }
}
