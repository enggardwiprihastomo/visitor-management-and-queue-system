$(document).ready(function () {    
    $(document).on('change', '#terminal', function() {
        var counter = $('#terminal').find(':selected').attr('data-counter')
        $('#counter').text(counter)
    });

    $(document).on('click', '#btnCounterPlus', function() {
        var counter = parseInt($('#counter').text())
        $('#counter').text(counter + 1)
    });

    $(document).on('click', '#btnCounterMin', function() {
        var counter = parseInt($('#counter').text())
        if (counter > 1) 
            $('#counter').text(counter - 1)
    });

    $(document).on('click', '#btnCounterSubmit', function() {
        var terminal = $('#terminal').val()
        var counter = $('#counter').text()
        var aksi = 'update_counter'

        $.ajax({
            type: 'POST',
            url: ajaxUrl,
            data: {
                terminal: terminal,
                counter: counter,
                aksi: aksi
            },
            dataType: 'json',
            success: function (data) {
                $('#terminal').find(':selected').attr('data-counter', counter)
                showMessage('Berhasil', '#55efc4', 'Counter tersimpan')
            }
        })
    });

    $(document).on('click', '.media', function() {
        media = $(this).attr('data-value')
    })

    $(document).on('click', '#btnMediaSubmit', function() {
        var aksi = 'update_media'
        $.ajax({
            type: 'POST',
            url: ajaxUrl,
            data: {
                media: media,
                aksi: aksi
            },
            dataType: 'json',
            success: function (data) {
                showMessage('Berhasil', '#55efc4', 'Media berhasil diupdate')
            }
        })
    })

    $(document).on('click', '#btnRunningTextSubmit', function() {
        var text = $('#runningText').val()
        if (text == '') {
            showMessage('Salah', '#ff7675', 'Running text kosong')
            return;
        }
        var aksi = 'add_running_text'

        $.ajax({
            type: 'POST',
            url: ajaxUrl,
            data: {
                text: text,
                aksi: aksi
            },
            dataType: 'json',
            success: function (data) {
                var id = $('#runningTextList tr').length + 1
                $('#runningTextList').append('<tr id="runningText'+ data.runningText.id +'">'+
                    '<td class="runningtextcaption">'+ data.runningText.text +'</td><td class="runningtextdelete">'+
                    '<button class="deleteRunningText" value="'+data.runningText.id+'">'+
                    '</button></td></tr>')
                showMessage('Berhasil', '#55efc4', 'Running text berhasil disimpan')
            }
        })
    });

    $(document).on('click', '.deleteRunningText', function() {
        var id = $(this).val()
        var aksi = 'delete_running_text'
        $.ajax({
            type: 'POST',
            url: ajaxUrl,
            data: {
                id: id,
                aksi: aksi
            },
            dataType: 'json',
            success: function (data) {
                $('#runningText'+id).remove()
				console.log('#runningText'+id)
                showMessage('Berhasil', '#55efc4', 'Running text berhasil dihapus')
            }
        })
    });

    $(document).on('change', '#employeeType', function() {
        var type = this.value
        getEmployee(type)
    });

    function getEmployee(type)
    {
        var aksi = 'get_employee'
        $.ajax({
            type: 'POST',
            url: ajaxUrl,
            data: {
                aksi: aksi,
                type: type
            },
            dataType: 'json',
            success: function (data) {
                $('#employeeList').text('')
                data.employees.forEach(employee => {
                    if (type == 'Pegawai Loket') {
                        var employeeList = '<tr id="employee'+ employee.id +
                            '" class="employee-counter" data-id="'+ employee.id + 
                            '" data-username="'+ employee.username + 
                            '" data-name="'+ employee.name + 
                            '"><td id="employeeName'+ employee.id +
                            '"  class="employeename">'+ employee.name +
                            '</td><td class="employeetype">'+ type +
                            '</td><td class="employeedelete"><button value="'+ employee.id +
                            '" class="deleteEmployee"></button></td></tr>'
                    }
                    else {
                        var employeeList = '<tr id="employee'+ employee.id +
                            '"><td id="employeeName'+ employee.id +
                            '"  class="employeename">'+ employee.name +
                            '</td><td class="employeetype">'+ type +
                            '</td><td class="employeedelete"><button value="'+ employee.id +
                            '" class="deleteEmployee"></button></td></tr>'
                    }

                    $('#employeeList').append(employeeList)
                });
            }
        })
    }

    $(document).on('click', '.employee-counter', function() {
        var employeeCounter = this
        var id = $(employeeCounter).attr('data-id')
        var username = $(employeeCounter).attr('data-username')
        var name = $(employeeCounter).attr('data-name')
        
        iziToast.show({
            id: 'question',
            timeout: false,
            close: false,
            overlay: true,
            displayMode: 'once',
            closeOnEscape: true,
            zindex: 999,
            title: '',
            message: '',
            position: 'center',
            drag: false,
            inputs: [
                ['<label>Username: </>'],
                ['<input id="employeeDataUsername" type="text" value="'+ username +'" placeholder="Username">'],
                ['<label>Nama: </>'],
                ['<input id="employeeDataName" type="text" value="'+ name +'" placeholder="Name">'],
                ['<input id="employeeDataPassword" type="password" placeholder="Password">'],
            ],
            buttons: [
                ['<button>Update</button>', function (instance, toast) {
                    ajaxData = {
                        aksi: 'update_employee_counter',
                        id: id,
                        username: $('#employeeDataUsername').val(),
                        name: $('#employeeDataName').val(),
                        password: $('#employeeDataPassword').val(),
                    }

                    updateEmployeeCounter(ajaxData, employeeCounter)
                    instance.hide({
                        transitionOut: 'fadeOut'
                    }, toast, 'button');
                }, true],
                ['<button>batal</button>', function (instance, toast) {
                    instance.hide({
                        transitionOut: 'fadeOut'
                    }, toast, 'button');
                }],
            ]
        });

        function updateEmployeeCounter(ajaxData, employeeCounter) {
            $.ajax({
                type: 'POST',
                url: ajaxUrl,
                data: ajaxData,
                dataType: 'json',
                success: function (data) {
                    $(employeeCounter).find('.employeename').text(data.employee.name)
                    showMessage('Berhasil', '#55efc4', 'Pegawai berhasil diupdate')
                }
            })
        }
    })

    $(document).on('click', '#employeeAdd', function() {
        var name = $('#employeeName').val()
        if (name == '') {
            showMessage('Salah', '#ff7675', 'Nama pegawai kosong')
            return;
        }

        var type = $('#employeeType').val()
        var aksi = 'add_employee'

        $.ajax({
            type: 'POST',
            url: ajaxUrl,
            data: {
                aksi: aksi,
                type: type,
                name: name
            },
            dataType: 'json',
            success: function (data) {
                $('#employeeList').append('<tr id="employee'+ data.employee.id +
                    '" class="employee-data" data-id="'+ data.employee.id + 
                    '"><td id="employeeName'+ data.employee.id +
                    '" class="employeename">'+ data.employee.name +
                    '</td><td class="employeetype">'+ type +
                    '</td><td class="employeedelete"><button value="'+ data.employee.id +
                    '" class="deletebtn deleteEmployee"></button></td></tr>')
                
                $('#employeeName').val('')
                showMessage('Berhasil', '#55efc4', 'Pegawai berhasil disimpan')
            }
        })
    });

    $(document).on('click', '.deleteEmployee', function() {
        var aksi = 'delete_employee'
        var id = $(this).val()
        var name = $('#employeeName'+id).text()
        var hapus = confirm('Apakah anda yakin ingin menghapus pegawai '+ name + '?')
        if (! hapus){
            return
        }
        var type = $('#employeeType').val()

        $.ajax({
            type: 'POST',
            url: ajaxUrl,
            data: {
                id: id,
                type: type,
                aksi: aksi
            },
            dataType: 'json',
            success: function (data) {
                $('#employee'+id).remove()
                showMessage('Berhasil', '#55efc4', 'Employee berhasil dihapus')
            }
        })
    });
	
	function getHistoryQueue(){
		$.ajax({
            type: 'POST',
            url: ajaxUrl + '/get/history_queue',
            data: {
            
            },
            dataType: 'json',
            success: function (data) {
                $('#historyQueue').text('')
				
				data.queues.forEach(queue => {
				$('#historyQueue').append('<tr><td class="resendnumber">'+ queue.handphone + ' - ' + queue.transaction +
				'</td><td class="resendicon"><button class="printQueue" data-id="'+
				queue.id +'"></button></td></tr>')
				})
            }
        })
	}
	
	$(document).on('click', '.printQueue', function() {
		$.ajax({
            type: 'POST',
            url: ajaxUrl + '/print_queue',
            data: {
				id: $(this).attr('data-id')
            },
            dataType: 'json',
            success: function (data) {
                showMessage('Berhasil', '#55efc4', 'Antrian berhasil dicetak')
            }
        })
	})

    $('#terminal').trigger('change')
    $('#employeeType').trigger('change')
	getHistoryQueue()

    function showMessage(title, color, message)
    {
        iziToast.show({
            backgroundColor: color,
            title: title,
            message: message,
            position: 'topRight',
            transitionIn: 'fadeInDown',
            transitionOut: 'fadeOutUp',
        });
    }
});