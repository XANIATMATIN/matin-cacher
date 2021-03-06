<?php

namespace MatinUtils\MatinCacher;


class Cacher
{
    protected $socketClient;
    public function __construct()
    {
        $this->socketClient = env('MATIN_CACHER_USABLE') ? new SocketClient(config('matinCacher.easySocket.host')) : false;
    }

    public function loadData($section, $data)
    {
        if ($this->socketClient->isConnected ?? false) {
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
        if ($this->socketClient->isConnected ?? false) {
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
        if ($this->socketClient->isConnected ?? false) {
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
        if ($this->socketClient->isConnected ?? false) {
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

    public function allData()
    {
        if ($this->socketClient->isConnected ?? false) {
            $data = [
                'api' => 'allData',
                'variables' => [],
                'data' => [],
            ];
            return $this->socketClient->sendAndGetResponse($data);
        } else {
            return false;
        }
    }

    public function databaseConfigs($data)
    {
        if ($this->socketClient->isConnected ?? false) {
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
        if ($this->socketClient->isConnected ?? false) {
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

    public function refreshTable(array $tables)
    {
        if ($this->socketClient->isConnected ?? false) {
            $data = [
                'api' => 'database/refreshTable',
                'variables' => [],
                'data' => [
                    'tables' => $tables,
                ],
            ];
            return $this->socketClient->sendAndGetResponse($data);
        } else {
            return false;
        }
    }

    public function getTDatabaseItem($table, array $conditions = [], array $relations = [])
    {
        if ($this->socketClient->isConnected ?? false) {
            $data = [
                'api' => 'database/get',
                'variables' => [],
                'data' => [
                    'table' => $table,
                    'conditions' => $conditions,
                    'relations' => $relations,
                ],
            ];
            return $this->socketClient->sendAndGetResponse($data);
        } else {
            return false;
        }
    }
}
