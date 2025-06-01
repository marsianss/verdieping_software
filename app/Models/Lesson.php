<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'duration',
        'price',
        'max_participants',
        'difficulty_level',
        'image_url',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function getImageUrlAttribute($value)
    {
        if ($value) {
            return $value;
        }

        // Default placeholder images based on difficulty level
        $images = [
            'beginner' => '/images/lessons/beginner.jpg',
            'intermediate' => '/images/lessons/intermediate.jpg',
            'advanced' => '/images/lessons/advanced.jpg',
            'any' => '/images/lessons/intermediate.jpg', // Default to intermediate image for 'any' level
        ];

        return $images[$this->difficulty_level] ?? $images['beginner'];
    }
}
