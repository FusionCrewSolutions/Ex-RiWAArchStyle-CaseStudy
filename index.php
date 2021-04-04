<?php 
if (session_status() == PHP_SESSION_NONE)
{
    session_start();
}
?>
<html>

<head>
    <link rel="stylesheet" href="View/bootstrap.min.css">
    <script src="js/jquery-3.2.1.min.js"></script>

    <script>
    $(document).on("submit", "#formLogin", function(event) 
    {
        event.preventDefault();
        event.stopPropagation();

        $.ajax({
            url: "Login",
            type: "POST",
            processData: false,
            contentType: false,
            data: $("#formLogin").serialize(),
            complete: function(response, status) {
                onLoginComplete(response.responseText, status);
            }
        });
    });

    function onLoginComplete(response, status) {
        if (response == "true") {
            document.location = "viewChatRoom.php";
        } else if (response == "false") {
            $("#divError").text("Error while loging in..");
            $("#divError").show();
        } else {
            $("#divError").text("Unknown error while loging in..");
            $("#divError").show();
        }
    }
    </script>
</head>

<body>
    <div class="container">
        <div class="row justify-content-md-center">
            <div class="col-6">
                <form id="formLogin" class="border p-2 m-5">

                    <div class="row d-flex justify-content-center m-2">
                        Username:&nbsp;<input type="text" name="txtUsername" id="txtMsg" />
                    </div>

                    <div class="row d-flex justify-content-center m-2">
                        Topics:&nbsp;
                        <input type="checkbox" name="chkTopic" value="soa" />SOA,&nbsp;
                        <input type="checkbox" name="chkTopic" value="cloud" />Cloud,&nbsp;
                        <input type="checkbox" name="chkTopic" value="iot" />IoT
                    </div>

                    <div class="row d-flex justify-content-center m-2">
                        <input type="submit" value="Login" class="btn btn-primary"/>
                    </div>

                    <div class="row d-flex justify-content-center m-2">
                        <div class="alert alert-danger" id="divError"></div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</body>

</html>