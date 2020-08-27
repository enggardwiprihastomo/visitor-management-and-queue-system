$(document).ready(function() { 
    function getVideoList() 
    {
        $.ajax({
            type: 'POST',
            url: ajaxUrl + '/get/video_list',
            data: {},
            dataType: 'json',
            success: function (data) {
                if (data.exist) {
                    playVideo(data.files)
                    console.log(data.files)
                }
            },
			error: function (xhr, err) {
                console.log(err)
				setTimeout(getVideoList, 2000)
			}
        })
    }

    function playVideo(videoList)
    {
        var index = 0
        var videoLength = videoList.length
        var videoPlayer = document.querySelector('#videoPlayer')

        function playVideo(i) {
            url = videoUrl + '/' + videoList[i]
            videoPlayer.src = url
            console.log(url)
            videoPlayer.load()
            videoPlayer.play()
        }

        function playNext() {
            index++
            if (index < videoLength) {
                playVideo(index)
            }
            else {
                index = 0
                playVideo(index)
            }
        }

        playVideo(index)
        videoPlayer.addEventListener('ended', playNext, false)
    }

    function getRunningText() 
    {
        $.ajax({
            type: 'POST',
            url: ajaxUrl + '/get/running_text',
            data: {},
            dataType: 'json',
            success: function (data) {
                if(data.exist) {
                    var runningText = $('#runningText')
                    data.runningTexts.forEach(text => {
                        runningText.append('<img src="'+logoUrl+'">')
                        runningText.append(text.text)
                    })
                    runningText.append('<img src="'+logoUrl+'">')
                }
            },
			error: function (xhr, err) {
				setTimeout(getRunningText, 2000)
			}
        })
    }

    function getTerminal() {
        $.ajax({
            type: 'POST',
            url: ajaxUrl + '/get/terminal',
            data: {},
            dataType: 'json',
            success: function (data) {
                $("#TerminalName").text(data.terminal)
            },
			error: function (xhr, err) {
				setTimeout(getTerminal, 2000)
			}
        })
    }

    function getQueue() {
        $.ajax({
            type: 'POST',
            url: ajaxUrl + '/get/terminal_data',
            data: {},
            dataType: 'json',
            success: function (data) {
                if (data.exist) {
                    displayQueues(data.counters)
                }
                else {
                    $('#counter').text('Loket Kosong')
                    $('#queue').text('-')
                    getQueue()
                }
            },
			error: function (xhr, err) {
				setTimeout(getQueue, 3000)
			}
        })

        function displayQueues(counters) {
            var index = 0
            function displayQueue() {
                var counter = counters[index].queue;
                var newCounter = counter.split('-')
                $('#counter').text('Loket ' + newCounter[0][0] + counters[index].counter)
                $('#queue').text(newCounter[0] + '-' + newCounter[1])
                index++
                if (index < counters.length) {
                    setTimeout(displayQueue, 3000)
                }
                else {
                    setTimeout(getQueue, 3000)
                }
            }

            displayQueue()
        }
    }

    function getWaitingQueue() {
        $.ajax({
            type: 'POST',
            url: ajaxUrl + '/get/remaining_queue',
            data: {},
            dataType: 'json',
            success: function (data) {
                if (data.remainingQueue) {
                    $("#remainingQueue").text(data.remainingQueue)
                }
                else {
                    $("#remainingQueue").text(0)
                }
                
                setTimeout(function() {
                    getWaitingQueue()
                }, 2000)
            },
			error: function (xhr, err) {
				setTimeout(getWaitingQueue, 2000)
			}
        })
    }

    getRunningText()
    getTerminal()
    getQueue()
    getWaitingQueue()
    getVideoList()
});