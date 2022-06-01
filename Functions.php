<?php

function loadData($section, $data)
{
    $response = app('matin-cacher')->loadData($section, $data);
    return $response === false ? $response : json_decode($response, true);
}

function cacheitem($item, $value = '')
{
    $response = app('matin-cacher')->setItem($item, $value);
    return $response === false ? $response : json_decode($response, true);
}

function removeCache($item)
{
    $response = app('matin-cacher')->forget($item);
    return $response === false ? $response : json_decode($response, true);
}

function getCachedItem($item)
{
    $response = app('matin-cacher')->getItem($item);
    return $response === false ? $response : json_decode($response, true)['value'] ?? null;
}

function databaseConfigs()
{
    $response = app('matin-cacher')->databaseConfigs(config('database.connections.mysql'));
    return $response === false ? $response : json_decode($response, true);
}

function feedTable($tableName)
{
    $response = app('matin-cacher')->feedTable($tableName);
    return $response === false ? $response : json_decode($response, true);
}

function refreshTable($tableName)
{
    $response = app('matin-cacher')->refreshTable($tableName);
    return $response === false ? $response : json_decode($response, true);
}

function getTableItem($tableName, $column, $value)
{
    $response = app('matin-cacher')->getTableItem($tableName, $column, $value);
    return $response === false ? $response : json_decode($response, true);
}
