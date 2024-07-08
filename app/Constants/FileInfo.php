<?php

namespace App\Constants;

class FileInfo
{

    /*
    |--------------------------------------------------------------------------
    | File Information
    |--------------------------------------------------------------------------
    |
    | This class basically contain the path of files and size of images.
    | All information are stored as an array. Developer will be able to access
    | this info as method and property using FileManager class.
    |
    */

    public function fileInfo()
    {
        $data['homeImg'] = [
            'path'      => 'storage/section/home'
        ];
        $data['aboutImg'] = [
            'path'      => 'storage/section/about'
        ];
        $data['contactImg'] = [
            'path'      => 'storage/section/contact',
        ];
        $data['gamesImg'] = [
            'path'      => 'storage/upload',
        ];
        $data['default'] = [
            'path'      => 'assets/images/default.png',
        ];

        $data['logoIcon'] = [
            'path'      => 'assets/images/logoIcon',
        ];
        $data['favicon'] = [
            'size'      => '128x128',
        ];
        $data['seo'] = [
            'path'      => 'assets/images/seo',
            'size'      => '1180x600',
        ];
        $data['adminProfile'] = [
            'path'      => 'assets/admin/images/profile',
            'size'      => '400x400',
        ];
        return $data;
    }
}
