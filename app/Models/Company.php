<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nama',
        'domisili_id',
        'telepon',
        'email',
        'alamat',
        'blacklist',
        'alasan_blacklist',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function domisili()
    {
        return $this->belongsTo(Domisili::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // app/Models/Customer.php

public function company()
{
    return $this->belongsTo(Company::class);
}

}
