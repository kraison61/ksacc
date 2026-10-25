<?php

namespace App\Models;

use App\Enums\LineRecipientSource;
use Database\Factories\LineRecipientFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'source',
    'line_id',
])]
class LineRecipient extends Model
{
    /** @use HasFactory<LineRecipientFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'source' => LineRecipientSource::class,
        ];
    }
}
