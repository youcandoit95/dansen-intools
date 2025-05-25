<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    protected $fillable = [
        'cabang_id',
        'username',
        'email',
        'no_wa',
        'password',
        'superadmin',
        'manager',
        'supervisor',
        'staff',
        'status'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'superadmin' => 'boolean',
        'manager' => 'boolean',
        'supervisor' => 'boolean',
        'staff' => 'boolean',
        'status' => 'boolean',
    ];

    public function cabang()
    {
        return $this->belongsTo(Cabang::class);
    }
}
