<?php

namespace App\Models;

use Database\Factories\PricingPlanFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'slug',
    'type',
    'title',
    'subtitle',
    'price_prefix',
    'price_amount',
    'price_suffix',
    'features',
    'cta_label',
    'is_featured',
    'sort_order',
    'is_published',
])]
class PricingPlan extends Model
{
    public const TYPE_PACKAGE = 'package';

    public const TYPE_A_LA_CARTE = 'a_la_carte';

    /** @use HasFactory<PricingPlanFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'features' => 'array',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    #[Scope]
    protected function published(Builder $query): void
    {
        $query->where('is_published', true);
    }

    #[Scope]
    protected function packages(Builder $query): void
    {
        $query->where('type', self::TYPE_PACKAGE);
    }

    #[Scope]
    protected function aLaCarte(Builder $query): void
    {
        $query->where('type', self::TYPE_A_LA_CARTE);
    }

    #[Scope]
    protected function ordered(Builder $query): void
    {
        $query->orderBy('sort_order')->orderBy('id');
    }
}
