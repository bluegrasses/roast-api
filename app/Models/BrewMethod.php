<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\BrewMethod
 *
 * @property int $id
 * @property string $method
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Cafe[] $cafes
 * @property-read int|null $cafes_count
 * @method static \Illuminate\Database\Eloquent\Builder|BrewMethod newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BrewMethod newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BrewMethod query()
 * @method static \Illuminate\Database\Eloquent\Builder|BrewMethod whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BrewMethod whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BrewMethod whereMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BrewMethod whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class BrewMethod extends Model
{
    use HasFactory;

    public function cafes()
    {
        return $this->belongsToMany(Cafe::class,'cafes_brew_methods','brew_method_id','cafe_id');
    }
}
