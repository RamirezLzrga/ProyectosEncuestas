<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class ActivityLog extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'activity_logs';

    protected $fillable = [
        'user_id',
        'user_email', // Guardamos email por si el usuario se borra
        'action', // 'login', 'create', 'update', 'delete', 'toggle'
        'description',
        'type', // 'auth', 'survey', 'user'
        'ip_address',
        'details' // json/array con info extra
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'details' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
