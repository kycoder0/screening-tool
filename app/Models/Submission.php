<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Submission extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'answers', 'form_id', 'outcome_id', 'user_ip'];

    public function outcome(): HasOne
    {
        return $this->hasOne(Outcome::class);
    }
}
