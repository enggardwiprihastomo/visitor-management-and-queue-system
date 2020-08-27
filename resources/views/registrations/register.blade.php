<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" href="{{ asset('asset/logo.png') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/form.css') }}">
    <title>Sistem Antrian Kantor Pelayanan Pajak Pratama Palu</title>
    <meta charset="UTF-8">
    <meta name="author" content="Tictoc Group">
    <meta name="keywords" content="Kantor Pajak Pratama Palu, Antrian, Palu, Sulawesi Tengah">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="_token" content='{{ csrf_token() }}' />
    <link rel="stylesheet" href="{{ asset('css/iziToast.min.css') }}">
</head>

<body>
    <form id="registration" action="{{ url('registration') }}" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="back">
            <a href="{{ url('/') }}"><img src="{{ asset('asset/back.png') }}"></a>
        </div>
        <div class="header">
			<img src="{{ asset('asset/formbg.png') }}">
		</div>
        <div class="inputform">
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
                </tr>-->
                <tr>
                    <td><input type="text" name="nama" placeholder="Nama Lengkap" value="{{ old('nama') }}" required></td>
                </tr>
				
                <tr>
                    <td colspan="2" style="text-align: right;">
                        <button type="submit" name="submit" value="{{ $menu }}">OK</button>
                    </td>
                </tr>
            </table>
        </div>
    </form>
    <script src="{{ asset('js/jquery.js') }}"></script>
    <script src="{{ asset('js/iziToast.min.js') }}"></script>
    <script src="{{ asset('js/jquery.mask.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $("#filektp").change(function () {
                var filename = $('#filektp').val().split('\\').pop();
                $('#uploadfilename').text(filename)
            });
        });
		$('.phone').mask('000000000000')
    </script>
    <script src="/js/registration.js"></script>
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
