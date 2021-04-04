<?php
namespace ExRiWAArch;

class Commons
{
    public static function getFormData($formData)
    {
        $params = array();
        foreach (explode('&', $formData) as $couple)
        {
            list($key, $val) = explode('=', $couple);
            $params[$key] = urldecode($val);
        }
        return $params;
    }

    private function newSocket(ZMQSocket $soc, $pid)
    {
        echo "Creating New Socket \n", $pid, "\n";

        //Do socket related processing here if needed
    }

    public function triggerPushEvent($pushData)
    {
        try
        {
            $context = new \ZMQContext(1, true);

            $socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'newsocket') or die("Error");

            $socket->connect("tcp://127.0.0.1:5555");

            $socket->send(json_encode($pushData));

            return true;
        }
        catch (Exception $e)
        {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

}
?>