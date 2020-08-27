<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Call;
use App\Terminal;
use Carbon\Carbon;

class CallingController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('access');
    }

    public function index()
    {
        $calls = Call::where('is_calling', true)->get();
        foreach($calls as $call)
        {
            $call->is_calling = false;
            $call->is_called = true;
            $call->save();
        }

        return view('queue/calling');
    }

    public function getData(Request $request, $data)
    {
        if ($data == 'check_request')
        {
            $call = Call::whereDate('created_at', Carbon::today())
                ->orderBy('created_at', 'desc')->get();
            $data = [
                'call' => $call
            ];
        }

        return response()->json($data);
    }

    public function call(Request $request)
    {
        $checkCalling = Call::where('is_calling', true)->first();
        
        if ($checkCalling) {
            $data['is_calling'] = true;
            
            return response()->json($data);
        }

        $call = Call::where('is_called', false)->first();
        if ($call) {
            $call->is_calling = true;
            $call->save();
            $call_urls = $this->getCallUrl($call);

            $data = [
                'is_calling' => false,
                'call' => $call,
                'urls' => $call_urls
            ];

            return response()->json($data);
        }

        $data = [
            'is_calling' => false,
            'call' => false,
        ];

        return response()->json($data);
    }

    public function getCallUrl($call)
    {
        $queue = explode('-', $call->queue->queue);
        $counter = $call->counter;
        $terminal = $queue[0];
        $antrian = str_split($queue[1]);

        $urls = array();
        $urls[] = url('audio/nomor_antrian.mp3');
        $url = 'audio/'.$terminal[0].'.mp3';
        $urls[] = url($url);

        if ($terminal[0] == 'E'){
            $url = 'audio/'.$terminal[1].'.mp3';
            $urls[] = url($url);
        }

        for ($i = 0; $i < count($antrian); $i++) {
            $char = $antrian[$i];
            if ($i <= 1) {
                if($char == '1') {
                    $url = 'audio/se.mp3';
                    $urls[] = url($url);
                }
                else if ($char != '0') {
                    $url = 'audio/'.$char.'.mp3';
                    $urls[] = url($url);
                }
                if ($i == 0 && $char != '0')
                {
                    $url = 'audio/ribu.mp3';
                    $urls[] = url($url);
                }
                else if ($i == 1 && $char != '0')
                {
                    $url = 'audio/ratus.mp3';
                    $urls[] = url($url);
                }
            }
            else if ($i == 2) {
                $belasan = $antrian[2] == '1' && $antrian[3] != '0';
                $sebelas = $antrian[2] == '1' && $antrian[3] == '1';

                if ($sebelas || ($char == '1' && !$belasan)) {
                    $url = 'audio/se.mp3';
                    $urls[] = url($url);
                }
                else if ($belasan) {
                    $char = $antrian[3];
                    $url = 'audio/'.$char.'.mp3';
                    $urls[] = url($url);
                }
                else if (!$belasan  && $char != '0') {
                    $url = 'audio/'.$char.'.mp3';
                    $urls[] = url($url);
                    if ($antrian[3] != '0') {
                        $url = 'audio/puluh.mp3';
                        $urls[] = url($url);
                    }
                }
            }
            else if ($i == 3) {
                $belasan = $antrian[2] == '1' && $antrian[3] != '0';
                $sebelas = $antrian[2] == '1' && $antrian[3] == '1';

                if ($belasan) {
                    $url = 'audio/belas.mp3';
                    $urls[] = url($url);
                }
                else if ($char == '0' && $antrian[2] != '0') {
                    $url = 'audio/puluh.mp3';
                    $urls[] = url($url);
                }
                else if (! $belasan && $antrian[3] != '0') {
                    $url = 'audio/'.$char.'.mp3';
                    $urls[] = url($url);
                }
            }
        }

        $urls[] = url('audio/silahkan_menuju_loket.mp3');
        $url = 'audio/'.$terminal[0].'.mp3';
        $urls[] = url($url);
        $url = 'audio/'.$counter.'.mp3';
        $urls[] = url($url);

        return $urls;
    }

    public function called(Request $request)
    {
        $call = Call::find($request->id);
        $call->is_calling = false;
        $call->is_called = true;
        $call->save();

        $data = [
            'call' => $call
        ];

        return response()->json($data);
    }
}