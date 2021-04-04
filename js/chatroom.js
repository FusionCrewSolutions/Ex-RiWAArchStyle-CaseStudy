$(document).ready(function () {

    // PUSH DC LINKER -----------------------------------------------------
    var abSession = new ab.Session('ws://localhost:8080',
        function () {

            //General BROADCAST msgs linker----------------------
            abSession.subscribe('ChatRoom', function (topic, data) {
                var dataset = JSON.parse(data);

                //Push-DC-Bus Routers ----------------
                if (dataset.url == "chatroom/user") {
                    onNewUserEntered(dataset);
                }

                if (dataset.url == "chatroom/msg") {
                    onNewMsg(dataset);
                }
            });

            //UNICAST msgs linker--------------------------------
            var channel = "ChatRoom" + username;
            abSession.subscribe(channel, function (topic, data) {

                var dataset = JSON.parse(data);

                //Push-DC-Bus Routers --------------
                if (dataset.url == "chatroom/msg") {
                    onNewMsg(dataset);
                }
            });

            //On enter chatroom, notify others------------------
            //This is coded here to make sure the XHR request is sent after subscribing to the 'ChatRoom' topic
            //Or the first user will generate a runtime error if there is no 'ChatRoom' topic
            $.ajax({
                url: "chatroom/user",
                type: "POST",
                processData: false,
                contentType: false
            });
        },
        function () {
            console.warn('WebSocket connection closed');
        },
        {
            'skipSubprotocolCheck': true
        });
});

//
//
// CONTROLLER ==================================================================================
$(document).on("click", "#btnSendMsg", function (event) {
    $("#alertSts").text("Sending...");

    var msgTo = $.map($('input[name="users"]:checked'), function (c) { return c.value; })

    $.ajax({
        url: "chatroom/msg",
        type: "POST",
        processData: false,
        contentType: false,
        data: "Username=" + $("#Username").text() + "&msgTo=" + msgTo + "&msg=" + $("#txtMsg").val(),
        complete: function (response, status) {
            onSendMsgComplete(response.responseText, status);
        }
    });
});

/*DC-Response-Handler*/
function onSendMsgComplete(response, status) {
    if (status == "success") {
        if (response == "true") {
            $("#alertSts").text("Msg sent.");
        }
    }
    else if (status == "error") {
        $("#alertSts").text("Error while sending msg.");
    }
    else {
        $("#alertStsProfile").text("Unknown error while senging msg.");
    }
}

//
//
//Push-DC Handlers -------------------------------------------------------
function onNewUserEntered(dataset) {
    var isUserIn = false;
    var radioBtns = $("#userList").find("input[type='checkbox']");

    $.each(radioBtns, function (i, radiobtn) {
        if (radiobtn.value == dataset.from) {
            isUserIn = true;
        }
    });

    if (!isUserIn && $("#Username").text() != dataset.from) {
        $("#userList").append(dataset.DOMObj);
    }

    if ($("#Username").text() != dataset.from) {
        onNewNotification(dataset.notification);
    }
}

function onNewMsg(dataset) {
    $("#divChat").append(dataset.DOMObj);

    if ($("#Username").text() != dataset.from) {
        onNewNotification(dataset.notification);
    }
}