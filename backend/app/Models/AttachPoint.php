<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttachPoint extends Model
{
    // use HasFactory;
    use SoftDeletes;
    /**
     * モデルに関連付けるテーブル
     *
     * @var string
     */
    protected $table = 'attach_points';
}
