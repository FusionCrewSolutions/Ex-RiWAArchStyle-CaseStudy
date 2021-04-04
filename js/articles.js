$(document).ready(function () {

    var abSession = new ab.Session('ws://localhost:8080', 
    function () {

        //Listen to all subscribed topics-------------------------
        var topicsArr = topics.split(',');

        var channel = "";
        $.each(topicsArr, function (i) {            
            channel = 'Articles' + topicsArr[i];
            abSession.subscribe(channel, function (topic, data) {
                onNewArticlePublish(data);
            });
        });            
    },
    function () { 
        console.warn('WebSocket connection closed'); 
    },
    {
        'skipSubprotocolCheck': true
    });

});//End of document ready


function onNewArticlePublish(data) {
    console.log(data);
    var dataset = JSON.parse(data);

    //DC-Bus Routers ---------------------------------------
    if (dataset.url == "article") {
        $("#articleList").prepend(dataset.DOMObj);

        if ($("#Username").text() != dataset.from) {
            onNewNotification(dataset.notification);
        }
    }
}


//Submit Article-------------------------------------------------------------------------
$(document).on("submit", "#formArticle", function (event) {

    event.preventDefault();
    event.stopPropagation();

    $("#altArticle").text("Posting...");

    $.ajax({
        url: "article",
        type: "POST",
        processData: false,
        contentType: false,
        data: $("#formArticle").serialize(),
        complete: function (response, status) {
            onArticlePostComplete(response.responseText, status);
        }
    });
});

function onArticlePostComplete(response, status) {
    if (status == "success") {
       
        if (response == "true") {
            $("#altArticle").text("Published.");
            $("#formArticle")[0].reset();
        }
    }
    else if (status == "error") {
        $("#altArticle").text("Error while posting article.");
    }
    else {
        $("#altArticle").text("Unknown error while posting article.");
    }
}