<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'category',
        'date_of_incident',
        'location_type',
        'latitude',
        'longitude',
        'address',
        'city',
        'state',
        'status',
        'admin_remarks',
        'photos',
    ];

    protected $casts = [
        'photos' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
