<?php

function cacheitem($item, $value = '')
{
    $response = app('matin-cacher')->setItem($item, $value);
    return $response === false ? $response : json_decode($response, true);
}

function allCached()
{
    $response = app('matin-cacher')->allData();
    return $response === false ? $response : json_decode($response, true);
}

function removeCache($item)
{
    if (empty($item)) return true;
    $response = app('matin-cacher')->forget($item);
    return $response === false ? $response : json_decode($response, true);
}

function getCachedItem($item)
{
    if (empty($item)) return false;
    $response = app('matin-cacher')->getItem($item);
    return $response === false ? $response : json_decode($response, true)['value'] ?? null;
}

function refreshCachedTables($tableNames)
{
    $tableNames = (array) $tableNames;
    $response = app('matin-cacher')->refreshTable($tableNames);
    return $response === false ? $response : json_decode($response, true);
}

function cachedDatabase(string $tableName, array $conditions = [], array $pluck = [], int $count = 0)
{
    $response = app('matin-cacher')->getTDatabaseItem($tableName, $conditions, $pluck, $count);
    return $response === false ? $response : json_decode($response, true)['value'] ?? [];
}

/**
 * Checks if an item is cached
 *
 *
 * @param  string,     $item     The item to check
 *
 * @return bool|string    false if cache server is not reachable, 'cached' if the item is cached, and 'not-cached' otherwise
 */
function isCached(string $item)
{
    if (empty($item)) return false;
    $response = app('matin-cacher')->isCached($item);
    return $response === false ? $response : json_decode($response, true)['value'] ?? false;
}

function tagCache(string $source, string $tag)
{
    $response = app('matin-cacher')->setTag($source, $tag);
    return $response === false ? $response : json_decode($response, true);
}

function findCachedTag(string $key)
{
    if (empty($key)) return false;
    $response = app('matin-cacher')->findTag($key);
    return $response === false ? $response : json_decode($response, true)['tag'] ?? '';
}

function tagIsCached(string $tag)
{
    if (empty($tag)) return false;
    $response = app('matin-cacher')->tagAvailability($tag);
    return $response === false ? $response : json_decode($response, true)['available'];
}
