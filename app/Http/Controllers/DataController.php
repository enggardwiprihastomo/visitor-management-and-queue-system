<?php

namespace App\Http\Controllers;
use App\Handphone;
use App\Citizen;
use App\Transaction;
use App\NpwpList;
use App\Employee;

use Illuminate\Http\Request;

class DataController extends Controller
{
    //
    public function __construct()
    {
        // $this->middleware('access');
    }

    public function getData(Request $request)
    {
        if ($request->data == 'getKtpByHandphone') {
            return $this->getKtpByHandphone($request->handphone);
        }
        else if ($request->data == 'getNpwpFromMaster') {
            $npwp = preg_replace("/[^0-9]/", "", $request->npwp);
            return $this->getNpwpFromMaster($npwp);
        }
        else if ($request->data == 'getEmployee') {
            $type = $request->type;
            if ($type == 'Account Representative' || $type == 'Pemeriksaan' || $type == 'Juru Sita') {
                $employees = Employee::where('type', $type)->orderBy('name', 'asc')->get();

                return response()->json(['employees' => $employees]);
            }
        }
        else if ($request->data == 'getNpwpsByHandphone') {
            return $this->getNpwpsByHandphone($request->handphone);
        }


        return response()->json(['exist' => false]);
    }

    public function getKtpByHandphone($handphone)
    {
        $handphoneExist = Handphone::where('number', $handphone)->exists();
        $citizen = false;
        if ($handphoneExist)
        {
            $handphone = Handphone::where('number', $handphone)
                ->orderBy('id', 'desc')->first();
            $citizen = $handphone->citizen;
        }
        
        $data = [
            "exist" => $handphoneExist,
            "ktp" => $citizen
        ];

        return response()->json($data);
    }

    public function getNpwpsByHandphone($handphone)
    {
        $exist = false;
        $npwps = false;
        $handphone = Handphone::where('number', $handphone)->first();
        $npwps = $handphone->getNpwps();
        if ($npwps){
            $exist = true;
        }

        $data = [
            "exist" => $exist,
            "npwps" => $npwps
        ];
        
        return response()->json($data);
    }

    public function getNpwpFromMaster($npwp)
    {
        $npwpExist = NpwpList::where('npwp', $npwp)->exists();
        $npwpMaster = $npwpExist;
        if ($npwpExist) {
            $npwpMaster = NpwpList::where('npwp', $npwp)->first();
        }
        
        $data = [
            "exist" => $npwpExist,
            "npwp" => $npwpMaster
        ];

        return response()->json($data);
    }
}
