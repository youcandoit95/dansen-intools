<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Domisili extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'domisili';

    protected $fillable = ['nama'];
}
