<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nama',
        'sales_agent_id',
        'no_tlp',
        'domisili',
        'alamat_lengkap',
        'is_blacklisted',
        'alasan_blacklist',
        'company_id',
    ];

    public function salesAgent()
    {
        return $this->belongsTo(SalesAgent::class);
    }

    public function domisiliRef()
    {
        return $this->belongsTo(Domisili::class, 'domisili');
    }

    public function company()
{
    return $this->belongsTo(Company::class, 'company_id');
}

}
