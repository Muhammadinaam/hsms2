<?php

namespace App\Traits;

trait AttachmentsRelation {
    public function attachments()
    {
        return $this->morphMany('\App\Attachment', 'attachable');
    }
}