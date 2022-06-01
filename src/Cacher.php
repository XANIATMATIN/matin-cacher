<?php

namespace MatinUtils\MatinCacher;


class Cacher
{
    protected $socketClient;
    public function __construct()
    {
        $this->socketClient = new SocketClient(config('matinCacher.easySocket.host'));
    }

    public function loadData($section, $data)
    {
        if ($this->socketClient->isConnected) {
            $data = [
                'api' => 'load',
                'variables' => ['section' => $section],
                'data' => $data,
            ];
            return $this->socketClient->sendAndGetResponse($data);
        } else {
            return false;
        }
    }

    public function setItem($item, $value)
    {
        if ($this->socketClient->isConnected) {
            $data = [
                'api' => 'set',
                'variables' => [],
                'data' => [
                    'item' => $item,
                    'value' => $value
                ],
            ];
            return $this->socketClient->sendAndGetResponse($data);
        } else {
            return false;
        }
    }

    public function forget($item)
    {
        if ($this->socketClient->isConnected) {
            $data = [
                'api' => 'forget',
                'variables' => [],
                'data' => [
                    'item' => $item,
                ],
            ];
            return $this->socketClient->sendAndGetResponse($data);
        } else {
            return false;
        }
    }

    public function getItem($item)
    {
        if ($this->socketClient->isConnected) {
            $data = [
                'api' => 'get',
                'variables' => [],
                'data' => [
                    'item' => $item,
                ],
            ];
            return $this->socketClient->sendAndGetResponse($data);
        } else {
            return false;
        }
    }

    public function databaseConfigs($data)
    {
        if ($this->socketClient->isConnected) {
            $data = [
                'api' => 'database/configs',
                'variables' => [],
                'data' => $data,
            ];
            return $this->socketClient->sendAndGetResponse($data);
        } else {
            return false;
        }
    }

    public function feedTable($table)
    {
        if ($this->socketClient->isConnected) {
            $data = [
                'api' => 'database/loadTable',
                'variables' => [],
                'data' => [
                    'table' => $table,
                ],
            ];
            return $this->socketClient->sendAndGetResponse($data);
        } else {
            return false;
        }
    }

    public function refreshTable($table)
    {
        if ($this->socketClient->isConnected) {
            $data = [
                'api' => 'database/refreshTable',
                'variables' => [],
                'data' => [
                    'table' => $table,
                ],
            ];
            return $this->socketClient->sendAndGetResponse($data);
        } else {
            return false;
        }
    }

    public function getTableItem($table, $column, $value)
    {
        if ($this->socketClient->isConnected) {
            $data = [
                'api' => 'database/find',
                'variables' => [],
                'data' => [
                    'table' => $table,
                    'column' => $column,
                    'value' => $value,
                ],
            ];
            return $this->socketClient->sendAndGetResponse($data);
        } else {
            return false;
        }
    }
}
