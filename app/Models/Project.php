<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

//    protected $fillable = [
//        'url', 'token', 'name', 'setting', 'owner_id'
//    ];

    protected $guarded =[];

    protected $casts = [
        'setting' => 'json',
    ];

    public function getGroupsAttribute(){

        return $this->setting['groups'];
    }
}
