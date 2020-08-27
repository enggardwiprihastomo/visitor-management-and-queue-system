<html>

<head>
    <link rel="icon" href="{{ asset('asset/logo.png') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/su.css') }}">
    <link rel="stylesheet" href="{{ asset('css/iziToast.min.css') }}">
    <title>Sistem Antrian Kantor Pelayanan Pajak Pratama Palu</title>
    <meta charset="UTF-8">
    <meta name="author" content="Tictoc Group">
    <meta name="keywords" content="Kantor Pajak Pratama Palu, Antrian, Palu, Sulawesi Tengah">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="_token" content='{{ csrf_token() }}' />
</head>

<body>
    <div class="header">
        <table>
            <tr>
                <td class="logo">
                    <img src="{{ asset('asset/logo.png') }}">
                </td>
                <td class="title">
                    Kantor Pelayanan Pajak<br>
                    Pratama Palu
                </td>
                <td class="datetime">
                    <font id="CurrentTime"></font><br>
                    <font id="CurrentDate"></font>
                </td>
                <td class="logout">
                    <button>
                        <a href="{{ url('logout') }}"><img src="{{ asset('asset/logout.png') }}"></a>
                    </button>
                </td>
            </tr>
        </table>
    </div>
    <div class="content">
        <table>
            <tr>
                <td class="table">
                    <div class="tablediv">
                        <table>
                            <tr>
                                <td class="tabledivimg">
                                    Meja<br><br>
                                    <img src="{{ asset('asset/table.png') }}">
                                </td>
                                <td class="tabledivinput">
                                    <select id="terminal" name="terminal">
                                        @foreach ($terminals as $terminal)

                                        <option value="{{ $terminal->id }}" data-counter="{{ $terminal->counter }}">{{ $terminal->service }}</option>
                                        @endforeach

                                    </select>
                                    <br><br>
                                    <button id="btnCounterMin" class="btnmin">-</button>
                                    <button id="counter" class="terminalqty">0</button>
                                    <button id="btnCounterPlus" class="btnplus">+</button>
                                    <br><br>
                                    <button id="btnCounterSubmit" class="btnok">OK</button>
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
                <td class="defaultmedia">
                    <div class="defaultmediadiv">
                        <table>
                            <tr>
                                <td class="defaultmediaimg">
                                    Media Antrian
                                    <br><br>
                                    <img src="asset/default.png">
                                </td>
                                <td class="defaultmediainput">
                                    Silahkan Pilih Default Media Antrian
                                    <br><br>
                                    <span>
                                    @if ($media == 'sms')
                                        <input type="radio" name="queuemedia" id="sms" value="sms" checked><label class="media" for="sms" data-value="sms">SMS</label>
                                        <input type="radio" name="queuemedia" id="printer" value="printer"><label class="media" for="printer" data-value="printer">Printer</label>
                                    @else
                                        <input type="radio" name="queuemedia" id="sms" value="sms"><label class="media" for="sms" data-value="sms">SMS</label>
                                        <input type="radio" name="queuemedia" id="printer" value="printer" checked><label class="media"for="printer" data-value="printer">Printer</label>
                                    @endif
                                    </span>
                                    <br><br>
                                    <button id="btnMediaSubmit" class="btnok">OK</button>
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
			<tr>
				<td colspan="2" class="resend">
					<div class="resenddiv">
						<table>
							<tr>
								<td class="resenddivimg">
									Cetak Antrian
									<br><br>
									<img src="asset/print.png">
								</td>
								<td class="resenddivlist">
									<div class="divlist">
										<table>
										<tr>
											<th colspan="2">
												List Nomor Telepon Antrian Terdaftar
											</th>
										</tr>
										<tbody id="historyQueue">
										</tbody>
										</table>
									</div>
								</td>
							</tr>
						</table>
					</div>
				</td>
			</tr>
            <tr>
                <td colspan="2" class="employee">
                    <div class="employeediv">
                        <table>
                            <tr>
                                <td class="employeedivinput">
                                    <font style="font-size: 24px">Pegawai</font><br><br>
                                    <img src="asset/employee.png"><br><br>
                                    <select id="employeeType" name="employeetype">
                                        <option value="Pegawai Loket">Pegawai Loket</option>
                                        <option value="Account Representative">Account Representative</option>
                                        <option value="Pemeriksaan">Pemeriksaan</option>
                                        <option value="Juru Sita">Juru Sita</option>
                                    </select><br><br>
                                    <input id="employeeName" type="text" name="employeename" placeholder="Nama"><br><br>
                                    <button id="employeeAdd" class="btnok">OK</button>
                                </td>
                                <td class="employeedivlist">
                                    <div class="divlist">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th colspan="3">
                                                        List Pegawai
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody id="employeeList"></tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="runningtext">
                    <div class="runningtextdiv">
                        <table>
                            <tr>
                                <td class="runningtextdivinput">
                                    Running Text <br><br>
                                    <img src="asset/runningtext.png"><br><br>
                                    <input id="runningText" type="text" name="runningtext" placeholder="Running Text"><br><br>
                                    <button id="btnRunningTextSubmit"  class="btnok">OK</button>
                                </td>
                                <td class="runningtextdivlist">
                                    <div class="divlist">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th colspan="2">
                                                        List Running Text
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody id="runningTextList">
                                                @foreach ($runningTexts as $runningText)
                                                <tr id="runningText{{ $runningText->id }}">
                                                    <td class="runningtextcaption">
                                                        {{ $runningText->text }}
                                                    </td>
                                                    <td class="runningtextdelete">
                                                        <button class="deleteRunningText" value="{{ $runningText->id }}"></button>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <script src="{{ asset('js/jquery.js') }}"></script>
    <script src="{{ asset('js/iziToast.min.js') }}"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        })

        var ajaxUrl = "{{ url('admin') }}";
        var media = " {{ $media }} "

        function showdate(id) {
            date = new Date;
            year = date.getFullYear();
            month = date.getMonth();
            months = new Array('Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus',
                'September', 'Oktober', 'November', 'Desember');
            d = date.getDate();
            day = date.getDay();
            days = new Array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu');
            result = d + ' ' + months[month] + ' ' + year;
            document.getElementById(id).innerHTML = result;
            setTimeout('showdate("' + id + '");', '1000');
            return true;
        }

        function showtime(id) {
            date = new Date;
            h = date.getHours();
            if (h < 10) {
                h = "0" + h;
            }
            m = date.getMinutes();
            if (m < 10) {
                m = "0" + m;
            }
            s = date.getSeconds();
            if (s < 10) {
                s = "0" + s;
            }
            result = h + ':' + m + ':' + s;
            document.getElementById(id).innerHTML = result;
            setTimeout('showtime("' + id + '");', '1000');
            return true;
        }

        $(document).ready(function () {
            showtime('CurrentTime');
            showdate('CurrentDate');
        });

    </script>
    <script src="{{ asset('js/admin.js') }}"></script>
</body>

</html>
