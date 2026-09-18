<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_lengkap',
        'no_hp',
        'jenis_kelamin',
        'email',
        'alamat',
        'usia',
        'nama_instansi',
        'nip_nim',
        'work_id',
        'education_id',
    ];

    public function work()
    {
        return $this->belongsTo(Work::class, 'work_id');
    }

    public function education()
    {
        return $this->belongsTo(Education::class, 'education_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
