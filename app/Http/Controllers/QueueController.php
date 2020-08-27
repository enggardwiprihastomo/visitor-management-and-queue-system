<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use App\Queue;
use App\Terminal;
use App\RunningText;

class QueueController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('access:staff');
    }

    public function index() 
    {
        $terminalModel = Terminal::find(session('terminal'));
        $terminal = $terminalModel->code;

        if ($terminal == 'E')
            return view('queue/queuee');
        else if ($terminal == 'F')
            return view('queue/queuef');

        return view('queue/queue');
    }

    public function display()
    {
        return view('queue/display');
    }

    public function getData(Request $request, $data)
    {
        if ($data == 'running_text') 
        {
            $runningTexts = RunningText::all();
            $exist = false;
            if ($runningTexts)
            {
                $exist = true;
            }

            $data = [
                'exist' => $exist,
                'runningTexts' => $runningTexts,
            ];
        }
        else if ($data == 'terminal') 
        {
            $terminalModel = Terminal::find(session('terminal'));
            $terminal = $terminalModel->code;

            $data = [
                'terminal' => $terminal,
            ];
        }
        else if($data == 'init_queue')
        {
            $terminalModel = Terminal::find(session('terminal'));
            $terminal = $terminalModel->code;
            $counter = session('counter');
            $terminalCounter = $terminal . $counter;
            
            $currentQueue = '';
            $is_counselling = false;
            $transaction = false;

            $currentQueueModel = Queue::where('queue', 'like', "$terminal%")
                ->where('counter', $counter)
                ->where('is_finished', false)->first();

            if ($currentQueueModel){
                $currentQueue = $currentQueueModel->queue;
                $transaction = $currentQueueModel->transaction;
                $transaction->npwps;
            }

            if ($terminal == 'F') {
                $is_counselling = true;
            }

            $data = [
                'terminalCounter' => $terminalCounter,
                'currentQueue' => $currentQueue,
                'is_counselling' => $is_counselling,
                'transaction' => $transaction
            ];
        }
        else if($data == 'current_queue')
        {
            $terminalModel = Terminal::find(session('terminal'));
            $terminal = $terminalModel->code;
            $counter = session('counter');
            $currentQueue = Queue::where('queue', 'like', "$terminal%")
                ->where('counter', $counter)
                ->where('is_finished', false)->first();

            if ($currentQueue){
                $currentQueue = $currentQueue->queue;
            }
            else {
                $currentQueue = '';
            }

            $data = [
                'currentQueue' => $currentQueue
            ];
        }
        else if ($data == 'check_queue')
        {
            $terminalModel = Terminal::find(session('terminal'));
            $terminal = $terminalModel->code;

            if ($terminal == 'E')
            {
                $counter = session('counter');
                $terminal = $terminal . $counter;
            }

            $remainingQueue = Queue::where('queue', 'like', "$terminal%")
                ->where('counter', 0)
                ->where('is_finished', false)->count();
            
            $finishedQueue = Queue::where('queue', 'like', "$terminal%")
                ->where('counter', 0)
                ->where('is_finished', true)
                ->orderBy('created_at', 'desc')->get();
            
            $data = [
                'remainingQueue' => $remainingQueue,
                'finishedQueue' => $finishedQueue
            ];
        }
        else if ($data == 'terminal_data')
        {
            $terminalModel = Terminal::find(session('terminal'));
            $terminal = $terminalModel->code;
            $counters = array();
            $queues = Queue::where('queue', 'like', "$terminal%")
                ->where('counter', '!=', 0)
                ->orderBy('counter', 'asc')->get();
            
            if ($queues) {
                foreach($queues as $queue) {
                    $counter = [
                        "counter" => $queue->counter,
                        "queue" => $queue->queue
                    ];
                    $counters[] = $counter;
                }
            }

            $exist = false;
            if (count($counters) > 0) {
                $exist = true;
            }
    
            $data = [
                "exist" => $exist,
                "counters" => $counters
            ];
        }
        else if ($data == 'remaining_queue') 
        {
            $terminalModel = Terminal::find(session('terminal'));
            $terminal = $terminalModel->code;
            $queues = Queue::where('queue', 'like', "$terminal%")
                ->where('counter', 0)
                ->where('is_finished', false)->get();
            
            $exist = false;
            
            if ($queues) {
                $exist = true;
                $remainingQueue = count($queues);
            }

            $data = [
                'exist' => $exist,
                'remainingQueue' => $remainingQueue,
            ];
        }
        else if ($data == 'change_counter') 
        {
            session(['counter' => $request->counter]);
        }
        else if ($data == 'npwp_list') 
        {
            $exist = false;
            $queue = Queue::where('queue', $request->queue)->first();
            $transaction = $queue->transaction;
            $npwpList = $transaction->npwps;
            $npwps = [];

            foreach($npwpList as $npwp) {
                $npwps[] = $npwp->number;
            }

            if ($npwpList) {
                $exist = true;
            }

            $data = [
                'exist' => $exist,
                'npwps' => $npwps,
            ];
        }
        else if ($data == 'video_list'){
            $files = Storage::disk('videoList')->files();
            $exist = false;
            if (count($files) > 0) {
                $exist = true;
            }
            $data = [
                'exist' => $exist,
                'files' => $files,
            ];
        }

        return response()->json($data);
    }

    public function requestCall(Request $request)
    {
        $terminalModel = Terminal::find(session('terminal'));
        $terminal = $terminalModel->code;
        $counter = session('counter');
        $queue = $request->queue;

        if ($terminal == 'E')
        {
            $arrQueue = explode('-', $queue);
            $queue = $arrQueue[0][0]. $counter . '-' .$arrQueue[1];
        }

        $queue = Queue::where('queue', $queue)->first();
        if ($queue) {
            $queue->call()->create([
                'counter' => $counter,
                'is_calling' => false,
                'is_called' => false
            ]);
        }
        
        return response()->json(['queue' => $queue]);
    }

    public function callAgain(Request $request)
    {
        $terminalModel = Terminal::find(session('terminal'));
        $terminal = $terminalModel->code;
        $counter = session('counter');

        $is_counselling = false;
        $transaction = false;

        if ($terminal == 'F') {
            $is_counselling = true;
        }

        $currentQueue = Queue::where('queue', $request->currentQueue)->first();
        if ($currentQueue) {
            $currentQueue->counter = 0;
            $currentQueue->is_finished = true;
            $currentQueue->save();
        }

        $queue = Queue::where('queue', $request->queue)->first();
        if ($queue) {
            $queue->counter = $counter;
            $queue->is_finished = false;
            $queue->save();
            $transaction = $queue->transaction;
            $transaction->npwps;
        }

        $data = [
            'next' => $queue->queue,
            'is_counselling' => $is_counselling,
            'transaction' => $transaction
        ];

        return response()->json($data);
    }

    public function requestNext(Request $request)
    {
        $terminalModel = Terminal::find(session('terminal'));
        $terminal = $terminalModel->code;
        $counter = session('counter');
        $queue = Queue::where('queue', $request->queue)->first();
        
        $is_counselling = false;
        $transaction = false;

        if ($queue) {
            $queue->counter = 0;
            $queue->is_finished = true;
            $queue->save();
        }

        if ($terminal == 'F') {
            $is_counselling = true;
        }
        
        if ($terminal != 'E') {
            $next = Queue::where('queue', 'like', "$terminal%")
                ->where('counter', 0)
                ->where('is_finished', false)
                ->orderBy('created_at', 'asc')->first();
        }
        else {
            $terminalCounter = $terminal . $counter;
            $next = Queue::where('queue', 'like', "$terminalCounter%")
                ->where('counter', 0)
                ->where('is_finished', false)
                ->orderBy('created_at', 'asc')->first();
        }
        
        if ($next) {
            $transaction = $next->transaction;
            $transaction->npwps;
            $next->counter = $counter;
            $next->save();
            $next = $next->queue;
        }
        else {
            $next = false;
        }

        $data = [
            'next' => $next,
            'is_counselling' => $is_counselling,
            'transaction' => $transaction
        ];

        return response()->json($data);
    }
}