<?php

namespace MatinUtils\MatinCacher;

use MatinUtils\EasySocket\Client as EasySocketClient;

class SocketClient extends EasySocketClient
{
    public function sendAndGetResponse($data = '')
    {
        $data = json_encode($data) . "\0";
        $this->writeOnSocket($data);
        $input = $this->readSocket();
        return $input;
    }
}
