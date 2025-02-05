<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'password',
        'name',
        'email',
        'phone',
    ];

    protected $hidden = [
        'password',
    ];

    public function books()
    {
        return $this->hasMany(Book::class, 'user_id');
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
