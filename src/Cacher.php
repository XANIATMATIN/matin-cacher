<?php

namespace MatinUtils\MatinCacher;


class Cacher
{
    protected $socketClient;
    protected $availableClusters = ['tables', 'localInventory', 'popularRoutes', 'default', 'supplier'];

    public function setItem($item, $value)
    {
        $cluster = explode('.', $item)[0];
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
        return $this->sendData([
            'pid' => app('log-system')->getpid(),
            'api' => 'allData',
            'variables' => [],
            'data' => [],
        ], $cluster);
    }

    public function refreshTable(array $tables)
    {
        return $this->sendData([
            'pid' => app('log-system')->getpid(),
            'api' => 'database/refreshTable',
            'variables' => [],
            'data' => [
                'tables' => $tables,
            ],
        ], 'tables');
    }

    public function getTDatabaseItem($table, array $conditions = [], array $pluck = [], int $count = 0)
    {
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
        ], 'tables');
    }

    public function isCached(string $item)
    {
        $cluster = explode('.', $item)[0];
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
        return $this->sendData([
            'pid' => app('log-system')->getpid(),
            'api' => 'setTag',
            'variables' => [],
            'data' => [
                'source' => $source,
                'tag' => $tag,
            ],
        ], 'localInventory');
    }

    public function findTag(string $key, bool $partial = false)
    {
        return $this->sendData([
            'pid' => app('log-system')->getpid(),
            'api' => 'findTag',
            'variables' => [],
            'data' => [
                'key' => $key,
                'partial' => $partial
            ],
        ], 'localInventory');
    }

    public function tagAvailability(string $tag)
    {
        return $this->sendData([
            'pid' => app('log-system')->getpid(),
            'api' => 'tagAvailability',
            'variables' => [],
            'data' => [
                'tag' => $tag
            ],
        ], 'localInventory');
    }

    protected function sendData($toSend, $cluster)
    {
        $cluster = in_array($cluster, $this->availableClusters) ? $cluster : 'default';
        if (!$this->socketIsConnected($cluster)) {
            $this->connectClient($cluster);
        }
        $response = $this->socketClient[$cluster]->sendAndGetResponse($toSend);
        if ($response === 'TryAgain') {
            app('log')->info("TryAgain");
            ///> it means the write couldn't happen, probably bc of a broken pipe
            ///> we'll reconnect the socket and try one more time
            $this->connectClient($cluster);
            $response = $this->socketClient[$cluster]->sendAndGetResponse($toSend);
        }
        return $response;
    }

    protected function socketIsConnected($cluster)
    {
        return !empty($this->socketClient[$cluster]) && $this->socketClient[$cluster]->isConnected;
    }

    protected function connectClient($cluster)
    {
        $this->socketClient[$cluster] = new SocketClient(config("matinCacher.clusters.$cluster.host"), config("matinCacher.clusters.$cluster.port"));
    }

    public function closeSocket()
    {
        foreach ($this->socketClient ?? [] as $socketClient) {
            return $socketClient->closeSocket();
        }
    }
}
