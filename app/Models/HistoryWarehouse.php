<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HistoryWarehouse extends Model
{
    use HasFactory;
    protected $table = 'history_warehouse';
    protected $fillable = ['name', 'quantity'];
}
