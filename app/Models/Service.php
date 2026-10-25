<?php

namespace App\Models;

use Database\Factories\ServiceFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'slug',
    'title',
    'description',
    'icon',
    'sort_order',
    'is_published',
])]
class Service extends Model
{
    /**
     * Icons an editor can assign to a service.
     *
     * @var array<string, string>
     */
    public const ICONS = [
        'book' => 'สมุดบัญชี',
        'building' => 'อาคาร',
        'calendar' => 'ปฏิทิน',
        'chart' => 'กราฟ',
        'file' => 'เอกสาร',
        'document' => 'แฟ้มเอกสาร',
        'message' => 'ข้อความ',
        'phone' => 'โทรศัพท์',
        'users' => 'ผู้คน',
        'shield' => 'ความปลอดภัย',
        'clock' => 'นาฬิกา',
        'check' => 'เครื่องหมายถูก',
        'info' => 'ข้อมูล',
        'upload' => 'อัปโหลด',
    ];

    /** @use HasFactory<ServiceFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
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
    protected function ordered(Builder $query): void
    {
        $query->orderBy('sort_order')->orderBy('id');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
