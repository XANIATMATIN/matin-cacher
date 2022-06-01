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
        $data = [
            'api' => 'load',
            'variables' => ['section' => $section],
            'data' => $data,
        ];
        return $this->socketClient->sendAndGetResponse($data);
    }

    public function forget($item)
    {
        $data = [
            'api' => 'forget',
            'variables' => [],
            'data' => [
                'item' => $item,
            ],
        ];
        return $this->socketClient->sendAndGetResponse($data);
    }

    public function getItem($item)
    {
        $data = [
            'api' => 'get',
            'variables' => [],
            'data' => [
                'item' => $item,
            ],
        ];
        return $this->socketClient->sendAndGetResponse($data);
    }

    public function databaseConfigs($data)
    {
        $data = [
            'api' => 'database/configs',
            'variables' => [],
            'data' => $data,
        ];
        return $this->socketClient->sendAndGetResponse($data);
    }

    public function feedTable($table)
    {
        $data = [
            'api' => 'database/loadTable',
            'variables' => [],
            'data' => [
                'table' => $table,
            ],
        ];
        return $this->socketClient->sendAndGetResponse($data);
    }

    public function refreshTable($table)
    {
        $data = [
            'api' => 'database/refreshTable',
            'variables' => [],
            'data' => [
                'table' => $table,
            ],
        ];
        return $this->socketClient->sendAndGetResponse($data);
    }

    public function getTableItem($table, $column, $value)
    {
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
    }
}
