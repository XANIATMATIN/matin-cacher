<?php

namespace MatinUtils\MatinCacher;


class Cacher
{
    protected $socketClient;
    public function __construct()
    {
        $this->connectClient('tables');
        $this->connectClient('localInventory');
        $this->connectClient('popularRoutes');
        $this->connectClient('default');
        $this->connectClient('supplier');
    }

    protected function connectClient($cluster)
    {
        $this->socketClient[$cluster] = new SocketClient(config("matinCacher.clusters.$cluster.host"), config("matinCacher.clusters.$cluster.port"));
    }

    protected function sendData($toSend, $cluster)
    {
        $response = $this->socketClient[$cluster]->sendAndGetResponse($toSend);
        if ($response === 'TryAgain') {
            ///> it means the write couldn't happen, probably bc of a broken pipe
            ///> we'll reconnect the socket and try one more time
            $this->connectClient($cluster);
            $response = $this->socketClient[$cluster]->sendAndGetResponse($toSend);
        }
        return $response;
    }

    public function setItem($item, $value)
    {
        $cluster = explode('.', $item)[0];
        $cluster = empty($this->socketClient[$cluster]) ? 'default' : $cluster;
        return $this->sendData([
            'pid' => app('log-system')->getpid(),
            'api' => 'set',
            'variables' => [],
            'data' => [
                'item' => $item,
                'value' => $value
            ],
        ], $cluster);
    }

    public function forget($items)
    {
        foreach ((array) $items ?? [] as $item) {
            $cluster = explode('.', $item ?? '')[0];
            $data[$cluster][] = $item;
        }
        foreach ($data ?? [] as $cluster => $items) {
            $cluster = empty($this->socketClient[$cluster]) ? 'default' : $cluster;
            $this->sendData([
                'pid' => app('log-system')->getpid(),
                'api' => 'forget',
                'variables' => [],
                'data' => [
                    'items' => $items,
                ],
            ], $cluster);
        }
    }

    public function getItem($item)
    {
        $cluster = explode('.', $item)[0];
        $cluster = empty($this->socketClient[$cluster]) ? 'default' : $cluster;
        return $this->sendData([
            'pid' => app('log-system')->getpid(),
            'api' => 'get',
            'variables' => [],
            'data' => [
                'item' => $item,
            ],
        ], $cluster);
    }

    public function allData($cluster = 'default')
    {
        if (empty($this->socketClient[$cluster])) return false;
        return $this->sendData([
            'pid' => app('log-system')->getpid(),
            'api' => 'allData',
            'variables' => [],
            'data' => [],
        ], $cluster);
    }

    public function refreshTable(array $tables)
    {
        $cluster = 'tables';
        return $this->sendData([
            'pid' => app('log-system')->getpid(),
            'api' => 'database/refreshTable',
            'variables' => [],
            'data' => [
                'tables' => $tables,
            ],
        ], $cluster);
    }

    public function getTDatabaseItem($table, array $conditions = [], array $pluck = [], int $count = 0)
    {
        $cluster = 'tables';
        return $this->sendData([
            'pid' => app('log-system')->getpid(),
            'api' => 'database/get',
            'variables' => [],
            'data' => [
                'table' => $table,
                'conditions' => $conditions,
                'pluck' => $pluck,
                'count' => $count,
            ],
        ], $cluster);
    }

    public function isCached(string $item)
    {
        $cluster = explode('.', $item)[0];
        $cluster = empty($this->socketClient[$cluster]) ? 'default' : $cluster;
        return $this->sendData([
            'pid' => app('log-system')->getpid(),
            'api' => 'isCached',
            'variables' => [],
            'data' => [
                'item' => $item,
            ],
        ], $cluster);
    }

    public function setTag(string $source, string $tag)
    {
        $cluster = 'localInventory';
        return $this->sendData([
            'pid' => app('log-system')->getpid(),
            'api' => 'setTag',
            'variables' => [],
            'data' => [
                'source' => $source,
                'tag' => $tag,
            ],
        ], $cluster);
    }

    public function findTag(string $key, bool $partial = false)
    {
        $cluster = 'localInventory';
        return $this->sendData([
            'pid' => app('log-system')->getpid(),
            'api' => 'findTag',
            'variables' => [],
            'data' => [
                'key' => $key,
                'partial' => $partial
            ],
        ], $cluster);
    }

    public function tagAvailability(string $tag)
    {
        $cluster = 'localInventory';
        return $this->sendData([
            'pid' => app('log-system')->getpid(),
            'api' => 'tagAvailability',
            'variables' => [],
            'data' => [
                'tag' => $tag
            ],
        ], $cluster);
    }
}
