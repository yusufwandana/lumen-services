<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Intervention\Image\ImageManagerStatic as Image;

class Controller extends BaseController
{
    protected function uploadService($file, $dir, $name = null)
    {
        try {
            # check name if exist.
            isset($name) ? $name = $name.'_' : $name = null;
            # check name of directory.
            (substr($dir,-1) != '/') ? $dir = $dir.'/' : $dir;
            # set file name.
            $filename = $name.rand().time().'.'.$file->getClientOriginalExtension();
            # resize image
            $image = Image::make($file->getRealPath());
            $image->resize(null, 400, function($constraint){
                $constraint->aspectRatio();
            });
            # upload file
            $image->save($dir.$filename);
            # response
            $status  = true;
            $message = 'File uploaded';
            $data    = $dir.$filename;
            
        } catch (\Throwable $th) {
            # response
            $status  = false;
            $message = $th->getMessage();
            $data    = null;
        }
        # return result
        return [
            'status'  => $status,
            'message' => $message,
            'data'    => $data,
        ];

    }
}
