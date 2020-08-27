<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Mike42\Escpos\Printer; 
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\EscposImage;
use Illuminate\Support\Carbon;

use App\Terminal;
use App\RunningText;
use App\User;
use App\Employee;
use App\Setting;

use App\Queue;

class AdminController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('access:admin');
    }

    public function index()
    {
        $terminals = Terminal::where('code', '<', 'E')->orderBy('code', 'asc')->get();
        $runningTexts = RunningText::orderBy('id', 'asc')->get();
        $media = Setting::where('name', 'media')->first()->value;
        
        return view('admin', [
            'terminals' => $terminals,
            'runningTexts' => $runningTexts,
            'media' => $media
        ]);
    }

    public function getData($data)
    {
        if ($data == 'init_admin') 
        {
            $terminals = Terminal::orderBy('code', 'asc')->get();
            
            $data = [
                'terminals' =>$terminals
            ];
        }
        else if ($data == 'running_text')
        {
            $runningTexts = RunningText::orderBy('id', 'asc')->get();

            $data = [
                'runningTexts' => $runningTexts
            ];
        }
		else if ($data == 'history_queue')
        {
            $historyQueues = Queue::orderBy('created_at', 'desc')->get();
			$queues = [];
			foreach($historyQueues as $historyQueue) {
				$queue = [
					'id' => $historyQueue->id,
					'handphone' => $historyQueue->transaction->handphone->number,
					'transaction' => $historyQueue->transaction->terminal->code,
				];
				$queues[] = $queue; 
			}
            $data = [
                'queues' => $queues
            ];
        }

        return response()->json($data);
    }

    public function update(Request $request)
    {
        $aksi = $request->aksi;
        $data = ['status' => 'success'];
        
        if ($aksi == 'update_counter')
        {
            $terminal = Terminal::find($request->terminal);
            $terminal->counter = $request->counter;
            $terminal->save();
        }
        else if ($aksi == 'update_media')
        {
            $media = Setting::where('name', 'media')->first();
            $media->value = $request->media;
            $media->save();
        }
        else if ($aksi == 'add_running_text') {
            $runningText = RunningText::create(['text' => $request->text]);
            $data = [
                'runningText' => $runningText
            ];
        }
        else if ($aksi == 'delete_running_text') {
            RunningText::find($request->id)->delete();
        }
        else if ($aksi == 'get_employee') {
            if ($request->type == 'Pegawai Loket') {
                $employees = User::where('is_staff', true)->orderBy('name', 'asc')->get();
                
                $data = [
                    "employees" => $employees
                ];
            }
            else if ($request->type == 'Account Representative' || $request->type == 'Pemeriksaan' || $request->type == 'Juru Sita') {
                $employees = Employee::where('type', $request->type)->orderBy('name', 'asc')->get();
                
                $data = [
                    "employees" => $employees
                ];
            }
        }
        else if ($aksi == 'add_employee') {
            if ($request->type == 'Pegawai Loket') {
                $username = preg_replace("/[^a-zA-Z]/", "", $request->name);
                $username = strtolower($username);
                $employee = User::create([
                    'username' => $username, 
                    'name' => $request->name, 
                    'is_admin' => false,
                    'is_staff' => true,
                    'password' => Hash::make('12345678')
                ]);

                $data = [
                    "employee" => $employee
                ];
            }
            else if ($request->type == 'Account Representative' || $request->type == 'Pemeriksaan' || $request->type == 'Juru Sita') {
                $employee = Employee::create([
                    'type' => $request->type, 
                    'name' => $request->name
                ]);

                $data = [
                    "employee" => $employee
                ];
            }
        }
        else if ($aksi == 'update_employee_counter') {
            $employee = User::find($request->id);
            $employee->username = $request->username;
            $employee->name = $request->name;
            
            if ($request->has('password')) {
                $employee->password = Hash::make($request->password);
            }

            $employee->save();
            $data = [
                "employee" => $employee
            ];
        }
        else if ($aksi == 'delete_employee') {
            if ($request->type == 'Pegawai Loket') {
                User::find($request->id)->delete();
            }
            else if ($request->type == 'Account Representative' || $request->type == 'Pemeriksaan' || $request->type == 'Juru Sita') {
                Employee::find($request->id)->delete();
            }
        }

        return response()->json($data);
    }
	
	public function printQueue(Request $request)
	{
		$queue = Queue::find($request->id);
		$terminal = $queue->queue[0];
        $queue = $queue->queue;
        $remaining_queue = Queue::where('queue', 'like', "$terminal%")->where('is_finished', false)->count() - 1;
        $date = Carbon::now();
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $dayWeek = $days[$date->dayOfWeekIso - 1];
        $year = $date->year;
        $month = $months[$date->month - 1];
        $day = $date->day;
        $time = $date->toTimeString();

        try {
            $connector = new WindowsPrintConnector("smb://PC270831WK102/Generic Text Only");
            $printer = new Printer($connector);
            
            // Logo
            // $logo = EscposImage::load("image/logo2.jpg", true);
            // $printer->setJustification(Printer::JUSTIFY_CENTER);
            // $printer->graphics($logo);
            // $printer->text($logoUrl);

            // Title
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            // $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            $printer->selectPrintMode(Printer::MODE_DOUBLE_HEIGHT);
            $printer->setEmphasis(true);
            $printer->text("KANTOR PELAYANAN PAJAK\n");
            $printer->text("PRATAMA PALU\n");
            // $printer->text("------------------------------------------\n");

            // Alamat
            $printer->setEmphasis();
            $printer->setDoubleStrike();
            $printer->selectPrintMode();
            $printer->text("Jln. Prof. M. Yamin No. 94, Palu\n");
            $printer->text("Telp: (0451) 421625\n");
            $printer->feed(2);
            
            // Nomor Antrian
            $printer->text("Nomor Antrian\n");
            $printer->feed(1);

            // Antrian
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->selectPrintMode(Printer::MODE_DOUBLE_HEIGHT);
            $printer->setUnderline(true);
            $printer->setEmphasis(true);
            $printer->setDoubleStrike(true);
            $printer->text("$queue\n");

            // Terminal
            $printer->selectPrintMode(Printer::MODE_EMPHASIZED);
            $printer->text("Terminal $terminal\n");
            $printer->feed(2);
            
            // Footer
            $printer->selectPrintMode();
            $printer->text("Silahkan Menunggu Antrian Anda\n");
            $printer->selectPrintMode(Printer::MODE_EMPHASIZED);
            $printer->text("Antrian Tersisa: $remaining_queue\n");
            $printer->feed(2);

            // Line
            // $printer->text("__________________________________________\n");
            $printer->feed(2);

            // Date
            // $printer->text(" $dayWeek\n");
            $printer->text("$dayWeek, $day $month $year - $time\n");
            $printer->feed(4);
            
            $printer->cut();
            
            /* Close printer */
            $printer -> close();
        } catch (Exception $e) {
            echo "Couldn't print to this printer: " . $e -> getMessage() . "\n";
        }
		return response()->json(['status' => 'success']);
	}
}