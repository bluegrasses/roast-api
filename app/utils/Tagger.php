<?php

namespace App\utils;

use App\Models\Tag;

class Tagger
{
    public static function tagCafe($cafe, $tags,$userId)
    {
        foreach ($tags as $tag) {
            $name=trim($tag);
            $tag = Tag::firstOrCreate(['name' => $name]);
            $tag->name=$name;
            $tag->save();
            //通过id传递额外的附加数据到中间表
            $cafe->tags()->syncWithoutDetaching([$tag->id => ['user_id' => $userId]]);
        }
    }
}
