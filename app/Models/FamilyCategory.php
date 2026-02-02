<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FamilyCategory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'type',
        'is_active',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function transactions()
    {
        return $this->hasMany(FamilyTransaction::class, 'category_id');
    }
}
