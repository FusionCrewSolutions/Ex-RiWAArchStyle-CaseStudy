<?php
if (session_status() == PHP_SESSION_NONE)
{
    session_start();
}
use \ExRiWAArch\User;
require 'vendor/autoload.php';

$username = $_SESSION["Username"];
$usersList = (new User)->getUsersListInChatRoom();
?>

<html>

<head>
    <link rel="stylesheet" href="View/bootstrap.min.css">
    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/autobahn.min.js"></script>
    <script src="js/chatroom.js"></script>
    <script src="js/notifications.js"></script>

    <script>
        var username = "<?php echo $username; ?>";    
    </script>
</head>

<body>
    <?php include("viewTemplateHeader.php") ?>
    <div class="container-fluid">
        <div class="row">

            <div class="col-8 p-4 border">
                
                <div class="row">
                    <h3>
                        <div class="float-left">Hello&nbsp;</div>
                        <div id="Username" class="float-left"><?php echo $username; ?></div>
                    </h3>
                </div>

                <div class="row">
                    <input type="text" id="txtMsg" />
                    <input type="button" id="btnSendMsg" value="Send" />                    
                </div>

                <div class="row pt-2">
                    <div id="alertSts" class="alert alert-danger"></div>
                </div>

                <div class="row">
                    <div id="divChat">

                    </div>
                </div>

            </div>

            <div id="userList" class="col-4 border">
                <h2>Members</h2>
                <?php echo $usersList; ?>
            </div>

        </div>
    </div>
</body>
</html>