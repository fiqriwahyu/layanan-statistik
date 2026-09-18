<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $table = 'feedback';

    protected $fillable = [
        'transaction_id',
        'rating_pelayanan',
        'rating_fasilitas',
        'rating_kualitas',
        'saran_kritik',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
