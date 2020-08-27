<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Outbox extends Model
{
    //
    protected $table = 'outbox';
    protected $fillable = ['DestinationNumber', 'TextDecoded', 'CreatorID'];
}
