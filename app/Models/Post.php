<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Kategori;

class Post extends Model
{
    use HasFactory;
    protected $fillable = [
        'judul',
        'gambar',
        'kategori_id',
        'isi',
        'user_id',
        'status'
    ];
    //relasi 1 ke 1 
    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

}
