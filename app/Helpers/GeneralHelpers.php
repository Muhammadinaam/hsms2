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

    public static function ReturnJsonErrorResponse($error_title, $error_message)
    {
        $response = [
            'status'  => false,
            'title' => $error_title,
            'message' => $error_message,
        ];

        return response()->json($response);
    }

    public static function createPrintAction()
    {
        $print = new class extends \Encore\Admin\Actions\RowAction
        {
            public function name()
            {
                return __('Print');
            }

            /**
             * @return string
             */
            public function href()
            {
                return "{$this->getResource()}/{$this->getKey()}/print";
            }
        };
        $print->is_new_window = true;
        return $print;
    }

    public static function setGridRowActions($grid, $deleteEnabled, $editEnabled, $viewEnabled, $printEnabled)
    {
        $grid->actions(function ($actions) use ( $deleteEnabled, $editEnabled, $viewEnabled, $printEnabled ) {
            if(!$deleteEnabled)
                $actions->disableDelete();
            
            if(!$editEnabled)
                $actions->disableEdit();
            
            if(!$viewEnabled)
                $actions->disableView();
            
            if($printEnabled)
                $actions->add(\App\Helpers\GeneralHelpers::createPrintAction());
        });
    }

}