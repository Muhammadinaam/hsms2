<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CommonModelWithStatuses extends CommonModel
{
    public $all_statuses = [];
    public $effected_relations = [];
    private $is_changing_cancellation_status = false;

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            if(count($model->all_statuses) == 0)
            {
                throw new Exception("all_statuses array is empty", 1);
            }
            $model->status = $model->all_statuses[0];
        });

        self::saving(function ($model) {
            if($model->id != null && $model->is_changing_cancellation_status == false ) {
                
                $model = self::find($model->id);
                foreach($model->effected_relations as $effected_relation) {
                    $model->{$effected_relation}->setNextOrPreviousStatus(false);
                }
            }
        });

        self::saved(function ($model) {
            if($model->id != null && $model->is_changing_cancellation_status == false ) {
                
                $model = self::find($model->id);
                foreach($model->effected_relations as $effected_relation) {
                    $model->{$effected_relation}->setNextOrPreviousStatus(true);
                }
            }
        });
    }

    /**
     * $is_next = true for next status, false for previous status
     */
    public function setNextOrPreviousStatus($is_next)
    {
        foreach($this->effected_relations as $effected_relation)
        {
            $this->{$effected_relation}->setNextOrPreviousStatus($is_next);
        }

        $current_status = $this->status;
        $current_status_index = array_search($current_status, $this->all_statuses);
        $new_status_index = $is_next ? $current_status_index + 1 : $current_status_index - 1;
        $this->status = $this->all_statuses[$new_status_index];
        $this->save();
    }

    public function setCancelledStatus()
    {
        $this->is_changing_cancellation_status = true;
        foreach($this->effected_relations as $effected_relation)
        {
            $this->{$effected_relation}->setNextOrPreviousStatus(false);
        }

        $this->status = \App\Helpers\StatusesHelper::CANCELLED;
        $this->save();
        $this->is_changing_cancellation_status = false;
    }

    public function unsetCancelledStatus()
    {
        $this->is_changing_cancellation_status = true;
        foreach($this->effected_relations as $effected_relation)
        {
            $this->{$effected_relation}->setNextOrPreviousStatus(true);
        }

        $this->status = $this->all_statuses[0];
        $this->save();
        $this->is_changing_cancellation_status = false;
    }

    public function isEditableOrCancellable()
    {
        return $this->status == $this->all_statuses[0];
    }
}
