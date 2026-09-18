<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'media_id',
        'sub_categorie_id',
        'service_id',
        'purpose_id',
        'data_diminta',
        'status_transaksi',
        'status_penilaian',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function media()
    {
        return $this->belongsTo(Media::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function purpose()
    {
        return $this->belongsTo(Purpose::class);
    }

    public function subCategorie()
    {
        return $this->belongsTo(SubCategorie::class, 'sub_categorie_id');
    }

    public function feedback()
    {
        return $this->hasOne(Feedback::class);
    }
}
