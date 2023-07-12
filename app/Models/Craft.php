<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Craft extends Model
{
    use HasFactory;

    public function ingredients(): HasMany
    {
        return $this->hasMany(Ingredient::class);
    }

    public function format()
    {
        $recipe = Ingredient::where('craft_id', '=', $this->id)->get()->all();
        return [
            'craft_id' => $this->id,
            'type' => $this->type,
            'category' => $this->category,
            'recipe' => $recipe,
        ];
    }
}
