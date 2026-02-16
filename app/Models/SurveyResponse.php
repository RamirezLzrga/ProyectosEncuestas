<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SurveyResponse extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'survey_responses';

    protected $fillable = [
        'survey_id',
        'answers', // Array asociativo: question_text => answer_value
        'ip_address',
        'user_agent'
    ];

    protected $casts = [
        'answers' => 'array',
        'created_at' => 'datetime',
    ];

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }
}
