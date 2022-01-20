<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Response extends Model
{
    public static function success($code, $message, $data = [])
    {
        return response()->json([
            'code'    => $code,
            'status'  => true,
            'message' => $message,
            'data'    => $data
        ]);
    }

    public static function error($code, $message)
    {
        return response()->json([
            'code'    => $code,
            'status'  => false,
            'message' => $message
        ]);
    }
}
