<?php

function loadData($section, $data)
{
    return app('matin-cacher')->loadData($section, $data);
}

function forget($section, $item = null)
{
    $item = $section . (empty($item) ? '' : ".$item");
    return app('matin-cacher')->getItem($item);
}

function getItem($section, $item = null)
{
    $item = $section . (empty($item) ? '' : ".$item");
    return app('matin-cacher')->getItem($item);
}

function databaseConfigs()
{
    return app('matin-cacher')->databaseConfigs(config('database.connections.mysql'));
}

function feedTable($tableName)
{
    return app('matin-cacher')->feedTable($tableName);
}

function refreshTable($tableName)
{
    return app('matin-cacher')->refreshTable($tableName);
}

function getTableItem($tableName, $column, $value)
{
    return app('matin-cacher')->getTableItem($tableName, $column, $value);
}
