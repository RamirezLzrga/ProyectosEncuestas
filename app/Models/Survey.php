<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Survey extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'surveys';

    protected $fillable = [
        'title',
        'description',
        'year',
        'start_date',
        'end_date',
        'is_active',
        'settings', // Array con configs: anonymous, collect_emails, etc.
        'questions', // Array embebido de preguntas
        'user_id'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
        'settings' => 'array',
        'questions' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function responses()
    {
        return $this->hasMany(SurveyResponse::class);
    }
}
