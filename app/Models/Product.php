<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;


class Product extends Model
{
    //
    use HasFactory, LogsActivity;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'category_id'
    ];
    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->useLogName('product')
            ->dontLogEmptyChanges();
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}