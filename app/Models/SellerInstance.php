<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//it is for soft delete
use Illuminate\Database\Eloquent\SoftDeletes;

class SellerInstance extends Model
{

    use HasFactory;
    use SoftDeletes;
    protected $table = "seller_instance";

    protected $fillable = [
        'id', 'role', 'abilities', 'instance_name', 'seller_id', 'phone', 'address', 'gst', 'logo', 'business_proof_certificate', 'type', 'domain', 'web', 'app', 'ip_address', 'play_store_link', 'app_store_link', 'template_id', 'created_at', 'updated_at', 'deleted_at', 'status'
    ];
}
