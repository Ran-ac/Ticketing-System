<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Clinic extends Model
{
     protected $table = 'clinics';
    protected $fillable = ['name','company','contact_number','email','address', 'created_at'];


    public function users()
    {
        return $this->belongsToMany(User::class);
    }

        public function Tickets()
    {
        return $this->belongsToMany(Ticket::class, 'branch_id');
    }
}
