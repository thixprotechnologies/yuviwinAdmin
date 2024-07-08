<?php

use Carbon\Carbon;
use App\Lib\FileManager;

function menuActive($routeName, $type = null, $param = null)
{
    if ($type == 3) $class = 'show';
    elseif ($type == 2) $class = 'true';
    else $class = 'active';

    if (is_array($routeName)) {
        foreach ($routeName as $key => $value) {
            if (request()->routeIs($value)) return $class;
        }
    } elseif (request()->routeIs($routeName)) {
        if ($param) {
            $routeParam = array_values(@request()->route()->parameters ?? []);
            if (strtolower(@$routeParam[0]) == strtolower($param)) return $class;
            else return;
        }
        return $class;
    }
}
function CostumDateFormet($date, $formet = 'H:i A d/m/Y')
{
    return Carbon::parse($date)->tz('Asia/Kolkata')->format($formet);
}
function getRecharegStatus($status){
    if($status == 1){
        return '<span class="badge bg-warning">PENDING</span>';
    }else if($status == 2){
        return '<span class="badge bg-success">COMPLETE</span>';
    }else if($status == 3){
        return '<span class="badge bg-danger">CANCELLED</span>';
    }else if($status == 4){
        return '<span class="badge bg-danger">REJECTED</span>';
    }else{
        return '<span class="badge bg-info">INITIATED</span>';
    }
}
function getComplainStatus($status){
    if($status == 1){
        return '<span class="badge bg-danger">PENDING</span>';
    }else if($status == 0){
        return '<span class="badge bg-info">REJECTED</span>';
    }else if($status == 2){
        return '<span class="badge bg-danger">COMPLETED</span>';
    }else{
        return '<span class="badge bg-info">PENDING</span>';
    }
}
function getWithDrawStatus($status){
    if($status == 1){
        return '<span class="badge bg-warning">PENDING</span>';
    }else if($status == 2){
        return '<span class="badge bg-success">COMPLETE</span>';
    }else if($status == 3){
        return '<span class="badge bg-danger">CANCELLED</span>';
    }else if($status == 4){
        return '<span class="badge bg-danger">REJECTED</span>';
    }else{
        return '<span class="badge bg-info">INITIATED</span>';
    }
}
function round_to_2dp($number) {
   return number_format($number, 2);
}

function getImage($image,$type = null)
{
    $clean = '';
    if (file_exists($image) && is_file($image)) {
        return asset($image) . $clean;
    }
    if ($type == 'user') {
        return asset('assets/images/avatar.png');
    }
    return asset('assets/images/default.png');
}
function fileUploader($file, $location, $size = null, $old = null, $thumb = null)
{
    $fileManager = new FileManager($file);
    $fileManager->path = $location;
    $fileManager->size = $size;
    $fileManager->old = $old;
    $fileManager->thumb = $thumb;
    $fileManager->upload();
    return $fileManager->filename;
}
function fileManager()
{
    return new FileManager();
}
function getFilePath($key)
{
    return fileManager()->$key()->path;
}
function getFileSize($key)
{
    return fileManager()->$key()->size;
}

function getFileExt($key)
{
    return fileManager()->$key()->extensions;
}

