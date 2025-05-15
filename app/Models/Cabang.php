<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cabang extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cabang'; // Nama tabel jika tidak plural

    protected $fillable = [
        'nama_cabang',
        'alamat',
        'telepon',
        'nama_pic',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
}
