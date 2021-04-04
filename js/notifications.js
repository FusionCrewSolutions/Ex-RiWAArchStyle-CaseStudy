$(document).ready(function () {    

});//End of document ready

function onNewNotification(data) {
    var notification = "<a class=\"dropdown-item\">" + data + "</a>";
    $("#menuNotifs").prepend(notification);

    var notifCount = $("#txtNotifCount").text();
    notifCount++;
    $("#txtNotifCount").text(notifCount);
}