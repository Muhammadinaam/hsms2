<?php

namespace App\Helpers;

use Illuminate\Support\MessageBag;

class GeneralHelpers
{
    public static function RedirectBackResponseWithError($error_title, $error_message)
    {
        $error = new MessageBag([
            'title'   => $error_title,
            'message' => $error_message,
        ]);
    
        return back()->with(compact('error'));
    }
}