<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Journal extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'tanggal',
        'nomor_jurnal',
        'period_id',
        'deskripsi',
        'reference_no',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function period()
    {
        return $this->belongsTo(AccountingPeriod::class, 'period_id');
    }

    public function lines()
    {
        return $this->hasMany(JournalLine::class);
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }
}
