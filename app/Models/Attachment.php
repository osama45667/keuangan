<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attachment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'journal_id',
        'original_name',
        'path',
        'mime',
        'size',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function journal()
    {
        return $this->belongsTo(Journal::class);
    }
}
