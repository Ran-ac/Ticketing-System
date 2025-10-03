<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'address',
        'contact_number',
        'role',
        'department',
        'branch',
        'password',
        'created_at',
    ];

    //relation to clinic
    public function clinic()
    {
        return $this->belongsTo(Clinic::class, 'branch_id');  // foreign key column is 'branch'
    }

    //relation to ticket
    public function Ticket()
    {
        return $this->belongsToMany(Ticket::class);
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
