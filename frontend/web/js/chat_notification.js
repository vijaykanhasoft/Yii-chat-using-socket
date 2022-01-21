//window.onload = function () {
    var chat = new WebSocket('ws://192.168.1.50:1000');

    chat.onopen = function (e) {
        chat.send(JSON.stringify({'action': 'setName', 'id': current_user.user_id}));
    };

    chat.onclose = function (evt) {
        if (evt.code == 3001) {
            $('#response').css('color', 'red').text('Chat server is not reachagle.');
        } else {
            $('#response').css('color', 'red').text('Chat server is not reachagle.');
        }
        chat = null;
    };

    window.setInterval(function(){
      chat.send(JSON.stringify({'action': 'calendarNotification', 'message': 'getNotification', 'user_id': current_user.user_id, 'date_time': moment.utc().unix()}));
    }, 5000);

    chat.onmessage = function (e) {
        $('#response').text('');
        var response = JSON.parse(e.data);
        if (response.type && response.type == 'chat') {
            if ($('[name="to"]').val() !== '' && (($('[name="to"]').val() == response.from_id) || (response.from_id == current_user.user_id)) && document.hasFocus()) {
                if (response.from_id == current_user.user_id) {
                    var ChatMsg = '<li class="right clearfix">';
                    ChatMsg += '<span class="chat-img pull-right">';
                    ChatMsg += '<img src="http://placehold.it/50/FA6F57/fff&text=ME" alt="User Avatar" class="img-circle" />';
                    ChatMsg += '</span>';
                    ChatMsg += '<div class="chat-body clearfix">';
                    ChatMsg += '<div class="header">';
                    ChatMsg += '<small class=" text-muted"><span></span>&nbsp;</small>';
                    ChatMsg += '<strong class="pull-right primary-font">' + response.from + '</strong>';
                    ChatMsg += '</div>';
                    ChatMsg += '<p>' + response.message + '</p>';
                    ChatMsg += '</div>';
                    ChatMsg += '</li>';
                    $('#chat').append(ChatMsg);
                } else {
                    var ChatMsg = '<li class="left clearfix"><span class="chat-img pull-left">';
                    ChatMsg += '<img src="http://placehold.it/50/55C1E7/fff&text=U" alt="User Avatar" class="img-circle" />';
                    ChatMsg += '</span>';
                    ChatMsg += '<div class="chat-body clearfix">';
                    ChatMsg += '<div class="header">';
                    ChatMsg += '<strong class="primary-font">' + response.from + '</strong>';
                    ChatMsg += '<small class="pull-right text-muted">';
                    ChatMsg += '<span></span>&nbsp;</small>';
                    ChatMsg += '</div>';
                    ChatMsg += '<p>' + response.message + '</p>';
                    ChatMsg += '</div>';
                    ChatMsg += '</li>';
                    $('#chat').append(ChatMsg);
                }
            } else {
                notifyMe(response);
                $('#USER' + response.from_id).find('.glyphicon-comment').addClass('quadrat').attr('data-msg', e.data);
            }
        } else if (response.type && response.type == 'notification') {
                var count = $('.badgeAlert').html();
                
                if(parseInt(count) == 0){
                    $('.badgeAlert').addClass('active');
                }
                
                count = parseInt(count) + response.data.length;
                
                $('.badgeAlert').html(response.data.length);            
                
                var notificationHtml = '';
                for(var n=0;n<response.data.length;n++){
                    notificationHtml += '\
                                        <li id="item_notification_1">\
                                        <div class="media">\
                                                <div class="media-left">\
                                                <a href="#">\
                                                <i class="fa fa-users" title="Appointment"></i>\
                                                </a>\
                                        </div>\
                                        <div class="media-body">\
                                                <div class="exclusaoNotificacao"><button class="btn btn-danger btn-xs button_exclusao" id="1" onclick="excluirItemNotificacao(this)">x</button>\
                                        </div>\
                                        <h4 class="media-heading">'+response.data[n].event_title+'</h4>\
                                        <p><i>'+response.data[n].event_description+'</i></p>\
                                        </div>\
                                        </div>\
                                        </li>\
                                ';
                }
            $('.list-notificacao').html(notificationHtml);
        } else if (response.message) {
            $('#response').text(response.message);
        }
        if ($(".panel-body").length > 0) {
            $(".panel-body").animate({
                scrollTop: $('#chat')[0].scrollHeight
            }, 500);
        }
    };

    $('#btnSend').click(function () {
        if ($('[name="to"]').val() == '') {
            alert('Please select user to chat');
            $('#message').focus();
        } else if ($('#message').val() == '') {
            alert('Enter the message');
            $('#message').focus();
        } else {
            chat.send(JSON.stringify({'action': 'chat', 'message': $('#message').val(), 'from': current_user.user_id, 'to': $('[name="to"]').val(), 'date_time': moment.utc().unix()}));
            $('#message').val('')
        }
    });
//};

function selectUser(user_id, element) {
    var Data = {user_id: user_id};
    $.ajax({
        type: 'POST',
        data: Data,
        url: '?r=chat/default/chatdata',
        success: function (response) {
            response = JSON.parse(response);
            var ChatHTML = '';
            if (response.result.length > 0) {
                for (var i = 0; i < response.result.length; i++) {
                    var chatMsgDetail = response.result[i];
                    if (chatMsgDetail.message_from == current_user.user_id) {
                        var ChatMsg = '<li class="right clearfix">';
                        ChatMsg += '<span class="chat-img pull-right">';
                        ChatMsg += '<img src="http://placehold.it/50/FA6F57/fff&text=ME" alt="User Avatar" class="img-circle" />';
                        ChatMsg += '</span>';
                        ChatMsg += '<div class="chat-body clearfix">';
                        ChatMsg += '<div class="header">';
                        ChatMsg += '<small class=" text-muted"><span></span>&nbsp;</small>';
                        ChatMsg += '<strong class="pull-right primary-font">' + chatMsgDetail.username + '</strong>';
                        ChatMsg += '</div>';
                        ChatMsg += '<p>' + chatMsgDetail.message + '</p>';
                        ChatMsg += '</div>';
                        ChatMsg += '</li>';
                        ChatHTML += ChatMsg;
                    } else {
                        var ChatMsg = '<li class="left clearfix"><span class="chat-img pull-left">';
                        ChatMsg += '<img src="http://placehold.it/50/55C1E7/fff&text=U" alt="User Avatar" class="img-circle" />';
                        ChatMsg += '</span>';
                        ChatMsg += '<div class="chat-body clearfix">';
                        ChatMsg += '<div class="header">';
                        ChatMsg += '<strong class="primary-font">' + chatMsgDetail.username + '</strong>';
                        ChatMsg += '<small class="pull-right text-muted">';
                        ChatMsg += '<span></span>&nbsp;</small>';
                        ChatMsg += '</div>';
                        ChatMsg += '<p>' + chatMsgDetail.message + '</p>';
                        ChatMsg += '</div>';
                        ChatMsg += '</li>';
                        ChatHTML += ChatMsg;
                    }
                }
                $('#chat').html(ChatHTML);
            } else {
                $('#chat').html('');
            }
            if ($(".panel-body").length > 0) {
                $(".panel-body").animate({
                    scrollTop: $('#chat')[0].scrollHeight
                }, 500);
            }
        }
    })
    if ($(element).find('.quadrat').length > 0) {
        var message = JSON.parse($(element).find('.quadrat').attr('data-msg'));
        $(element).find('span.quadrat').removeClass('quadrat');
    }
    $('[name="to"]').val(user_id);
    $(element).closest('li').closest('ul').find('li.active').removeClass('active');
    $(element).closest('li').addClass('active');
}
if ($('#ChatWindow').length > 0) {
    document.getElementById('ChatWindow').addEventListener('keypress', function (event) {
        if (event.keyCode == 13) {
            event.preventDefault();
            $('#btnSend').trigger('click');
        }
    });
}

function notifyMe(message) {
    // Let's check if the browser supports notifications
    if (!("Notification" in window)) {
        alert("This browser does not support desktop notification");
    }

    // Let's check if the user is okay to get some notification
    else if (Notification.permission === "granted") {
        // If it's okay let's create a notification
        var options = {
            data: message,
            body: message.message,
            icon: "http://maestroselectronics.com/wp-content/uploads/bfi_thumb/blank-user-355ba8nijgtrgca9vdzuv4.jpg",
            dir: "ltr"
        };
        var notification = new Notification(message.from, options);
        notification.onclick = function (event) {
            event.preventDefault(); // prevent the browser from focusing the Notification's tab
            selectUser(event.currentTarget.data.from_id, $('#USER' + event.currentTarget.data.from_id)[0]);
            this.close();
        }
    }

    // Otherwise, we need to ask the user for permission
    // Note, Chrome does not implement the permission static property
    // So we have to check for NOT 'denied' instead of 'default'
    else if (Notification.permission !== 'denied') {
        Notification.requestPermission(function (permission) {
            // Whatever the user answers, we make sure we store the information
            if (!('permission' in Notification)) {
                Notification.permission = permission;
            }
            // If the user is okay, let's create a notification
            if (permission === "granted") {
                var options = {
                    data: message,
                    body: message.message,
                    icon: "http://maestroselectronics.com/wp-content/uploads/bfi_thumb/blank-user-355ba8nijgtrgca9vdzuv4.jpg",
                    dir: "ltr"
                };
                var notification = new Notification(message.from, options);
                notification.onclick = function (event) {
                    event.preventDefault(); // prevent the browser from focusing the Notification's tab
                    selectUser(event.currentTarget.data.from_id, $('#USER' + event.currentTarget.data.from_id)[0]);
                    this.close();
                }
            }
        });
    }
}
