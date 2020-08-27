<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Handphone extends Model
{
    //
    protected $fillable = ['number'];

    public function citizen()
    {
        return $this->belongsTo('App\Citizen');
    }

    public function transaction()
    {
        return $this->hasMany('App\Transaction');
    }

    public function getNpwps()
    {
        $transactions = $this->transaction;
        if (! $transactions)
            return [];
        
        $newNpwps = array();
        foreach ($transactions as $transaction) {
            $npwps = $transaction->npwps;
            foreach ($npwps as $npwp) {
                $newNpwps[] = $npwp->number;
            }
        }

        $uniqueNpwps = array_unique($newNpwps);

        return $uniqueNpwps;
    }
}
