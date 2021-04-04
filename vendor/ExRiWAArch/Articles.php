<?php
namespace ExRiWAArch;

class Articles
{
    public function publishArticle($formData)
    {
        $params = (new Commons)->getFormData($formData);

        $article = "";
        $article .= "<div class=\"card\"><div class=\"card-body\"><div class=\"container\">";
        $article .= "<div class=\"row p-1\">";
        $article .= "<h6>" . $params["ddlTopics"] . ": " . $params["txtTitle"] . " by " . $_SESSION["Username"] . "</h6>";
        $article .= "</div>";
        $article .= "<div class=\"row p-1\">";
        $article .= $params["txtContent"];
        $article .= "</div>";
        $article .= "</div></div></div>";

        $pushData = array(
            'url' => 'article',
            'from' => $_SESSION["Username"],
            'to' => '',
            'topic' => $params["ddlTopics"],
            'DOMObj' => $article,
            'notification' => "New article: " . $params["txtTitle"] . ".",            
        );

        //UNICAST to one target user
        (new Commons)->triggerPushEvent($pushData);

        echo "true";
    }
}
