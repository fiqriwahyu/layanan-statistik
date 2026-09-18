<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purpose extends Model
{
    use HasFactory;

    protected $fillable = ['purpose_type'];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
