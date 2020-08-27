<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Citizen extends Model
{
    //
    protected $fillable = ['name', 'ktp_number', 'ktp_file_name'];

    public function handphone()
    {
        return $this->hasMany('App\Handphone');
    }
}
