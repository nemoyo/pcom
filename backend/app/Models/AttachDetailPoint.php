<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttachDetailPoint extends Model
{
    use SoftDeletes;
    // use HasFactory;
    protected $table = 'attach_detail_points';

}
