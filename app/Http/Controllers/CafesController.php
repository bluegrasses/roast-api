<?php

namespace App\Http\Controllers;

use App\Http\Requests\CafeNewRequest;
use App\Http\Resources\CafesResource;
use App\Models\Cafe;
use App\Traits\ApiResponse;
use App\utils\GaodeMaps;
use App\utils\Tagger;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Request;

class CafesController extends Controller
{
    //
    use ApiResponse;

    public function getCafes()
    {
        $collection = Cafe::with('brewMethods')->get();
        return $this->sendSuccess($collection,'cafe list success');
    }

    public function getCafe($id)
    {
        $cafe =  Cafe::where('id', '=', $id)->with('brewMethods')->with('userLike')->first();
        if (is_null($cafe)){
            return $this->sendError('cafe not found');
        }
        return $this->sendSuccess($cafe,'cafe find success');
    }

    /**
     * 添加咖啡店
     * @param CafeNewRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postNewCafe(CafeNewRequest $request)
    {
        // 已添加的咖啡店
        $addedCafes = [];
        // 所有位置信息
        $locations = $request->input('locations');

        // 父节点（可理解为总店）
        $parentCafe = new Cafe();

        // 咖啡店名称
        $parentCafe->name = $request->input('name');
        // 分店位置名称
        $parentCafe->location_name = $locations[0]['name'] ?: '';
        // 分店地址
        $parentCafe->address = $locations[0]['address'];
        // 所在城市
        $parentCafe->city = $locations[0]['city'];
        // 所在省份
        $parentCafe->state = $locations[0]['state'];
        // 邮政编码
        $parentCafe->zip = $locations[0]['zip'];
        $coordinates = GaodeMaps::geocodeAddress($parentCafe->address, $parentCafe->city, $parentCafe->state);
        // 纬度
        $parentCafe->latitude = $coordinates['lat'];
        // 经度
        $parentCafe->longitude = $coordinates['lng'];
        // 咖啡烘焙师
        $parentCafe->roaster = $request->input('roaster') ? 1 : 0;
        // 咖啡店网址
        $parentCafe->website = $request->input('website');
        // 描述信息
        $parentCafe->description = $request->input('description') ?: '';
        // 添加者
        $parentCafe->added_by = $request->user()->id;
        $parentCafe->save();

        // 冲泡方法
        $brewMethods = $locations[0]['methodsAvailable'];
        //标签信息
        $tags=$locations[0]['tags'];
        // 保存与此咖啡店关联的所有冲泡方法（保存关联关系）
        $parentCafe->brewMethods()->sync($brewMethods);

        // 保存与此咖啡店关联的所有标签（保存关联关系）
        Tagger::tagCafe($parentCafe,$tags,$request->user()->id);

        // 将当前咖啡店数据推送到已添加咖啡店数组
        array_push($addedCafes, $parentCafe->toArray());

        // 第一个索引的位置信息已经使用，从第 2 个位置开始
        if (count($locations) > 1) {
            // 从索引值 1 开始，以为第一个位置已经使用了
            for ($i = 1; $i < count($locations); $i++) {
                // 其它分店信息的获取和保存，与总店共用名称、网址、描述、烘焙师等信息，其他逻辑与总店一致
                $cafe = new Cafe();

                $cafe->parent = $parentCafe->id;
                $cafe->name = $request->input('name');
                $cafe->location_name = $locations[$i]['name'] ?: '';
                $cafe->address = $locations[$i]['address'];
                $cafe->city = $locations[$i]['city'];
                $cafe->state = $locations[$i]['state'];
                $cafe->zip = $locations[$i]['zip'];
                $coordinates = GaodeMaps::geocodeAddress($cafe->address, $cafe->city, $cafe->state);
                $cafe->latitude = $coordinates['lat'];
                $cafe->longitude = $coordinates['lng'];
                $cafe->roaster = $request->input('roaster') != '' ? 1 : 0;
                $cafe->website = $request->input('website');
                $cafe->description = $request->input('description') ?: '';
                $cafe->added_by = $request->user()->id;
                $cafe->save();

                $cafe->brewMethods()->sync($locations[$i]['methodsAvailable']);

                Tagger::tagCafe($cafe,$locations[$i]['tags'],$request->user()->id);

                array_push($addedCafes, $cafe->toArray());
            }
        }

        return $this->sendSuccess($addedCafes,'cafe create success');
    }

//    实现喜欢
    public function postLikeCafe($cafeID){
        $cafe=Cafe::where('id','=',$cafeID)->first();
        $cafe->likes()->attach(Auth::user()->id,['created_at'=>Carbon::now(),'updated_at'=>Carbon::now()]);
        return response()->json(['cafe_liked'=>true],201);
    }

//    实现取消喜欢
    public function deleteLikeCafe($cafeID){
        $cafe=Cafe::where('id','=',$cafeID)->first();
        $cafe->likes()->detach(Auth::user()->id);
        return response()->json(null,204);
    }

    /**
     * 用户给咖啡店添加标签
     * @param Request $request
     * @param $cafeID
     */
    public function postAddTags(Request $request,$cafeID){
        $tags=$request->input('tags');
        $cafe = Cafe::find($cafeID);
        Tagger::tagCafe($cafe,$tags,Auth::user()->id);

        $result = Cafe::where('id', '=', $cafeID)
            ->with('brewMethods')
            ->with('tags')
            ->with('userLike')
            ->first();
        return $this->sendSuccess($result,'cafe tags add success');
    }

    /**
     * 用户删除咖啡店标签
     * @param $cafeID
     * @param $tagID
     */
    public function deleteCafeTag($cafeID,$tagID){
        \DB::table('cafes_users_tags')->where('cafe_id',$cafeID)->where('tag_id',$tagID)->where('user_id',Auth::user()->id)->delete();
        return $this->sendSuccess(null,'cafe tag delete success');
    }

}
