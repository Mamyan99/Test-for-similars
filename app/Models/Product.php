<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Product
 *
 * @property int $id
 * @property string $name
 * @property int $popularity
 */

class Product extends Model
{
    use HasFactory;

    // This is only for russian and english
    const IGNORE_WORDS = [
        "и", "или", "не", "но", "а", "в", "на", "с", "по", "от", "до", "за", "у", "о", "к", "из",
        'in', 'on', 'at', 'for', 'with', 'and', 'but', 'or', 'to', 'from', 'into', 'onto', 'over', 'under', 'through',
        'a', 'an', 'the', 'this', 'that', 'these', 'those'
    ];

    public function getName(): string
    {
        return $this->name;
    }

    public function getId(): int
    {
        return $this->id;
    }
}
