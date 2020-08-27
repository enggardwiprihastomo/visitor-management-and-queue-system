<html>
<head>
	<link rel="icon" href="{{ asset('asset/logo.png') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('css/public.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('css/iziToast.min.css') }}">
	<title>Sistem Antrian Kantor Pelayanan Pajak Pratama Palu</title>
	<meta charset="UTF-8">
	<meta name="author" content="Tictoc Group">
	<meta name="keywords" content="Kantor Pajak Pratama Palu, Antrian, Palu, Sulawesi Tengah">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
	<div class="header">
		<table>
			<tr>
				<td class="logo">
					<img src="{{ asset('asset/logo.png') }}">
				</td>
				<td class="title">
					Kantor Pelayanan Pajak<br>Pratama Palu
				</td>
			</tr>
		</table>
	</div>
	<div class="menucontent">
		<table>
			<tr>
				<td>
					<a href="{{ url('registration/personalnpwp') }}">
						<div class="firstmenu">
							<span></span>
							<div class="menuicon">
								<img src="{{ asset('asset/menua.png') }}">
							</div>
							<div class="menucaption">Pendaftaran Antrian NPWP Orang Pribadi (Usahawan)</div>
						</div>
					</a>
				</td>
				<td>
					<a href="{{ url('registration/publicnpwp') }}">
						<div class="evenmenu">
							<span></span>
							<div class="menuicon">
								<img src="{{ asset('asset/menub.png') }}">
							</div>
							<div class="menucaption">Pendaftaran Antrian NPWP Badan, Bendahara Pemerintah, dan Orang Pribadi (Karyawan)</div>
						</div>
					</a>
				</td>
				<td>
					<a href="{{ url('registration/report') }}">
						<div class="oddmenu">
							<span></span>
							<div class="menuicon">
								<img src="{{ asset('asset/menuc.png') }}">
							</div>
							<div class="menucaption">Pelaporan (SPT, Surat Lain, Dsb)</div>
						</div>
					</a>
				</td>
				<td>
					<a href="{{ url('registration/request') }}">
						<div class="evenmenu">
							<span></span>
							<div class="menuicon">
								<img src="{{ asset('asset/menud.png') }}">
							</div>
							<div class="menucaption">Permohonan (Efin, Sertifikat Digital, SKB, Dsb)</div>
						</div>
					</a>
				</td>
			</tr>
			<tr>
				<td>
					<div class="oddmenu" onclick="checkconsultationdisplay()">
						<span></span>
						<div class="largermenuicon">
							<img src="{{ asset('asset/menue.png') }}">
						</div>
						<div class="largermenucaption">Konsultasi</div>
					</div>
				</td>
				<td colspan="3">
					<a href="{{ url('registration/counseling') }}">
						<div class="longmenu">
							<span></span>
							<div class="largermenuicon">
								<img src="{{ asset('asset/menuf.png') }}">
							</div>
							<div class="largermenucaption">Konseling<br>(Bertemu Dengan AR/Pemeriksa/Penagihan)</div>
						</div>
					</a>
				</td>
			</tr>
		</table>
	</div>
	<div class="consultationborder" id="consultationborder">
		<div class="closepopup" onclick="closeconsultation()">
			<img src="{{ asset('asset/close.png') }}">
		</div>
		<a href="{{ url('registration/consult') }}">
			<div class="consultationmenu">
				<table>
					<tr>
						<td style="height: 70%;">
							<img src="{{ asset('asset/taxconst.png') }}">
						</td>
					</tr>
					<tr>
						<td style="height: 30%;">Konsultasi Pajak</td>
					</tr>
				</table>
			</div>
		</a>
		<a href="{{ url('registration/consult_app') }}">
			<div class="consultationmenu">
				<table>
					<tr>
						<td style="height: 70%;">
							<img src="{{ asset('asset/taxappconst.png') }}">
						</td>
					</tr>
					<tr>
						<td style="height: 30%;">Konsultasi Aplikasi Pajak</td>
					</tr>
				</table>
			</div>
		</a>
	</div>
	<script src="{{ asset('js/jquery.js') }}"></script>
	<script src="{{ asset('js/iziToast.min.js') }}"></script>
	<script>
		function closeconsultation(){
			document.getElementById('consultationborder').style.display='none';
		}

		function checkconsultationdisplay(){
			if (document.getElementById('consultationborder').style.display==="block") {
				document.getElementById('consultationborder').style.display="none";
			}
			else{
				document.getElementById('consultationborder').style.display="block";
				document.getElementById('consultationborder').style.display="flex";
			}
		};

		window.onclick = function(event) {
			if (event.target == document.getElementById('consultationborder')){
				document.getElementById('consultationborder').style.display='none';
			}
		};

		$(function() {  
		  $('.firstmenu')
			.on('mouseenter', function(e) {
					var parentOffset = $(this).offset(),
					relX = e.pageX - parentOffset.left,
					relY = e.pageY - parentOffset.top;
					$(this).find('span').css({top:relY, left:relX})
			})
			.on('mouseout', function(e) {
					var parentOffset = $(this).offset(),
					relX = e.pageX - parentOffset.left,
					relY = e.pageY - parentOffset.top;
				$(this).find('span').css({top:relY, left:relX})
			});

			 $('.oddmenu')
			.on('mouseenter', function(e) {
					var parentOffset = $(this).offset(),
					relX = e.pageX - parentOffset.left,
					relY = e.pageY - parentOffset.top;
					$(this).find('span').css({top:relY, left:relX})
			})
			.on('mouseout', function(e) {
					var parentOffset = $(this).offset(),
					relX = e.pageX - parentOffset.left,
					relY = e.pageY - parentOffset.top;
				$(this).find('span').css({top:relY, left:relX})
			});

			 $('.evenmenu')
			.on('mouseenter', function(e) {
					var parentOffset = $(this).offset(),
					relX = e.pageX - parentOffset.left,
					relY = e.pageY - parentOffset.top;
					$(this).find('span').css({top:relY, left:relX})
			})
			.on('mouseout', function(e) {
					var parentOffset = $(this).offset(),
					relX = e.pageX - parentOffset.left,
					relY = e.pageY - parentOffset.top;
				$(this).find('span').css({top:relY, left:relX})
			});

			 $('.longmenu')
			.on('mouseenter', function(e) {
					var parentOffset = $(this).offset(),
					relX = e.pageX - parentOffset.left,
					relY = e.pageY - parentOffset.top;
					$(this).find('span').css({top:relY, left:relX})
			})
			.on('mouseout', function(e) {
					var parentOffset = $(this).offset(),
					relX = e.pageX - parentOffset.left,
					relY = e.pageY - parentOffset.top;
				$(this).find('span').css({top:relY, left:relX})
			});
		});
	</script>
	@if (Session::has('success'))
		
	<script>
        $(document).ready(function () {
            iziToast.success({
                title: 'Berhasil',
                position: 'topRight',
                transitionIn: 'fadeInDown',
                transitionOut: 'fadeOutUp',
                message: '{!! Session::get('success') !!}',
            });
        })
    </script>
	@endif
</body>
</html>