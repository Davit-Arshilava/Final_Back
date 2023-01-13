<?php

namespace App\Models;

use App\Scopes\FilterByAuthUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description'];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function answers()
    {
        return $this->hasManyThrough(Answer::class, Question::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    protected static function booted()
    {
        static::addGlobalScope(new FilterByAuthUser);
    }
}
