<?php
namespace ExRiWAArch;

use Ratchet\ConnectionInterface;
use Ratchet\Wamp\WampServerInterface;

class DCPushSender implements WampServerInterface
{
    protected $subscribedTopics = array();

    public function onOpen(ConnectionInterface $conn)
    {
        echo "Opened\n";
    }

    public function onClose(ConnectionInterface $conn)
    {
        echo "Closed\n";
    }

    public function onSubscribe(ConnectionInterface $conn, $topic)
    {
        $this->subscribedTopics[$topic->getId()] = $topic;
        echo "Subscribed $topic \n";
    }

    public function onUnSubscribe(ConnectionInterface $conn, $topic)
    {
    }

    //
    //
    //
    //== CALLS FROM SERVER-MODEL =====================================================
    //Trigered by the Web server via ZMQ as defined in WS server (DCPushServer.php)
    //Broadcasted to all the subscribers of the topic
    public function onWebServerCall($data)
    {
        $dataset = json_decode($data, true);

        //Routers ===============================================================
        switch ($dataset['url'])
        {
            //User enters into the chat room----------------------------
            case "chatroom/user":
                $topic = $this->subscribedTopics["ChatRoom"];
                $topic->broadcast($data);
                echo $dataset['from'] . " entered.\n";
                break;

            //User sends a msg------------------------------------------
            case "chatroom/msg":
                $toList = $dataset['to'];

                //BROADCAST to everyone in the room----
                if (count($toList) == 1 && $toList[0] == $dataset['from'])
                {
                    $topic = $this->subscribedTopics["ChatRoom"];
                    $topic->broadcast($data);
                    echo "Message from : " . $dataset['from'] . ".\n";
                }
                //MULTICAST to selected users---------
                else if (count($toList) > 1)
                {
                    foreach ($toList as $toUser)
                    {
                        if (trim($toUser) != "")
                        {
                            $topic = $this->subscribedTopics["ChatRoom" . $toUser];
                            $topic->broadcast($data);
                            echo "Messaged to : " . $toUser . ".\n";
                        }
                    }
                }
                break;

            //Article publish-----------------------------------------
            case "article":
                $channel = "Articles" . $dataset['topic'];
                $topic = $this->subscribedTopics[$channel];
                $topic->broadcast($data);
                echo "Article published.\n";
                break;
        }
    }

    //
    //
    //
    //== SECURITY ==========================================================================
    public function onCall(ConnectionInterface $conn, $id, $topic, array $params)
    {
        // In this application if clients send data it's because the user hacked around in console
        $conn->callError($id, $topic, 'You are not allowed to make calls')->close();
    }

    public function onPublish(ConnectionInterface $conn, $topic, $event, array $exclude, array $eligible)
    {
        // In this application if clients send data it's because the user hacked around in console
        $conn->close();
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "Error \n";
    }
}
