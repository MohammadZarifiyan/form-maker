<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Query extends Model
{
    use HasFactory;

    protected $fillable = [
        'sql',
        'type',
        'parameters',
        'button_title',
    ];

    protected $casts = [
        'parameters' => 'array',
        'status' => 'boolean',
    ];

    public function connection()
    {
        return $this->belongsTo(Connection::class);
    }

    public function form()
    {
        return $this->belongsToMany(Form::class);
    }
}
