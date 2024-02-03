<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Question extends Model
{
    use HasFactory;
    protected $fillable = ['form_id', 'name', 'text', 'input_type', 'options', 'validation_rules', 'visibility_rule'];

    protected $casts = [
        'validation_rules' => 'array',
        'visibility_rule' => 'array',
        'options' => 'array',
    ];

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }
}
