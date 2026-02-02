<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JournalLine extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'journal_id',
        'account_id',
        'debit',
        'kredit',
        'memo',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function journal()
    {
        return $this->belongsTo(Journal::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
