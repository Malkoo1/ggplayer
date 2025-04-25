<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppRecord extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'app_id',
        'os',
        'status',
        'assign_url',
        'reseller_id',
        'expiry_date',
        'licence_pkg',
        'licence_expire',
        'note',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'expiry_date' => 'datetime',
        'licence_expire' => 'datetime',
    ];

    /**
     * Get the reseller that created the app record.
     */
    public function reseller()
    {
        return $this->belongsTo(User::class, 'reseller_id');
    }
}
