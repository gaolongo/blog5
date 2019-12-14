<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Model\Category;
use App\model\Type;
use App\model\Attribute;
use App\model\Goods;
use App\model\GoodsAttr;
use App\model\Product;
class IndexController extends Controller
{
	//后台首页
    public function index()
    {
    	return view('admin.index');
    }
    //分类视图
    public function cate_add()
    {
    	$res=Category::get()->toArray();
        $cate=Category::createTree($res);
        return view('admin.cate_add',['cate'=>$cate,'res'=>$res]);
    }
    //分类添加
    public function cate_add_do(Request $request)
    {

        $res=request()->all();
        $data=Category::insert([
            'cate_name'=>$res['cate_name'],
            'cate_pid'=>$res['cate_pid']
        ]);
        // dd($data);;
        if($data){
            return redirect('admin/cate_list');
        }
    }
    //分类列表
    public function cate_list()
    {
        $data=Category::get();
        return view('admin.cate_list',['data'=>$data]);
    }
    //分类删除
    public function cate_del($cate_id)
    {
    	$data=Category::where(['cate_id'=>$cate_id])->delete();
    	// dd($data);
    	if($data){
			return redirect('admin/cate_list');
    	}
    }
    //类型视图
    public function type_add()
    {
        return view('admin.type_add');
    }
    //类型添加
    public function type_add_do()
    {

        $res=request()->all();
        // dd($res);
        $data=Type::insert([
            'type_name'=>$res['type_name']
        ]);
        // dd($data);;
        if($data){
            return redirect('admin/type_list');
        }
    }
    //类型列表
    public function type_list()
    {
        $data=Type::get();
        foreach($data as $key=>$val){
            $info=Attribute::where('type_id',$val['type_id'])->count();
            $data[$key]['attr_count']=$info;
        }
        return view('admin.type_list',['data'=>$data]);
    }
    //属性视图
    public function attr_add()
    {
        $info=Type::get();
        return view('admin.attr_add',['info'=>$info]);
    }
    //属性添加
    public function attr_add_do()
    {
        $res=request()->all();
        // dd($res);
        $data=Attribute::insert([
            'attr_name'=>$res['attr_name'],
            'type_id'=>$res['type_id'],
            'attr_hua'=>$res['attr_hua']
        ]);
        // dd($data);
        if($data){
            return redirect('admin/attr_list');
        }
    }
    //属性类表
    public function attr_list()
    {
        $sear=request()->all();
        $data = Attribute::join('type','type.type_id','=','attribute.type_id')->get();
        // 循环type_name值
        foreach($data as $key => $val){
            $info = Type::where('type_id',$val['type_id'])->value('type_name');
            $data[$key]['type_name'] = $info;
        }
        return view('admin.attr_list',['data'=>$data,'sear'=>$sear]);
    }
    //属性批量删除
    public function attr_del(Request $request)
    {
    //    echo 111;exit;
        $attr_id = $request->input('attr_id');
        // dd($attr_id);
        //利用循环将需要删除的id 一个一个进行执行sql；
        foreach($attr_id as $v){
            $res =Attribute::where('attr_id',"=","$v")->delete();
        }
        // dd($res);
        if ($res){
            $res = [
                'code'=>200,
                'msg'=>'删除成功'
            ];
            return $res;
        }else{
            $res = [
                'code'=>10001,
                'msg'=>'删除失败'
            ];
            return $res;
        }
    }
    //对应属性列表
    public function attr_attrlist(Request $request)
    {
    	$type_id=$request->all();
        $type_id=$type_id["type_id"];
        // dd($type_id);

        $res= Type::join('attribute','attribute.type_id','=','type.type_id')->where('attribute.type_id',$type_id)->get();
        // dd($res);
        return view('admin.attr_attrlist',compact('res'));
    }
    //商品视图
    public function goods_add()
    {
        $cateData=Category::get();
        $typeData=Type::get();
        return view('admin.goods_add',['cateData'=>$cateData,'typeData'=>$typeData]);
    }
    public function getAttr(Request $request)
    {
        $type_id=$request->input('type_id');
        // dd($type_id);
        $attrData=Attribute::where(['type_id'=>$type_id])->get();
        // var_dump($attrData);die;
        return json_encode($attrData);
    }
    //商品添加
    public function goods_add_do(Request $request)
    {
        $data=$request->all();
        // dd($data);
        $path = "";
        if($request->hasFile('goods_img')){
            $path = Storage::putFile('avatars', $request->file('goods_img'));
        };
        // dd($path);
        $good_img="http://www.blog5.com/".$path;
        // dd($good_img);
        $res=Goods::create([
            'goods_name'=>$data['goods_name'],
            'cate_id'=>$data['cate_id'],
            'goods_price'=>$data['goods_price'],
            'goods_img'=>$good_img,
        ]);
        // dd($res);
        $goods_id=$res['goods_id'];
        // dd($goods_id);
        foreach($data['attr_id_list'] as $key=>$value){
            GoodsAttr::create([
                'goods_id'=>$goods_id,
                'attr_id'=>$value,
                'attr_value'=>$data['attr_value_list'][$key],
                'attr_price'=>$data['attr_price_list'][$key],
            ]);
        }
        return redirect('/admin/product_add/'.$goods_id);
    }
    //商品展示
    public function goods_list(Request $request)
    {
        $query=$request->all();
        $where=[];
        if($query['goods_name']??''){
            $where[] = ['goods_name','like',"%$query[goods_name]%"];
        }
        if($query['goods_price']??''){
            $where['goods_price'] =$query['goods_price'];
        }
        $pagesize=config('app.pageSize');
        // dd($pagesize);
        $data=Goods::join('category','category.cate_id','=','goods.cate_id')->where($where)->paginate($pagesize);
        // dd($data);
        return view('admin.goods_list',['data'=>$data,'query'=>$query]);
    }
    //商品上下架及点及改
    public function isshow(Request $request)
    {
        $is_show = $request->is_show;
        //dd($is_show);
        $goods_id = $request->id;
        //dd($goods_id);
        $res = Goods::where('goods_id', $goods_id)->update(['is_show' => $is_show]);
    }
    //货品视图
    public function product_add($goods_id)
    {
    	//通过goods_id查询商品表
        $goodsData=Goods::where(['goods_id'=>$goods_id])->first();
        // dd($goodsData);
        $attrData=GoodsAttr::join('attribute','goodsattr.attr_id','=','attribute.attr_id')->where(['goods_id'=>$goods_id,'attr_hua'=>2])->get()->toArray();
        // var_dump($attrData);die;
        $spec=[];
        foreach($attrData as $key=>$value){
            $spec[$value['attr_id']][]=$value;
        }
        return view('admin/product_add',['goodsData'=>$goodsData,'spec'=>$spec]);
    }
    //货品添加
    public function product_add_do(Request $request)
    {
        $postData=$request->input();
        // dd($postData);
        //几个属性值为1组数据
        $size=count($postData['value_list']) / count($postData['pro_number']);
        // dd($size);
        //分个数组
        $guan=array_chunk($postData['value_list'],$size);
        // dd($guan);
        foreach($guan as $key=>$value){
            $res=Product::create([
                 'goods_id'=>$postData['goods_id'],
                 'value_list'=>implode(",",$value),
                 'pro_number'=>$postData['pro_number'][$key]
            ]);
        }
        // dd($res);
        if($guan){
        	return redirect('admin/goods_list');
        }
    }

}
