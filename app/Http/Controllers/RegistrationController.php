<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;
use Mike42\Escpos\Printer; 
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\EscposImage;
use Illuminate\Support\Carbon;

use App\Citizen;
use App\Handphone;
use App\Npwp;
use App\Queue;
use App\Terminal;
use App\Transaction;
use App\Outbox;
use App\NpwpList;
use App\Employee;
use App\Setting;

class RegistrationController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('access:registrasi');
    }

    public function index($menu)
    {
        $media = Setting::where('name', 'media')->first();
        session(['media' => $media->value]);
        if ($menu == 'personalnpwp') {
            return view('registrations/registration')
                ->with('header', 'Pendaftaran Baru<br>NPWP Orang Pribadi (Usahawan)')
                ->with('menu', 'personal');
        }
        else if ($menu == 'publicnpwp') {
            return view('registrations/registration')
                ->with('header', 'Pendaftaran Baru NPWP Badan, Bendahara Pemerintah, dan Orang Pribadi (Karyawan)')
                ->with('menu', 'public');
        }
        else if ($menu == 'report') {
            return view('registrations/registration')
                ->with('menu', 'report');
        }
        else if ($menu == 'request') {
            return view('registrations/registration')
                ->with('menu', 'request');
        }
        else if ($menu == 'consult')
            return view('registrations/registration')
                ->with("header", "Konsultasi Pajak")
                ->with('menu', 'consult');
        else if ($menu == 'consult_app')
            return view('registrations/registration')
                ->with("header", "Konsultasi Aplikasi Pajak")
                ->with('menu', 'consult_app');
        else if ($menu == 'counseling')
            return view('registrations/registration')
            ->with('menu', 'counseling');
        else
            abort(404);
    }

    public function register()
    {
        if (! Auth::check()) {
            abort(403, 'Unauthorized action.');
        }

        return view('registrations/register')
            ->with('header', 'Pendaftaran Baru<br>Data Nomor Handphone')
            ->with('menu', 'register');
    }
    
    public function transaction(Request $request)
    {
        $handphoneExist = Handphone::where('number', $request->nohp)->exists();
        $type = $request->queueinformation;
        $aksi = $request->submit;

        if ($aksi == 'register') {
            $validationData = $request->validate([
                'nohp' => 'required|unique:handphones,number|max:12',
                //'noktp' => 'required',
                'nama' => 'required',
                //'ktpfile' => 'required|mimes:pdf'
            ], [
                'nohp.required' => 'Nomor handphone anda dibutuhkan',
                'nohp.unique' => 'Nomor handphone anda telah terdaftar sebelumnya',
                //'noktp.required' => 'Nomor KTP anda dibutuhkan',
                'nama.required' => 'Nama lengkap anda dibutuhkan',
                //'ktpfile.required' => 'File KTP anda dibutuhkan',
                //'ktp.mimes' => 'File KTP anda harus berupa file pdf'
            ]);

            $handphone = $this->registHanphone($request);
        }
        else if ($aksi == 'personal' || $aksi == 'public')
        {
            $validationData = $request->validate([
                // 'nohp' => 'required|unique:handphones,number|max:12',
                'nohp' => 'required|max:12',
                //'noktp' => 'required',
                'nama' => 'required',
                //'ktpfile' => 'required|mimes:pdf'
            ], [
                'nohp.required' => 'Nomor handphone anda dibutuhkan',
                // 'nohp.unique' => 'Nomor handphone anda telah terdaftar sebelumnya',
                //'noktp.required' => 'Nomor KTP anda dibutuhkan',
                'nama.required' => 'Nama lengkap anda dibutuhkan',
                //'ktpfile.required' => 'File KTP anda dibutuhkan',
                //'ktp.mimes' => 'File KTP anda harus berupa file pdf'
            ]);

            $handphone = Handphone::where('number', $request->nohp)->first();

            if ($handphone){
                $citizen = $handphone->citizen;
				$citizen->name = $request->nama;
				$citizen->save();
                //if ($request->noktp != $citizen->ktp_number)
                //{
                //    $errors = new MessageBag();
                //    $errors->add('nohp', 'Nomor handphone anda telah terdaftar dengan ktp yang berbeda');

                //    $request->flash();
                //    return back()->withErrors($errors);
                //}
            }
			else {
				$handphone = $this->registHanphone($request);
			}

            if ($aksi == 'personal')
                $terminal = Terminal::find('1');
            else
                $terminal = Terminal::find('2');
            
            $options = [
                "is_counselling" => false,
                "c_type" => null,
                "c_representative" => null
            ];

            $transaction = $this->createTransaction($handphone, $terminal, $options);
            $queue = $this->createQueue($transaction, $type);
        }
        else if ($aksi == 'report')
        {
            $validationData = $request->validate([
                'nohp' => 'required|exists:handphones,number|max:12',
                //'noktp' => 'required|exists:citizens,ktp_number',
                'nama' => 'required|exists:citizens,name',
                'npwp.*' => 'required'
            ], [
                'nohp.required' => 'Nomor handphone anda dibutuhkan',
                'nohp.exists' => 'Nomor handphone anda tidak terdaftar',
                //'noktp.required' => 'Nomor KTP anda dibutuhkan',
                //'noktp.exists' => 'Ktp anda tidak terdaftar',
                'nama.required' => 'Nama lengkap anda dibutuhkan',
                'nama.exists' => 'Nama anda tidak terdaftar',
                'npwp.*.required' => 'NPWP anda dibutuhkan',
            ]);


            $terminal = Terminal::find('3');
            $handphone = Handphone::where('number', $request->nohp)->first();

            $options = [
                "is_counselling" => false,
                "c_type" => null,
                "c_representative" => null
            ];

            $npwps = $request->npwp;
            $transaction = $this->createTransaction($handphone, $terminal, $options);
            $this->addNpwp($transaction, $npwps);
            $queue = $this->createQueue($transaction, $type);
        }
        else if ($aksi == 'request')
        {
            $validationData = $request->validate([
                'nohp' => 'required|exists:handphones,number|max:12',
                //'noktp' => 'required|exists:citizens,ktp_number',
                'nama' => 'required|exists:citizens,name',
                'npwp.*' => 'required'
            ], [
                'nohp.required' => 'Nomor handphone anda dibutuhkan',
                'nohp.exists' => 'Nomor handphone anda tidak terdaftar',
                //'noktp.required' => 'Nomor KTP anda dibutuhkan',
                //'noktp.exists' => 'Ktp anda tidak terdaftar',
                'nama.required' => 'Nama lengkap anda dibutuhkan',
                'nama.exists' => 'Nama anda tidak terdaftar',
                'npwp.*.required' => 'NPWP anda dibutuhkan',
            ]);

            $terminal = Terminal::find('4');
            $handphone = Handphone::where('number', $request->nohp)->first();

            $options = [
                "is_counselling" => false,
                "c_type" => null,
                "c_representative" => null
            ];

            $npwps = $request->npwp;
            $transaction = $this->createTransaction($handphone, $terminal, $options);
            $this->addNpwp($transaction, $npwps);
            $queue = $this->createQueue($transaction, $type);
        }
        else if ($aksi == 'consult' || $aksi == 'consult_app')
        {
            $validationData = $request->validate([
                'nohp' => 'required|exists:handphones,number|max:12',
                //'noktp' => 'required|exists:citizens,ktp_number',
                'nama' => 'required|exists:citizens,name',
                'npwp.*' => 'required'
            ], [
                'nohp.required' => 'Nomor handphone anda dibutuhkan',
                'nohp.exists' => 'Nomor handphone anda tidak terdaftar',
                //'noktp.required' => 'Nomor KTP anda dibutuhkan',
                //'noktp.exists' => 'Ktp anda tidak terdaftar',
                'nama.required' => 'Nama lengkap anda dibutuhkan',
                'nama.exists' => 'Nama anda tidak terdaftar',
                'npwp.*.required' => 'NPWP anda dibutuhkan',
            ]);

            $terminal = Terminal::find('5');
            $handphone = Handphone::where('number', $request->nohp)->first();

            $options = [
                "is_counselling" => false,
                "c_type" => null,
                "c_representative" => null
            ];

            $npwps = $request->npwp;
            $transaction = $this->createTransaction($handphone, $terminal, $options);
            $this->addNpwp($transaction, $npwps);
            $newQueue = $this->createQueue($transaction, $type);

            $terminalQueue = explode('-', $newQueue->queue);
            $terminal = $terminalQueue[0];
            $queue = $terminalQueue[1];

            if ($aksi == 'consult') {
                $newQueue->queue = $terminal . '1' . "-$queue";
                $newQueue->save();
            }
            else if ($aksi == 'consult_app') {
                $newQueue->queue = $terminal . '2' . "-$queue";
                $newQueue->save();
            }
        }
        else if ($aksi == 'counseling')
        {
            $validationData = $request->validate([
                'nohp' => 'required|exists:handphones,number|max:12',
                //'noktp' => 'required|exists:citizens,ktp_number',
                'nama' => 'required|exists:citizens,name',
                'npwp.*' => 'required',
                'employeename' => 'required|exists:employees,id'
            ], [
                'nohp.required' => 'Nomor handphone anda dibutuhkan',
                'nohp.exists' => 'Nomor handphone anda tidak terdaftar',
                //'noktp.required' => 'Nomor KTP anda dibutuhkan',
                //'noktp.exists' => 'Ktp anda tidak terdaftar',
                'nama.required' => 'Nama lengkap anda dibutuhkan',
                'nama.exists' => 'Nama anda tidak terdaftar',
                'npwp.*.required' => 'NPWP anda dibutuhkan',
                'employeename.required' => 'Pegawai belum dipilih',
                'employeename.exists' => 'Pegawai yang anda pilih tidak terdaftar'
            ]);

            $terminal = Terminal::find('6');
            $handphone = Handphone::where('number', $request->nohp)->first();
            $employee = Employee::find($request->employeename);
            
            $options = [
                "is_counselling" => true,
                "c_type" => $employee->type,
                "c_representative" => $employee->name
            ];

            $npwps = $request->npwp;
            $transaction = $this->createTransaction($handphone, $terminal, $options);
            $this->addNpwp($transaction, $npwps);
            $queue = $this->createQueue($transaction, $type);
        }
        
        if ($type == 'sms') {
            session()->flash('success', 'Nomor antrian anda telah dikirim ke handphone anda');
        }
        return redirect('/');
    }

    public function registHanphone($request)
    {
		/*
        $fileName = $request->noktp . uniqid('_') . '.' . $request
            ->ktpfile->getClientOriginalExtension();
        $request->file('ktpfile')->storeAs('', $fileName, 'ktpFiles');

        $citizenExist = Citizen::where('ktp_number', $request->noktp)->exists();
        if ($citizenExist)
        {
            $newCitizen = Citizen::where('ktp_number', $request->noktp)->first();
            Storage::disk('ktpFiles')->delete($newCitizen->ktp_file_name);
        }
        else
        {
            $newCitizen = new Citizen;
        }

        $newCitizen->name = $request->nama;
        $newCitizen->ktp_number = $request->noktp;
        $newCitizen->ktp_file_name = $fileName;
        $newCitizen->save();

        $handphone  = $newCitizen->handphone()->create([
            'number' => $request->nohp
        ]);
        
		*/
		$newCitizen = new Citizen;
		$newCitizen->name = $request->nama;
		$newCitizen->save();
		
		$handphone  = $newCitizen->handphone()->create([
            'number' => $request->nohp
        ]);
		
		//$handphone = Handphone::create(['number' => $request->nohp]);
        return $handphone;
    }

    public function createTransaction($handphone, $terminal, $options)
    {
        $newTransaction = Transaction::create([
            'handphone_id' => $handphone->id, 
            'terminal_id' => $terminal->id, 
            'is_counselling' => $options['is_counselling'],
            'c_type' => $options['c_type'],
            'c_representative' => $options['c_representative']
        ]);

        return $newTransaction;
    }

    public function addNpwp($transaction, $npwps)
    {
        foreach($npwps as $npwp)
        {
            $npwp = preg_replace("/[^0-9]/", "", $npwp);
            
            if ($npwp == '') {
                continue;
            }

            $npwpExist = NpwpList::where('npwp', $npwp)->first();
            if (! $npwpExist) {
                continue;
            }
            
            $npwp = explode('-', $npwp)[0];
            $npwpExist = Npwp::where('transaction_id', $transaction->id)
                ->where('number', $npwp)->exists();
            
            if (! $npwpExist) {
                $transaction->npwps()->create([
                    "number" => $npwp
                ]);
            }
        }

        return $transaction->npwps;
    }

    public function createQueue($transaction, $type)
    {
        $terminal = $transaction->terminal;
        $code = $terminal->code . '%';
        $last_queue = Queue::where('queue', 'like', $code)
            ->orderBy('created_at', 'desc')->first();
        if ($last_queue)
        {
            $queueNumber = intval(explode('-', $last_queue->queue)[1]);
        }
        else 
        {
            $queueNumber = '0';
        }
        
        
        $queue = strval((intval($queueNumber) + 1));

        for ($i = strlen($queue); $i < 4; $i++)
            $queue = '0' . $queue;

        $queue = $terminal->code . '-' . $queue;

        $newQueue = $transaction->queue()->create([
            'queue' => $queue, 
            'type' => $type, 
            'counter'=> 0, 
            'is_finished' => false
        ]);

        if ($type == 'sms')
            $this->smsQueue($newQueue);
        else if ($type == 'print')
            $this->printQueue($newQueue);

        return $newQueue;
    }

    public function smsQueue($queue)
    {
        $queueNumber = $queue->queue;
        $terminal = $queueNumber[0];
        $handphone = $queue->transaction->handphone->number;
        $remaining_queue = Queue::where('queue', 'like', "$terminal%")->where('is_finished', false)->count() - 1;
        $smsText = "Selamat datang di KPP Pratama Palu. Nomor antrian Anda $queueNumber (Sisa Antrian: $remaining_queue). Silahkan menunggu antrian Anda.";
        $newOutbox = Outbox::create(['DestinationNumber' => $handphone, 'TextDecoded' => $smsText, 'CreatorID' => $terminal]);
    }

    public function printQueue ($queue)
    {
        // $logoUrl = url('image/logo.png');
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
    }
}
