<?php

namespace App\Helpers;

class SelectHelper
{
    public static function selectedOptionData($model_name, $id)
    {
        $data = $model_name::find($id);
        
        if ($data) {
            return [$data->id => $data->text_for_select];
        }
    }

    public static function selectModelUrl($model_name, $where_clauses = '')
    {
        $where_clauses = $where_clauses != '' ? '&where_clauses=' . $where_clauses : '';  
        return url('select-data-model?model=' . urlencode($model_name)) . $where_clauses;
    }

    public static function buildAjaxSelect(
        $form, 
        $column, 
        $title, 
        $add_button_url, 
        $model_class, 
        $where_clauses = '',
        $display_attribute = 'text_for_select')
    {
        $ret = $form->select($column, $title)
        ->addVariables(['add_button_url' => $add_button_url])
        ->options(function ($id) use ($model_class) {
            return \App\Helpers\SelectHelper::selectedOptionData($model_class, $id);
        })
        ->ajax(\App\Helpers\SelectHelper::selectModelUrl($model_class, $where_clauses), 'id', $display_attribute);

        return $ret;
    }
}