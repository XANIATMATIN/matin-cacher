<?php

namespace MatinUtils\MatinCacher;


class Cacher
{
    protected $socketClient;
    public function __construct()
    {
        $this->socketClient['tables'] = new SocketClient(config('matinCacher.clusters.tables.host'), config('matinCacher.clusters.tables.port'));
        $this->socketClient['localInventory'] = new SocketClient(config('matinCacher.clusters.localInventory.host'), config('matinCacher.clusters.localInventory.port'));
        $this->socketClient['popularRoutes'] = new SocketClient(config('matinCacher.clusters.popularRoutes.host'), config('matinCacher.clusters.popularRoutes.port'));
        $this->socketClient['default'] = new SocketClient(config('matinCacher.clusters.default.host'), config('matinCacher.clusters.default.port'));
    }

    public function setItem($item, $value)
    {
        $cluster = explode('.', $item)[0];
        $socketClient = $this->socketClient[$cluster] ?? $this->socketClient['default'];
        if ($socketClient->isConnected ?? false) {
            $data = [
                'pid' => app('log-system')->getpid(),
                'api' => 'set',
                'variables' => [],
                'data' => [
                    'item' => $item,
                    'value' => $value
                ],
            ];
            return $socketClient->sendAndGetResponse($data);
        } else {
            return false;
        }
    }

    public function forget($item)
    {
        $cluster = explode('.', $item)[0];
        $socketClient = $this->socketClient[$cluster] ?? $this->socketClient['default'];
        if ($socketClient->isConnected ?? false) {
            $data = [
                'pid' => app('log-system')->getpid(),
                'api' => 'forget',
                'variables' => [],
                'data' => [
                    'items' => $item,
                ],
            ];
            return $socketClient->sendAndGetResponse($data);
        } else {
            return false;
        }
    }

    public function getItem($item)
    {
        $cluster = explode('.', $item)[0];
        $socketClient = $this->socketClient[$cluster] ?? $this->socketClient['default'];
        if ($socketClient->isConnected ?? false) {
            $data = [
                'pid' => app('log-system')->getpid(),
                'api' => 'get',
                'variables' => [],
                'data' => [
                    'item' => $item,
                ],
            ];
            return $socketClient->sendAndGetResponse($data);
        } else {
            return false;
        }
    }

    public function allData($cluster = 'default')
    {
        $socketClient = $this->socketClient[$cluster] ?? null;
        if (empty($socketClient)) return false;
        if ($socketClient->isConnected ?? false) {
            $data = [
                'pid' => app('log-system')->getpid(),
                'api' => 'allData',
                'variables' => [],
                'data' => [],
            ];
            return $socketClient->sendAndGetResponse($data);
        } else {
            return false;
        }
    }

    public function refreshTable(array $tables)
    {
        if ($this->socketClient['tables']->isConnected ?? false) {
            $data = [
                'pid' => app('log-system')->getpid(),
                'api' => 'database/refreshTable',
                'variables' => [],
                'data' => [
                    'tables' => $tables,
                ],
            ];
            return $this->socketClient['tables']->sendAndGetResponse($data);
        } else {
            return false;
        }
    }

    public function getTDatabaseItem($table, array $conditions = [], array $pluck = [], int $count = 0)
    {
        if ($this->socketClient['tables']->isConnected ?? false) {
            $data = [
                'pid' => app('log-system')->getpid(),
                'api' => 'database/get',
                'variables' => [],
                'data' => [
                    'table' => $table,
                    'conditions' => $conditions,
                    'pluck' => $pluck,
                    'count' => $count,
                ],
            ];
            return $this->socketClient['tables']->sendAndGetResponse($data);
        } else {
            return false;
        }
    }

    public function isCached(string $item)
    {
        $cluster = explode('.', $item)[0];
        $socketClient = $this->socketClient[$cluster] ?? $this->socketClient['default'];
        if ($socketClient->isConnected ?? false) {
            $data = [
                'pid' => app('log-system')->getpid(),
                'api' => 'isCached',
                'variables' => [],
                'data' => [
                    'item' => $item,
                ],
            ];
            return $socketClient->sendAndGetResponse($data);
        } else {
            return false;
        }
    }

    public function setTag(string $source, string $tag)
    {
        if ($this->socketClient['localInventory']->isConnected ?? false) {
            $data = [
                'pid' => app('log-system')->getpid(),
                'api' => 'setTag',
                'variables' => [],
                'data' => [
                    'source' => $source,
                    'tag' => $tag,
                ],
            ];
            return $this->socketClient['localInventory']->sendAndGetResponse($data);
        } else {
            return false;
        }
    }

    public function findTag(string $key)
    {
        if ($this->socketClient['localInventory']->isConnected ?? false) {
            $data = [
                'pid' => app('log-system')->getpid(),
                'api' => 'findTag',
                'variables' => [],
                'data' => [
                    'key' => $key,
                ],
            ];
            return $this->socketClient['localInventory']->sendAndGetResponse($data);
        } else {
            return false;
        }
    }

    public function tagAvailability(string $tag)
    {
        if ($this->socketClient['localInventory']->isConnected ?? false) {
            $data = [
                'pid' => app('log-system')->getpid(),
                'api' => 'tagAvailability',
                'variables' => [],
                'data' => [
                    'tag' => $tag
                ],
            ];
            return $this->socketClient['localInventory']->sendAndGetResponse($data);
        } else {
            return false;
        }
    }
}
