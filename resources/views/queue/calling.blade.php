<html>

<head>
    <link rel="icon" href="{{ asset('asset/logo.png') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
    <title>Sistem Antrian Kantor Pelayanan Pajak Pratama Palu</title>
    <meta charset="UTF-8">
    <meta name="author" content="Tictoc Group">
    <meta name="keywords" content="Kantor Pajak Pratama Palu, Antrian, Palu, Sulawesi Tengah">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="_token" content='{{ csrf_token() }}' />
</head>

<body class="audiodiv">
    <table class="audiotable">
        <tr>
            <th>
                <img src="{{ asset('asset/logo.png') }}"><br>
                Kantor Pelayanan Pajak<br>Pratama Palu
            </th>
        </tr>
        <tr>
            <td>

            </td>
        </tr>
        <tr>
            <td>
                <img src="{{ asset('asset/audio.png') }}" style="height: 200px; width: auto;">
            </td>
        </tr>
        <tr>
            <td>
                Sistem Antrian Pengunjung
            </td>
        </tr>
    </table>
    <script src="{{ url('js/jquery-3.3.1.min.js') }}"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        })
        var ajaxUrl = "{{ url('calling') }}";

        $(document).ready(function () {
            
            refreshRequest()

            function refreshRequest() {
                setTimeout(() => {
                    call()
                    refreshRequest()
                }, 5000);
            }

            function checkRequest() {
                $.ajax({
                    type: 'POST',
                    url: ajaxUrl + '/get/check_request',
                    data: {},
                    dataType: 'json',
                    success: function (data) {},
                    error: function (xhr, err) {
                        setTimeout(() => {
                            checkRequest()
                        }, 3000);
                    }
                })
            }

            function call() {
                $.ajax({
                    type: 'POST',
                    url: ajaxUrl + '/call',
                    data: {},
                    dataType: 'json',
                    success: function (data) {
                        if ((!data.is_calling) && (!data.call == false)) {
                            callQueue(data.urls, data.call)
                        }
                    },
                    error: function (xhr, err) {
                        setTimeout(() => {
                            call()
                            refreshRequest()
                        }, 3000);
                    }
                })
            }

            function callQueue(urls, call) {
                var index = 0;
                var audio = new Audio(urls[index]);
                var audioNext = new Audio(urls[index + 1]);
                var lastAudio = new Audio(urls[urls.length - 1])
                audio.play()

                index++;

                audio.addEventListener("ended", playAudioNext);
                audioNext.addEventListener("ended", playAudio);

                lastAudio.addEventListener("ended", function () {
                    setCalled(call)
                });

                function playAudioNext() {
                    if (index == urls.length - 1) {
                        audio.pause()
                        index++;
                        lastAudio.play()
                    } else {
                        audioNext.play()
                        index++;
                        audio.src = urls[index]
                    }
                }

                function playAudio() {
                    if (index == urls.length - 1) {
                        audio.pause()
                        index++;
                        lastAudio.play()
                    } else {
                        audio.play()
                        index++;
                        audioNext.src = urls[index]
                    }
                }
            }

            function setCalled(call) {
                console.log(call)
                $.ajax({
                    type: 'POST',
                    url: ajaxUrl + '/called',
                    data: {
                        id: call.id
                    },
                    dataType: 'json',
                    success: function (data) {},
                    error: function (xhr, err) {
                        setTimeout(() => {
                            setCalled()
                        }, 1000);
                    }
                })
            }
        })

    </script>
</body>

</html>
