$(document).ready(function () {
    initQueue()
    checkQueue()
    refreshQueue()

    function refreshQueue() {
        setTimeout(() => {
            checkQueue()
            refreshQueue()
        }, 2000);
    }

    function initQueue() {
        $.ajax({
            type: 'POST',
            url: ajaxUrl + '/get/init_queue',
            data: {},
            dataType: 'json',
            success: function (data) {
                $('#terminalCounter').text(data.terminalCounter)
                $('#currentQueue').text(data.currentQueue)
                if ((data.isCounselling) && (data.transaction != false)) {
                    $('#cRepresentative').text(data.transaction.c_representative)
                    $('#cType').text(data.transaction.c_type)
                }
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
                    checkQueue()
                }
            })
        }
    })

    $(document).on('click', '#next', function (event) {
        var queue = $('#currentQueue').text()
        $('#currentQueue').text('')
        $('#cRepresentative').text('')
        $('#cType').text('')
        
        $.ajax({
            type: 'POST',
            url: ajaxUrl + '/request_next',
            data: {
                queue: queue
            },
            dataType: 'json',
            success: function (data) {
                if (! data.next == false) {
                    $('#currentQueue').text(data.next)
                    $('#call').trigger('click');
                    if ((data.isCounselling) && (data.transaction != false)) {
                        $('#cRepresentative').text(data.transaction.c_representative)
                        $('#cType').text(data.transaction.c_type)
                    }
                }
                else {
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
                    if ((data.isCounselling) && (data.transaction != false)) {
                        $('#cRepresentative').text(data.transaction.c_representative)
                        $('#cType').text(data.transaction.c_type)
                    }
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
