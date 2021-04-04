<?php
if (session_status() == PHP_SESSION_NONE)
{
    session_start();
}
use \ExRiWAArch\User;
require 'vendor/autoload.php';

$username = $_SESSION["Username"];
$topics = $_SESSION["topics"];

?>

<html>

<head>
    <link rel="stylesheet" href="View/bootstrap.min.css">
    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/autobahn.min.js"></script>
    <script src="js/articles.js"></script>
    <script src="js/notifications.js"></script>

    <script>
        var username = "<?php echo $username; ?>";
        var topics = "<?php echo $topics; ?>";
    </script>
</head>

<body>
    <?php include "viewTemplateHeader.php"?>
    <div class="container-fluid">
        <h1>Articles</h1>
        <div class="row">           

            <div class="col-6 border">
                <div class="row p-3">
                    <h3>Publish New Article</h3>
                </div>

                <div class="row p-4">
                    <form id="formArticle">
                        <div class="row p-2">
                            Topic:&nbsp;
                            <select name="ddlTopics" id="ddlTopics">
                                <option value="0">--Select Topic--</option>
                                <option value="soa">SOA</option>
                                <option value="cloud">Cloud</option>
                                <option value="iot">IOT</option>
                            </select>
                        </div>

                        <div class="row p-1">
                            Title:&nbsp;<input name="txtTitle" type="text">
                        </div>

                        <div class="row p-1">
                            Content:&nbsp;<input name="txtContent" type="text">
                        </div>

                        <div class="row p-1">
                            <input type="submit" value="Publish">
                        </div>

                         <div id="altArticle" class="alert alert-danger"></div>
                    </form>
                </div>

            </div>

            <div class="col-6 border">
                <h3>Read Articles</h3>
                <div id="articleList" class="row p-3">
                    
                </div>
            </div>

        </div>

    </div>
</body>

</html>