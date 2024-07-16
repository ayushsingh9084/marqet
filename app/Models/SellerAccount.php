<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
//it is for soft delete
use Illuminate\Database\Eloquent\SoftDeletes;

class SellerAccount extends Model
{

    use HasFactory, HasApiTokens, SoftDeletes;

    protected $table = "seller_accounts";

    protected $fillable = [
        'id', 'role', 'first_name', 'last_name', 'business_name', 'gst','email', 'phone', 'address', 'last_phone_otp', 'last_email_otp', 'email_verified_at', 'mobile_verified_at', 'added_by', 'created_at', 'updated_at', 'deleted_at', 'status'
    ];
}
