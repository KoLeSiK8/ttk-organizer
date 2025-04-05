<?php // app/Models/User.php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = [
        'login', 
        'full_name', 
        'password', 
        'role' // 'user' или 'admin'
    ];

    protected $hidden = ['password'];
}
?>