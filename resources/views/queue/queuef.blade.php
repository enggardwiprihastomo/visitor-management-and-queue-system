<html>
<head>
	<link rel="icon" href="{{ asset('asset/logo.png') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('css/iziToast.min.css') }}">
	<title>Sistem Antrian Kantor Pelayanan Pajak Pratama Palu</title>
	<meta charset="UTF-8">
	<meta name="author" content="Tictoc Group">
	<meta name="keywords" content="Kantor Pajak Pratama Palu, Antrian, Palu, Sulawesi Tengah">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="_token" content='{{ csrf_token() }}' />
</head>
<body>
	<div class="dropdownbutton">
		<div class="buttonstate down"></div>
	</div>
	<div class="dropdownbox dropdownboxtransform">
		<table>
			<tr>
				<td style="padding-top: 10px;"></td>
			</tr>
			<tr>
				<td>
					<button>
						<a href="{{ url('/logout') }}"><img src="{{ asset('asset/exit.png') }}"></a>
					</button>
				</td>
			</tr>
			<tr>
				<td>
					Logout
				</td>
			</tr>
		</table>
	</div>
	<div class="header">
		<table>
			<tr>
				<td class="logo">
					<a href="{{ url('queue/display') }}"><img src="{{ asset('asset/logo.png') }}"></a>
				</td>
				<td class="title">
					Kantor Pelayanan Pajak<br>Pratama Palu
				</td>
				<td class="datetimeterminal">
					<div class="divdatetime">
						<div class="divdatetimeimg">
							<img src="{{ asset('asset/clock.png') }}">
						</div>
						<div class="divdaydatetime">
						<font id="CurrentDay"></font><br>
						<font id="CurrentDate"></font><font id="CurrentTime"></font>
					</div>
					</div>
					<div class="divterminal">
						<div id="terminalCounter" class="terminalname">
							A1
						</div>
						<div class="employeename">
							{{ Auth::user()->name }}
						</div>
					</div>
				</td>
			</tr>
		</table>
	</div>
	<div class="content">
		<table>
			<tr>
				<td class="activequeue">
					<div class="informationqueue">
						<div class="informationqueuedesc">Informasi</div>
						<div class="informationqueuecontent">
							<table>
								<tr>
									<th id="cRepresentative"></th>
								</tr>
								<tr>
									<td id="cType"></td>
								</tr>
							</table>
						</div>
					</div>
				</td>
				<td class="queuehistory" rowspan="3">
					<div class="othercallinghistory">
						<table>
							<thead>
                                <tr>
                                    <th colspan="2">History Panggilan</th>
                                </tr>
                            </thead>
                            <tbody id="callingHistory"></tbody>
						</table>
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<div class="othercallingqueue">
						<div class="othercallingqueuedesc">Antrian Aktif</div>
						<div id="currentQueue" class="othercallingqueuenumber">A-0001</div>
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<button id="call" class="callingbutton">Panggil</button>
				</td>
			</tr>
			<tr>
				<td>
					<button id="next" class="nextbutton">Selanjutnya</button>
				</td>
				<td class="remainingqueue">
					<img src="{{ asset('asset/queueleft.png') }}"> Antrian Tersisa: <b id="remainingQueue">0</b>
				</td>
			</tr>
		</table>
	</div>
	<script src="{{ asset('js/jquery.js') }}"></script>
	<script src="{{ asset('js/iziToast.min.js') }}"></script>
	<script>
		function showdate(id){
			date = new Date;
			year = date.getFullYear();
			month = date.getMonth();
			d = date.getDate();
			result = d+'/'+month+'/'+year;
			document.getElementById(id).innerHTML = result;
			setTimeout('showdate("'+id+'");','1000');
			return true;
		}

		function showday(id){
			date = new Date;
			day = date.getDay();
			days = new Array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu');
			result = days[day];
			document.getElementById(id).innerHTML = result;
			setTimeout('showday("'+id+'");','1000');
			return true;
		}

		function showtime(id){
			date = new Date;
			h = date.getHours();
			if(h<10)
			{
			h = "0"+h;
			}
			m = date.getMinutes();
			if(m<10)
			{
			m = "0"+m;
			}
			s = date.getSeconds();
			if(s<10)
			{
			s = "0"+s;
			}
			result = h+':'+m+':'+s;
			document.getElementById(id).innerHTML = result;
			setTimeout('showtime("'+id+'");','1000');
			return true;
		}

		$(document).ready(function(){
			showtime('CurrentTime');
			showdate('CurrentDate');
			showday('CurrentDay');
		});

		  $(".dropdownbutton").click(function() {
		  $('.dropdownboxtransform').toggleClass('dropdownboxtransformactive');
		  $('.down').toggleClass('up');
		});
	</script>
	<script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        })
        var ajaxUrl = "{{ url('queue') }}";
        var callUrlAsset = "{{ asset('/asset/recall.png') }}"
    </script>
    <script src="{{ asset('js/queue.js') }}"></script>
</body>
</html>