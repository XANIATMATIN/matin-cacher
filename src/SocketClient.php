<?php

namespace MatinUtils\MatinCacher;

use MatinUtils\EasySocket\Client as EasySocketClient;

class SocketClient extends EasySocketClient
{
    public function sendAndGetResponse($data = '')
    {
        if (!$this->writeOnSocket(app('easy-socket')->prepareMessage(json_encode($data)))) {
            ///> it means the write couldn't happen, probably bc of a broken pipe
            ///> we'll send this back to try reconnecting the socket
            return 'TryAgain'; 
        }
        return $this->readSocket();
    }
}
