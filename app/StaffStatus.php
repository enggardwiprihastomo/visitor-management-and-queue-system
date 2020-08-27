<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StaffStatus extends Model
{
    //
    protected $table = 'staff_status';
    protected $fillable = ['user_id', 'terminal_id', 'counter', 'status'];

    public function terminal()
    {
        return $this->belongsTo('App\Terminal');
    }
}
