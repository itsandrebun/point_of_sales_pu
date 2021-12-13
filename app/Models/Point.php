<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'point';
    protected $primaryKey = 'point_id';
    protected $guarded = [];

    // const CREATED_AT = 'get_point_at';
    // const UPDATED_AT = '';
}
