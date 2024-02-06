<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Apartment extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'slug',
        'rooms',
        'beds',
        'bathrooms',
        'square_meters',
        'address',
        'country',
        'latitude',
        'longitude',
        'cover_image',
        'user_id',
    ];
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function services()
    {
        return $this->belongsToMany(Service::class);
    }
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function images()
    {
        return $this->hasMany(Image::class);
    }
    public function visits()
    {
        return $this->hasMany(Visit::class);
    }
}
