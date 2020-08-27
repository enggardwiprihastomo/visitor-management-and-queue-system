$(document).ready(function () {
    $('.phone').mask('000000000000')
    $('.ktp').mask('0000000000000000')
    var valid = false
    var consult = (menu == 'consult' || menu == 'consult_app')

    $('#registration').on('submit', function (event) {
        if (valid) {
            return
        }

        event.preventDefault()
        var handphhoneCheck = $('input[name="nama"]').val() == '' && $('input[name="noktp"]').val() == ''
        if (handphhoneCheck) {
            showHandphoneConfirmation()
        } else {
            npwpLength = $('.npwp').length
            npwpValid = 0

            $('.npwp').each(function (i, element) {
                var value = element.value
                var npwp = this
                ajaxData = {
                    'data': 'getNpwpFromMaster',
                    "npwp": value
                }

                $.ajax({
                    type: 'POST',
                    url: ajaxDataUrl,
                    data: ajaxData,
                    dataType: 'json',
                    success: function (data) {
                        if (data.exist) {
                            npwpValid++
                        }
                        else {
                            showNpwpConfirmation(npwp)
                        }
                    }
                })
            })

            setTimeout(() => {
                if (npwpLength == npwpValid || consult) {
                    valid = true
                    $('button[name="submit"]').trigger('click')
                }
            }, 2000);
        }
    })

    $('input[name="nohp"]').change(function (event) {
        var aksi = $('button[name="submit"]').val()
        if ((!(aksi == 'personal' || aksi == 'public')) && this.value != '') {

            var value = this.value
            ajaxData = {
                'data': 'getKtpByHandphone',
                "handphone": value
            }

            getKtp(ajaxData)
        }
    })

    $(document).on('focus', '.npwp', function() {
        $(this).css('color', 'grey')
        $(this).css('border-bottom', '')
        $(this).mask('00.000.000.0-000.000')
    })

    $(document).on('focusout', '.npwp', function() {
        var aksi = $('button[name="submit"]').val()
        if (!(aksi == 'personal' || aksi == 'public')) {
            var value = this.value
            if (value != '') {
                ajaxData = {
                    'data': 'getNpwpFromMaster',
                    "npwp": value
                }

                getNpwp(ajaxData, this)
            }
        }
    })
    
    $(document).on('change', '.npwp', function () {
        var aksi = $('button[name="submit"]').val()
        if (!(aksi == 'personal' || aksi == 'public')) {
            var value = this.value
            if (value != '') {
                ajaxData = {
                    'data': 'getNpwpFromMaster',
                    "npwp": value
                }

                getNpwp(ajaxData, this)
            }
        }
    })

    function getKtp(ajaxData) {
        resetNpwp()
        
        $.ajax({
            type: 'POST',
            url: ajaxDataUrl,
            data: ajaxData,
            dataType: 'json',
            success: function (data) {
                if (data.exist) {
                    //$('input[name="noktp"]').val(data.ktp.ktp_number)
                    $('input[name="nama"]').val(data.ktp.name)
                    ajaxData = {
                        'data': 'getNpwpsByHandphone',
                        "handphone": ajaxData.handphone
                    }

                    getNpwps(ajaxData);
                } else if (!data.exist) {
                    //$('input[name="noktp"]').val('')
                    $('input[name="nama"]').val('')
                    showHandphoneConfirmation()
                }
            }
        })
    }

    function resetNpwp() {
        firstNpwp = $('.npwp').first()
        $(firstNpwp).val('')
        $(firstNpwp).trigger('change')
        
        $(".npwp").each(function(index) {
            if (index == 0) {
                return
            }
            $('#npwp_'+index).remove()
        })
    }

    function getNpwps(ajaxData) {
        $.ajax({
            type: 'POST',
            url: ajaxDataUrl,
            data: ajaxData,
            dataType: 'json',
            success: function (data) {
                if (data.exist) {                    
                    npwps = Object.values(data.npwps)
                    $('#npwpsList').text('')
                    npwps.forEach(npwp => {
                        npwpList = '<tr><td><input type="checkbox" value="'+ npwp +
                            '"></td><td>'+npwp+'</td></tr>'
                        $('#npwpsList').append(npwpList)
                    })
                    $('#modalNpwps').iziModal('open');
                }
            }
        })
    }

    $(document).on('click', '#modalNpwpsSubmit', function() {
        npwpList = $('#npwpsList').find(":checked")

        resetNpwp()

        npwpList.each(function(index) {
            var value = $(this).val()
            if (index == 0) {
                firstNpwp = $('.npwp').first()
                $(firstNpwp).val(value)
                $(firstNpwp).trigger('change')

                return
            }

            var tablenpwp = '<tr id="npwp_'+ index +'"><td style="padding-top:12px; padding-bottom:12px; padding-left:0px;"><input class="npwp" type="text" name="npwp[]" placeholder="NPWP" required></td><td><img src="'+deleteIconUrl+'" onclick="removenpwp(' +
                index + ')"></td></tr>';

            $("#tablenpwp").append(tablenpwp);

            lastNpwp = $('.npwp').last()
            $(lastNpwp).val(value)
            $(lastNpwp).trigger('change')

            console.log(value)
        })        
    })

    function getNpwp(ajaxData, npwp) {
        $.ajax({
            type: 'POST',
            url: ajaxDataUrl,
            data: ajaxData,
            dataType: 'json',
            success: function (data) {
                if (data.exist) {
                    $(npwp).css('color', 'green')
                    $(npwp).css('border-bottom', '1px solid green')
                    $(npwp).val(data.npwp.npwp)
                    $(npwp).mask('00.000.000.0-000.000 ')
                    $(npwp).val(data.npwp.npwp + ' - ' + data.npwp.nama)
                } else if (!data.exist) {
                    showNpwpConfirmation(npwp)
                }
            }
        })
    }

    function showHandphoneConfirmation() {
        iziToast.info({
            id: 'question',
            timeout: 20000,
            close: false,
            overlay: true,
            displayMode: 'once',
            closeOnEscape: true,
            zindex: 999,
            title: 'Nomor Tidak Terdaftar',
            message: 'Silahkan mendaftarkan nomor jika ingin melanjutkan',
            position: 'center',
            buttons: [
                ['<button>daftar</button>', function (instance, toast) {
                    instance.hide({
                        transitionOut: 'fadeOut'
                    }, toast, 'button');

                    window.location.href = ajaxUrl;
                }, true],
                ['<button>batal</button>', function (instance, toast) {
                    instance.hide({
                        transitionOut: 'fadeOut'
                    }, toast, 'button');
                }],
            ]
        });
    }

    function showNpwpConfirmation(npwp) {
        if (consult) {
            return
        }

        $(npwp).css('color', 'red')
        $(npwp).css('border-bottom', '1px solid red')
        // npwp.focus()

        // error = iziToast.error({
        //     timeout: false,
        //     close: false,
        //     overlay: true,
        //     displayMode: 'once',
        //     id: 'question',
        //     zindex: 999,
        //     title: 'NPWP Tidak Terdaftar',
        //     message: 'Pastikan NPWP yang anda masukan adalah benar',
        //     position: 'center',
        //     buttons: [
        //         ['<button>ok</button>', function (instance, toast) {
        //             instance.hide({
        //                 transitionOut: 'fadeOut'
        //             }, toast, 'button');
        //             npwp.focus()
        //         }]
        //     ]
        // });
    }

    $(document).on('click', '.employeeType', function() {
        var type = $(this).text()
        var data = 'getEmployee'
        
        $.ajax({
            type: 'POST',
            url: ajaxDataUrl,
            data: {
                data: data,
                type: type,
            },
            dataType: 'json',
            success: function (data) {
                $('#employees').html('<option value="">Silahkan pilih petugas yang hendak Anda temui</option>')
                var employees = data.employees
                if (Array.isArray(employees)){
                    employees.forEach(employee => {
                        $('#employees').append('<option value="'+ employee.id +'">'+ employee.name +'</option>')
                    })
                }
            }
        })
    })

    $('.employeeType:first').trigger('click')
})