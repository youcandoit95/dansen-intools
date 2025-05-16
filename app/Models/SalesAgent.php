<?php

// app/Models/SalesAgent.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesAgent extends Model
{
    use SoftDeletes;

    protected $fillable = ['nama', 'telepon', 'email', 'domisili'];

    public function domisiliRef()
    {
        return $this->belongsTo(Domisili::class, 'domisili');
    }
}
