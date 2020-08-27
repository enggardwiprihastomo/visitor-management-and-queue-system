<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" href="{{ asset('asset/logo.png') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/form.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/iziToast.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/iziModal.min.css') }}">
    <title>Sistem Antrian Kantor Pelayanan Pajak Pratama Palu</title>
    <meta charset="UTF-8">
    <meta name="author" content="Tictoc Group">
    <meta name="keywords" content="Kantor Pajak Pratama Palu, Antrian, Palu, Sulawesi Tengah">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="_token" content='{{ csrf_token() }}' />
</head>

<body>
    <form id="registration" action="{{ url('registration') }}" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="dropdownbutton">
            <div class="buttonstate down"></div>
        </div>
        <div class="dropdownbox dropdownboxtransform">
            <table>
                <tr>
                    <td colspan="2" style="padding-top: 30px;"></td>
                </tr>
                <tr>
                    @if (Session::get('media') == 'sms')
                    <td>
                        <input type="radio" name="queueinformation" id="sms" value="sms" checked="true"><label for="sms"></label>
                    </td>
                    <td>
                        <input type="radio" name="queueinformation" id="print" value="print"><label for="print"></label>
                    </td>
                    @else
                    <td>
                        <input type="radio" name="queueinformation" id="sms" value="sms"><label for="sms"></label>
                    </td>
                    <td>
                        <input type="radio" name="queueinformation" id="print" value="print" checked><label for="print"></label>
                    </td>
                    @endif
                </tr>
                <tr>
                    <td>
                        <label for="sms"><img src="{{ asset('asset/sms.png') }}"></label>
                    </td>
                    <td>
                        <label for="print"><img src="{{ asset('asset/print.png') }}"></label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="sms">SMS</label>
                    </td>
                    <td>
                        <label for="print">PRINT</label>
                    </td>
                </tr>
            </table>
        </div>
        <div class="back">
            <a href="{{ url('/') }}"><img src="{{ asset('asset/back.png') }}"></a>
        </div>
        <div class="header">
			<img src="{{ asset('asset/formbg.png') }}">
		</div>
        <div class="inputform">
            @if ($menu == 'public' || $menu == 'personal')
           
            <table>
                <tr>
                    <th colspan="2">{!! $header !!}<th>
                </tr>
				<!--
                <tr>
                    <td rowspan="4" style="width: 20%;">
                        <table class="subtable">
                            <tr>
                                <td>
                                    <label class="uploadfile">
                                        <input id="filektp" type="file" name="ktpfile" accept=".pdf" value="{{ old('ktpfile') }}"
                                            required>
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label id="uploadfilename">Nama File</label>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
				-->
                <tr>
                    <td>
                        <input class="phone" type="text" name="nohp" placeholder="Nomor HP" value="{{ old('nohp') }}" required>
                    </td>
                </tr>
				<!--
                <tr>
                    <td><input class="ktp" type="text" name="noktp" placeholder="Nomor KTP" value="{{ old('noktp') }}"
                            required></td>
                </tr>
				-->
                <tr>
                    <td><input type="text" name="nama" placeholder="Nama Lengkap" value="{{ old('nama') }}" required></td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: right;">
                        <button type="submit" name="submit" value="{{ $menu }}">OK</button>
                    </td>
                </tr>
            </table>
            @endif
            @if ($menu == 'report')
            
            <table class="reportingtable">
                <tr>
                    <th colspan="2">Pelaporan<br>(SPT, Surat Lain, Dsb)<th>
                </tr>
                <tr>
                    <td>
                        <table class="subtable">
                            <tr>
                                <td style="padding: 12px;"><input class="phone" type="text" name="nohp" placeholder="Nomor HP" value="{{ old('nohp') }}" required></td>
                                <td></td>
                            </tr>
							<!--
                            <tr>
                                <td style="padding: 12px;"><input type="text" name="noktp" placeholder="Nomor KTP"
                                        readonly="true" value="{{ old('noktp') }}" required></td>
                                <td></td>
                            </tr>
							-->
                            <tr>
                                <td style="padding: 12px;"><input type="text" name="nama" placeholder="Nama Lengkap"
                                        readonly="true" value="{{ old('nama') }}" required></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td style="padding: 12px;">
                                    <table class="subtablenpwp" id="tablenpwp">
                                        <tr>
                                            <td style="padding-bottom: 12px;">
                                                <input class="npwp" type="text" name="npwp[]" placeholder="NPWP" required>
                                            </td>
                                            <td>
                                                <img src="{{ asset('asset/add.png') }}" onclick="createnpwp()">
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: right; padding: 12px;">
                                    <button type="submit" name="submit" value="report">OK</button>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            @endif
            @if ($menu == 'request')
            
            <table class="reportingtable">
                <tr>
                    <th colspan="2">Permohonan<br>(EFIN, Sertifikat Digital, SKB, Dsb)<th>
                </tr>
                <tr>
                    <td>
                        <table class="subtable">
                            <tr>
                                <td style="padding: 12px;">
                                    <input class="phone" type="text" name="nohp" placeholder="Nomor HP" value="{{ old('nohp') }}" required>
                                </td>
                                <td></td>
                            </tr>
							<!--
                            <tr>
                                <td style="padding: 12px;"><input type="text" name="noktp" placeholder="Nomor KTP"
                                        readonly="true" required></td>
                                <td></td>
                            </tr>-->
                            <tr>
                                <td style="padding: 12px;"><input type="text" name="nama" placeholder="Nama Lengkap"
                                        readonly="true" required></td>
                                <td></td>
                            </tr>
							
                            <tr>
                                <td style="padding: 12px;">
                                    <table class="subtablenpwp" id="tablenpwp">
                                        <tr>
                                            <td style="padding-bottom: 12px;">
                                                <input class="npwp" type="text" name="npwp[]" placeholder="NPWP" required>
                                            </td>
                                            <td>
                                                <img src="{{ asset('asset/add.png') }}" onclick="createnpwp()">
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: right; padding: 12px;">
                                    <button type="submit" name="submit" value="request">OK</button>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            @endif
            @if ($menu == 'consult' || $menu == 'consult_app')
            
            <table class="reportingtable">
                <tr>
                    <th colspan="2">{{ $header }}<th>
                </tr>
                <tr>
                    <td>
                        <table class="subtable">
                            <tr>
                                <td style="padding: 12px;">
                                    <input class="phone" type="text" name="nohp" placeholder="Nomor HP" value="{{ old('nohp') }}" required>
                                </td>
                                <td></td>
                            </tr>
							<!--
                            <tr>
                                <td style="padding: 12px;"><input type="text" name="noktp" placeholder="Nomor KTP"
                                        readonly="true" required></td>
                                <td></td>
                            </tr>-->
                            <tr>
                                <td style="padding: 12px;"><input type="text" name="nama" placeholder="Nama Lengkap"
                                        readonly="true" required></td>
                                <td></td>
                            </tr>
							
                            <tr>
                                <td style="padding: 12px;">
                                    <table class="subtablenpwp" id="tablenpwp">
                                        <tr>
                                            <td style="padding-bottom: 12px;">
                                                <input class="npwp" type="text" name="npwp[]" placeholder="NPWP" required>
                                            </td>
                                            <td>
                                                <img src="{{ asset('asset/add.png') }}" onclick="createnpwp()">
                                            </td>
                                        </tr>
                                    </table>
								</td>
                            </tr>
                            <tr>
                                <td style="text-align: right; padding: 12px;">
                                    <button type="submit" name="submit" value="{{ $menu }}">OK</button>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            @endif
            @if ($menu == 'counseling')
            
            <table class="counselingtable">
                <tr>
                    <th>Konseling<br>(Bertemu Dengan AR/Pemeriksa/Penagihan)<th>
                </tr>
                <tr>
                    <td style="width: 100%;">
                        <input class="phone" type="text" name="nohp" placeholder="Nomor HP" value="{{ old('nohp') }}" required>
                    </td>
                </tr>
				<tr>
					<td>
                        <input type="text" name="nama" readonly="true" placeholder="Nama Lengkap">
                    </td>
				</tr>
				<tr>
				<td style="width: 50%; text-align: left;padding-top: 30px;padding-bottom: 5px;">
                        <input type="radio" name="employeetype" id="ar" value="Account Representative" checked="true">
                        <label class="employeeType" for="ar">Account Representative</label>
                        <input type="radio" name="employeetype" id="pemeriksaan" value="Pemeriksaan">
                        <label class="employeeType" for="pemeriksaan">Pemeriksaan</label>
                        <input type="radio" name="employeetype" id="jurusita" value="Juru Sita">
                        <label class="employeeType" for="jurusita">Juru Sita</label>
                    </td>
				</tr>
                <tr>
					<!--
                    <td>
                        <input type="text" name="noktp" readonly="true" placeholder="Nomor KTP">
                    </td>
					-->
                    <td style="padding-top: 0px;padding-bottom: 25px;">
                        <select id="employees" name="employeename" required>
                            <option value="">Silahkan pilih petugas yang hendak Anda temui</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table class="subtablenpwp" id="tablenpwp">
                            <tr>
                                <td style="padding-bottom: 12px;">
                                    <input class="npwp" type="text" name="npwp[]" placeholder="NPWP" required>
                                </td>
                                <td>
                                    <img src="{{ asset('asset/add.png') }}" onclick="createnpwp()">
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
				<tr>
                    <td style="text-align: right;">
                        <button type="submit" name="submit" value="counseling">OK</button>
                    </td>
                </tr>
            </table>
            @endif
        
        </div>
    </form>
    <div id="modalNpwps" class="popupnpwp">
		<table>
			<thead>
			<tr>
				<th>Pilih</th>
				<th>NPWP</th>
			</tr>
            </thead>
            <tbody id="npwpsList"></tbody>
        </table>
        <button id="modalNpwpsSubmit" type="submit" name="submit" value="counseling">OK</button>
	</div>
    <script src="{{ asset('js/jquery.js') }}"></script>
    <script src="{{ asset('js/iziToast.min.js') }}"></script>
    <script src="{{ asset('js/iziModal.min.js') }}"></script>
    <script src="{{ asset('js/jquery.mask.min.js') }}"></script>
    <script>
        $(".dropdownbutton").click(function () {
            $('.dropdownboxtransform').toggleClass('dropdownboxtransformactive');
            $('.down').toggleClass('up');
        });
        var menu = "{{ $menu }}"
        $('#modalNpwps').iziModal()
    </script>
    @if ($menu == 'public' || $menu == 'personal')
    
    <script>
        $(document).ready(function () {
            $("#filektp").change(function () {
                var filename = $('#filektp').val().split('\\').pop();
                $('#uploadfilename').text(filename)
            });
        });
    </script>
    @else
    
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        })
		
        var ajaxUrl = "{{ url('registration') }}"
        var ajaxDataUrl = "{{ url('data') }}"
        var deleteIconUrl = "{{ asset('asset/delete.png') }}"
        var i = 0;

        function increment() {
            i += 1;
        }

        function createnpwp() {
            increment();
            var tablenpwp = '<tr id="npwp_'+ i +'"><td style="padding-top:12px; padding-bottom:12px; padding-left:0px;"><input class="npwp" type="text" name="npwp[]" placeholder="NPWP" required></td><td><img src="{{ asset('asset/delete.png') }}" onclick="removenpwp(' +
                i + ')"></td></tr>';
            $("#tablenpwp").append(tablenpwp);
        }

        function removenpwp(id) {
            $("#npwp_" + id).remove();
        }
    </script>
    @endif

    <script src="{{ asset('js/registration.js') }}"></script>

    @if ($errors->any())
    @foreach ($errors->all() as $error)

    <script>
        $(document).ready(function () {
            iziToast.error({
                title: 'Error',
                position: 'topRight',
                transitionIn: 'fadeInDown',
                transitionOut: 'fadeOutUp',
                message: '{!! $error !!}',
            });
        })
    </script>
    @endforeach
    @endif
    
</body>
</html>
