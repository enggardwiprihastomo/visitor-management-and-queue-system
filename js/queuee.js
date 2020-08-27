$(document).ready(function () {
    getTerminal()
    refreshQueue()
    initQueue();
    

    function getTerminal() {
        $.ajax({
            type: 'POST',
            url: ajaxUrl + '/get/terminal',
            data: {},
            dataType: 'json',
            success: function (data) {
                $("#terminal").text(data.terminal)
            }
        })
    }

    function refreshQueue() {
        setTimeout(() => {
            checkQueue()
            refreshQueue()
        }, 2000);
    }

    function initQueue() {
        $.ajax({
            type: 'POST',
            url: ajaxUrl + '/get/current_queue',
            data: {},
            dataType: 'json',
            success: function (data) {
                $('#currentQueue').text(data.currentQueue)
            }
        })
    }

    function checkQueue() {
        $.ajax({
            type: 'POST',
            url: ajaxUrl + '/get/check_queue',
            data: {},
            dataType: 'json',
            success: function (data) {
                $('#remainingQueue').text(data.remainingQueue)
                $('#callingHistory').text('')
                data.finishedQueue.forEach(function (queue) {
                    $('#callingHistory').append('<tr><td class="historyQueue" style="width: 90%">' +
                        queue.queue + '</td><td><button class="callAgain" value="'+queue.queue+'">' +
                        '<img src="'+callUrlAsset+'"></button></td></tr>'
                    );
                })
            }
        })
    }

    $(document).on('click', '#counter', function (event) {
        var counter = $('#counter').val()
        $.ajax({
            type: 'POST',
            url: ajaxUrl + '/get/change_counter',
            data: {
                counter: counter
            },
            dataType: 'json',
            success: function (data) {
                initQueue()
                checkQueue()
            }
        })
    })

    $(document).on('click', '#call', function (event) {
        var queue = $('#currentQueue').text()
        if (queue == '') {
            alert('Tidak ada antrian')
        } else {
            $.ajax({
                type: 'POST',
                url: ajaxUrl + '/request_call',
                data: {
                    queue: queue
                },
                dataType: 'json',
                success: function (data) {
                    console.log(queue);
                    console.log(data);
                    checkQueue()
                }
            })
        }
    })

    $(document).on('click', '#next', function (event) {
        var queue = $('#currentQueue').text()
        $.ajax({
            type: 'POST',
            url: ajaxUrl + '/request_next',
            data: {
                queue: queue
            },
            dataType: 'json',
            success: function (data) {
                if (data.next != false) {
                    $('#currentQueue').text(data.next)
                    $('#call').trigger('click');
                }
                else {
                    $('#currentQueue').text('')
                    alert('Tidak ada antrian selanjutnya')
                }
                checkQueue()
            }
        })

    })

    $(document).on('click', '.callAgain', function (event) {
        var queue = $(this).val()
        var currentQueue = $('#currentQueue').text()
        
        $('#currentQueue').text('')
        $('#cRepresentative').text('')
        $('#cType').text('')

        $.ajax({
            type: 'POST',
            url: ajaxUrl + '/call_again',
            data: {
                currentQueue: currentQueue,
                queue: queue
            },
            dataType: 'json',
            success: function (data) {
                if (! data.next == false) {
                    $('#currentQueue').text(data.next)
                    $('#call').trigger('click');
                }
                
                checkQueue()
            }
        })
    })

    $(document).on('click', '.historyQueue', function() {
        getNpwpList($(this).text())
    })

    $(document).on('click', '#currentQueue', function() {
        getNpwpList($(this).text())
    })

    function getNpwpList(queue) {
        $('.iziToast-wrapper').remove()
        $.ajax({
            type: 'POST',
            url: ajaxUrl + '/get/npwp_list',
            data: {
                queue: queue
            },
            dataType: 'json',
            success: function (data) {                    
                console.log(data)
                if(data.exist) {
                    showNpwps(data.npwps)
                }
            }
        })
    }
    
    function showNpwps(npwps) {
        npwpText = ''
        npwps.forEach(npwp => {
            npwpText += '<option>'+ npwp +'</option>'
        })
        npwpSelect = '<select>'+ npwpText +'</select>'

        console.log(npwpSelect)
        iziToast.show({
            id: 'question',
            timeout: false,
            displayMode: 'once',
            closeOnEscape: true,
            zindex: 999,
            title: '',
            message: '',
            position: 'center',
            drag: false,
            inputs: [
                [npwpSelect],
            ],
        });
    }
});
