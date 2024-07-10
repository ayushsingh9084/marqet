<?php

namespace App\Models\MasterModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//it is for soft delete
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{

    use HasFactory;
    use SoftDeletes;
    protected $table = "role";

    protected $fillable = [
        'role_name', 'slug', 'abilities', 'status'
    ];
}
