<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'price', 'image', 'stock', 'is_available',
        'rarity', 'is_featured', // <-- ADDED
    ];

    protected $casts = [
        'price'        => 'decimal:2',
        'is_available' => 'boolean',
        'is_featured'  => 'boolean', // <-- ADDED
    ];

    // <-- ADDED: Rarity options used in admin form + shop filters
    public const RARITIES = [
        'common'    => [
            'label' => 'Common',
            'color' => '#888888',
            'icon'  => 'fa-car',
            'desc'  => 'Standard model',
        ],
        'rare'      => [
            'label' => 'Rare',
            'color' => '#3a8ef6',
            'icon'  => 'fa-star',
            'desc'  => 'Limited production',
        ],
        'epic'      => [
            'label' => 'Epic',
            'color' => '#9b59b6',
            'icon'  => 'fa-gem',
            'desc'  => 'Special edition',
        ],
        'legendary' => [
            'label' => 'Legendary',
            'color' => '#f5c518',
            'icon'  => 'fa-crown',
            'desc'  => 'Collector\'s item',
        ],
        'iconic'    => [
            'label' => 'Iconic',
            'color' => '#dc0000',
            'icon'  => 'fa-horse',
            'desc'  => 'Ferrari legend',
        ],
    ];

    // <-- ADDED: Accessors for rarity label/color/icon
    public function getRarityLabelAttribute(): string
    {
        return self::RARITIES[$this->rarity]['label'] ?? 'Common';
    }

    public function getRarityColorAttribute(): string
    {
        return self::RARITIES[$this->rarity]['color'] ?? '#888888';
    }

    public function getRarityIconAttribute(): string
    {
        return self::RARITIES[$this->rarity]['icon'] ?? 'fa-car';
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->image && file_exists(storage_path('app/public/cars/' . $this->image))) {
            return asset('storage/cars/' . $this->image);
        }
        return asset('images/car-placeholder.jpg');
    }
}