<?php
namespace ExRiWAArch;

class User
{
    //User Log in to Chat room ----------------------------------
    public function login($formData)
    {
        $params = (new Commons)->getFormData($formData);

        $_SESSION["Username"] = $params["txtUsername"];

        //Subscribed topics--------------------------
        $topics = "";
        foreach (explode('&', $formData) as $couple)
        {
            list($key, $val) = explode('=', $couple);
            if ($key == "chkTopic")
            {
                $topics .= urldecode($val) . ",";
            }
        }

        $topics = rtrim($topics, ',');
        $_SESSION["topics"] = $topics;

        
        //Update list-------------------------------
        $this->updateChatUsers($params["txtUsername"]);

        return "true";
    }

    private function updateChatUsers($username)
    {
        $usersArray = $this->getUsersArrayInChatRoom();

        if (!array_key_exists($username, $usersArray))
        {
            $charUsersFile = fopen("chatUsers.txt", "a") or die("Unable to open file!");
            fwrite($charUsersFile, $username . PHP_EOL);
            fclose($charUsersFile);
        }

        return true;
    }

    private function getUsersArrayInChatRoom()
    {
        $file = fopen("chatUsers.txt", "r");
        $userList = array();

        while (!feof($file))
        {
            $user = trim(fgets($file));

            if ($user != "")
            {
                $userList[$user] = $user;
            }
        }

        fclose($file);

        return $userList;
    }

    public function getUsersListInChatRoom()
    {
        $file = fopen("chatUsers.txt", "r");
        $userList = "";

        while (!feof($file))
        {
            $user = trim(fgets($file));

            $username = $_SESSION["Username"];

            if ($user != "" && $user != $username)
            {
                $userList .= "<input type=\"checkbox\" name=\"users\" value=\"$user\"> &nbsp";
                $userList .= "<label>[$user]</label><br>";
            }
        }

        fclose($file);

        return $userList;
    }

    public function notifyUserEnteringChatroom()
    {
        $userList = "<input type=\"checkbox\" name=\"users\" value=\"" . $_SESSION["Username"] . "\"> &nbsp";
        $userList .= "<label>[" . $_SESSION["Username"] . "]</label><br>";

        $pushData = array(
            'url' => 'chatroom/user',
            'from' => $_SESSION["Username"],
            'to' => '',
            'topic' => '',
            'DOMObj' => $userList,
            'notification' => "New user " . $_SESSION["Username"] . " entered.",
        );

        if ((new Commons)->triggerPushEvent($pushData))
        {
            return "true";
        }
        else
        {
            return "false";
        }
    }

    //Messaging ---------------------------------------------------
    public function sendMsg($formdata)
    {
        $params = (new Commons)->getFormData($formdata);

        $msg = "";
        $msg .= "<div class=\"card\">";
        $msg .= "<div class=\"card-body\">[" . $params["Username"] . "]: " . $params["msg"] . "</div>";
        $msg .= "</div>";

        //If multiple target users are selected, get the list
        $toList = array();

        if ($params["msgTo"] == "")
        {
            $toList[0] = $_SESSION["Username"];
        }
        else
        {
            $toList = explode(",", $_SESSION["Username"] . "," . $params["msgTo"]);
        }

        $pushData = array(
            'url' => 'chatroom/msg',
            'from' => $_SESSION["Username"],
            'to' => $toList,
            'topic' => "",
            'DOMObj' => $msg,
            'notification' => "New msg from " . $_SESSION["Username"] . ".",
        );

        (new Commons)->triggerPushEvent($pushData);

        echo "true";
    }
}
