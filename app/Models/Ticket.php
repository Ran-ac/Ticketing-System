<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $table = 'Tickets';
    protected $fillable = ['title','description','status','priority','user_id', 'assigned_to','branch_id','department','due_date','resolved_at'];

    public function clinic()
    {
        return $this->belongsTo(Clinic::class, 'branch_id');
    }

}
