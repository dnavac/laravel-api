<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    //
    protected $table = 'student';

    //Para decir que campos pueden ser alterados
    protected $fillable = ['name','email','phone','language'];

}
