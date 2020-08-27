<html>
<head>
	<link rel="icon" href="{{ asset('asset/logo.png') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('css/display.css') }}">
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
					<a href="{{ url('queue') }}"><img src="{{ asset('asset/logo.png') }}"></a>
				</td>
				<td class="title">
					Kantor Pelayanan Pajak<br>Pratama Palu
				</td>
				<td class="terminal">
					<font id="TerminalDesc">Terminal</font><br>
					<font id="TerminalName"></font>
				</td>
				<td class="datetime">
					<font id="CurrentTime"></font><br>
					<font id="CurrentDate"></font>
				</td>
			</tr>
		</table>
	</div>
	
	<div class="displaycontent">
		<table>
			<tr>
				<td class="queuesection">
					<div id="counter" class="locketsection">
						Loket 1
					</div>
				</td>
				<td rowspan="2" class="videosection">
					<div class="onlinetv">
						<video id="videoPlayer" src="" style="height: 100%; width: auto" loop="loop" autoplay="autoplay" controls="controls"></video>
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<div class="activequeuesection">
						<div class="queuedesc">Nomor Antrian</div>
						<div id="queue" class="queuenumber"></div>
					</div>
				</td>
			</tr>
		</table>
	</div>
	
	<div class="runningtext">
		<marquee id="runningText"></marquee>
	</div>
	
	<script src="{{ asset('js/jquery.js') }}"></script>
	<script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
		})
		
		var ajaxUrl = "{{ url('queue') }}";
		var videoUrl = "{{ asset('/videos/') }}";
        var logoUrl = "{{ asset('asset/logo.png') }}";

	</script>
    <script src="{{ asset('js/queue.display.js') }}"></script>
	<script>
		function showdate(id){
			date = new Date;
			year = date.getFullYear();
			month = date.getMonth();
			months = new Array('Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
			d = date.getDate();
			day = date.getDay();
			days = new Array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu');
			result = d+' '+months[month]+' '+year;
			document.getElementById(id).innerHTML = result;
			setTimeout('showdate("'+id+'");','1000');
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
		});
	</script>
</body>
</html>