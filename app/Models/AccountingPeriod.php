<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountingPeriod extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'bulan',
        'tahun',
        'start_date',
        'end_date',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function journals()
    {
        return $this->hasMany(Journal::class, 'period_id');
    }
}
