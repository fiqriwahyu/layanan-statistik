<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    use HasFactory;

    protected $fillable = ['categorie_type'];

    public function subCategories()
    {
        return $this->hasMany(SubCategorie::class);
    }
}
