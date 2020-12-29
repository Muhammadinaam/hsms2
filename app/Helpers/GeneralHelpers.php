<?php

namespace App\Helpers;

use Illuminate\Support\MessageBag;

class GeneralHelpers
{
    public static function RedirectBackResponseWithError($error_title, $error_message)
    {
        $error = new MessageBag([
            'title' => $error_title,
            'message' => $error_message,
        ]);

        return back()->with(compact('error'));
    }

    public static function ReturnJsonErrorResponse($error_title, $error_message)
    {
        $response = [
            'status' => false,
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
        $grid->actions(function ($actions) use ($deleteEnabled, $editEnabled, $viewEnabled, $printEnabled) {
            if (!$deleteEnabled) {
                $actions->disableDelete();
            }

            if (!$editEnabled) {
                $actions->disableEdit();
            }

            if (!$viewEnabled) {
                $actions->disableView();
            }

            if ($printEnabled) {
                $actions->add(\App\Helpers\GeneralHelpers::createPrintAction());
            }

        });
    }

    public static function convertNumberToWord($num = false)
    {
        $num = str_replace(array(',', ' '), '', trim($num));
        if (!$num) {
            return false;
        }
        $num = (int) $num;
        $words = array();
        $list1 = array('', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'eleven',
            'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen',
        );
        $list2 = array('', 'ten', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety', 'hundred');
        $list3 = array('', 'thousand', 'million', 'billion', 'trillion', 'quadrillion', 'quintillion', 'sextillion', 'septillion',
            'octillion', 'nonillion', 'decillion', 'undecillion', 'duodecillion', 'tredecillion', 'quattuordecillion',
            'quindecillion', 'sexdecillion', 'septendecillion', 'octodecillion', 'novemdecillion', 'vigintillion',
        );
        $num_length = strlen($num);
        $levels = (int) (($num_length + 2) / 3);
        $max_length = $levels * 3;
        $num = substr('00' . $num, -$max_length);
        $num_levels = str_split($num, 3);
        for ($i = 0; $i < count($num_levels); $i++) {
            $levels--;
            $hundreds = (int) ($num_levels[$i] / 100);
            $hundreds = ($hundreds ? ' ' . $list1[$hundreds] . ' hundred' . ' ' : '');
            $tens = (int) ($num_levels[$i] % 100);
            $singles = '';
            if ($tens < 20) {
                $tens = ($tens ? ' ' . $list1[$tens] . ' ' : '');
            } else {
                $tens = (int) ($tens / 10);
                $tens = ' ' . $list2[$tens] . ' ';
                $singles = (int) ($num_levels[$i] % 10);
                $singles = ' ' . $list1[$singles] . ' ';
            }
            $words[] = $hundreds . $tens . $singles . (($levels && (int) ($num_levels[$i])) ? ' ' . $list3[$levels] . ' ' : '');
        } //end for loop
        $commas = count($words);
        if ($commas > 1) {
            $commas = $commas - 1;
        }
        return implode(' ', $words);
    }

}
