<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CommonModel extends Model
{
    protected $appends = ['text_for_select'];
    protected static $isDeleteAllowed = false;

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $lastObj = self::orderBy('id', 'desc')->first();
            if($lastObj != null)
            {
                $model->id = $lastObj->id + 1;
            }
            $model->created_by = \Auth::guard('admin')->user()->id;
        });

        self::saving(function ($model) {
            if($model->id != null ) {
                $model->updated_by = \Auth::guard('admin')->user()->id;
            }
        });

        self::deleting(function ($model) {
            if(!self::$isDeleteAllowed)
            {
                throw new \Exception("Delete not allowed", 1);
            }
            
            // foreach (self::$relationMethods as $relationMethod) {
            //     if ($model->$relationMethod()->count() > 0) {
            //         return false;
            //     }
            // }
        });
    }

    public function searchForSelect($search_term, $where_clauses)
    {
        if($where_clauses == '') 
        {
            return $this;
        }

        $data = $this
            ->where(function($query) use ($search_term, $where_clauses) {
                $query->whereRaw($where_clauses);
            });

        return $data;
    }

    public function getTextForSelectAttribute()
    {
        return 'Id: ' . $this->id;
    }

    public function createdBy()
    {
        return $this->belongsTo('\Encore\Admin\Auth\Database\Administrator', 'created_by');
    }
}
