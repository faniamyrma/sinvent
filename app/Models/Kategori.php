<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model //class kategori merupakan turunan dari model
{
    use HasFactory;
    protected $table = 'kategori';  //$table merupakan propertied

    protected $fillable = ['kategori', 'jenis'];

    public function barang()
    {
        return $this->hasMany(Barang::class);
    }
}
