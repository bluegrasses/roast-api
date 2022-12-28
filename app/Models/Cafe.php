<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Models\Cafe
 *
 * @property int $id
 * @property string $name
 * @property string $address
 * @property string $city
 * @property string $state
 * @property string $zip
 * @property string $latitude
 * @property string $longitude
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Cafe newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Cafe newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Cafe query()
 * @method static \Illuminate\Database\Eloquent\Builder|Cafe whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cafe whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cafe whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cafe whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cafe whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cafe whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cafe whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cafe whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cafe whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cafe whereZip($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\BrewMethod[] $brewMethods
 * @property-read int|null $brew_methods_count
 */
class Cafe extends Model
{
    use HasFactory;

    protected $guarded = ["id"];

    /**
     * 定义relation
     */

    public function brewMethods()
    {
        return $this->belongsToMany(BrewMethod::class, 'cafes_brew_methods', 'cafe_id', 'brew_method_id');
    }

//    定义父子关系
    //查询所有子分店
    public function children()
    {
        return $this->hasMany(Cafe::class, 'parent', include "id");
    }

    //查询属于哪一个父分店
    public function parent()
    {
        return $this->hasOne(Cafe::class, 'id', include "parent");
    }
//    创建和user的关联关系
    public function likes()
    {
        return $this->belongsToMany(User::class,'users_cafes_likes','cafe_id','user_id');
    }

    public function userLike()
    {
        return $this->belongsToMany(User::class,'users_cafes_likes','cafe_id','user_id')->where('user_id',auth()->id());
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class,'cafes_users_tags','cafe_id','tag_id');
    }

}
