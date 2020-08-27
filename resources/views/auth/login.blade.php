<html>

<head>
    <link rel="icon" href="{{ asset('asset/logo.png') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/login.css') }}">
    <title>Sistem Antrian Kantor Pelayanan Pajak Pratama Palu</title>
    <meta charset="UTF-8">
    <meta name="author" content="Tictoc Group">
    <meta name="keywords" content="Kantor Pajak Pratama Palu, Antrian, Palu, Sulawesi Tengah">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/iziToast.min.css') }}">
</head>

<body>
    <form action="{{ url('login') }}" method="post">
        {{ csrf_field() }}
        <div class="loginborder">

            <table>
                <tr>
                    <td><img src="{{ asset('asset/logo.png') }}"></td>
                </tr>
                <tr>
                    <td>
                        Kantor Pelayanan Pajak<br>Pratama Palu
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="text" name="username" placeholder="Username" required>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="password" name="password" placeholder="Password" required>
                    </td>
                </tr>
                <tr>
                    <td>
                        <select id="terminal" name="terminal">
                            <option value="">Terminal</option>
                            @foreach ($terminals as $terminal)
                            <option value="{{ $terminal->id }}" data-counter="{{ $terminal->counter }}">{{
                                $terminal->service }}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>

                <tr>
                    <td>
                        <select id="counter" name="counter">
                            <option value="">Counter</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td>
                        <button type="submit" name="submit" value="login">OK</button>
                    </td>
                </tr>
            </table>

        </div>
    </form>
    <script src="{{ asset('js/jquery.js') }}"></script>
    <script src="{{ asset('js/iziToast.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('#terminal').change(function () {
                $('#counter').empty()
                $('#counter').removeAttr('required')
                $('#counter').append('<option value="">Counter</option>')
                if (this.value != '') {
                    $('#counter').attr('required', 'required')
                    for (iter = 1; iter <= $('#terminal').find(':selected').attr('data-counter'); iter++) {
                        $('#counter').append('<option value="' + iter + '">' + iter + '</option>')
                    }
                }
            })
        })

    </script>
    @if (Session::has('error'))

    <script>
        $(document).ready(function () {
            iziToast.error({
                title: 'Error',
                position: 'topRight',
                transitionIn: 'fadeInDown',
                transitionOut: 'fadeOutUp',
                message: '{!! Session::get("error") !!}',
            });
        })
    </script>

    @endif
</body>

</html>
