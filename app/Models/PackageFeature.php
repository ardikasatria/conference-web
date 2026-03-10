<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageFeature extends Model
{
    use HasFactory;

    protected $fillable = [
        'package_id',
        'feature_name',
        'description',
        'is_included',
        'order',
    ];

    protected $casts = [
        'is_included' => 'boolean',
    ];

    /**
     * Get the package this feature belongs to
     */
    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
