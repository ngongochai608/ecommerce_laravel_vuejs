<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 
        'status',
        'items', 
        'price',
        'price_add', 
        'discount', 
        'total', 
        'note',
        'table_id',
    ];

    public function table()
    {
        return $this->belongsTo(Table::class);
    }
}
