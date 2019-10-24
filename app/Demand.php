<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Demand extends Model
{
    protected $fillable = [
        'description', 'contact', 'address', 'status', 
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

    public function user(){
        return $this->belongsTo(User::class);
    }
}
