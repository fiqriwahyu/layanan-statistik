<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $fillable = ['media_type'];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
