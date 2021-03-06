<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Model\Admin;
use App\Model\Category;
use App\model\Type;
use App\model\Attribute;
use App\model\Goods;
use App\model\GoodsAttr;
use App\model\Product;
use Illuminate\Support\Facades\Cache;
use App\Model\Stat;
use App\Model\Inventory;
use App\Model\Summary;
use App\model\Advert;
use DB;
class IndexController extends Controller
{
	//后台首页
    public function index()
    {
    	return view('admin.index');
    }

    //天气添加页面
    public function weather()
    {
        return view('admin.weather');
    }
    //天气添加执行
    public function weather_do()
    {
        if(request()->ajax()){
            //读缓存
            $city=request('city');
            //北京  weatherData_北京
            //天津  weatherData_天津
            $cache_name='weatherData_'.$city;
            $data=Cache::get($cache_name);
            if(empty($data)){
                //调用天气接口
                $url = 'http://api.k780.com/?app=weather.future&weaid='.$city.'&&appkey=43595&sign=f803d6a557060c7118587c6a22d9760a';
                // dd($url);
                //发请求
                $data=file_get_contents($url);
                $time24=strtotime(date("Y-m-d"))+86400;
                $second=$time24-time();
                Cache::put($cache_name,$data,$second);
            }
            //把调接口得到的json格式天气数据返回
            echo $data;die;
        }
        return view('admin.weather');
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
        $cate_name=request('cate_name');
        $onlyRes=Category::where('cate_name',$cate_name)->first();
        if ($onlyRes) {
            echo "<script>alert('该分类名已占用');location.href='/admin/cate_add';</script>";die;
        }
        $data=Category::insert([
            'cate_name'=>$res['cate_name'],
            'cate_pid'=>$res['cate_pid']
        ]);
        // dd($data);;
        if ($data){
            echo "<script>alert('添加成功');location.href='/admin/cate_list';</script>";
        }else{
            echo "<script>alert('添加失败');location.href='/admin/cate_add';</script>";
        }
    }
    //分类唯一性
    public function nameOnly(Request $request)
    {
      $info=$request->all();
      $cate_name=request('cate_name');
      $onlyRes=Category::where('cate_name',$cate_name)->first();
      //dd($onlyRes);
      if($onlyRes){
        echo json_encode(['font'=>1]);
      }else{
        echo json_encode(['font'=>2]);
      }
    }

    //分类唯一性
    public function cate_listSave()
    {
        $cate_name=request()->cate_name;
        $data=Category::where('cate_name',$cate_name)->first();
        if($data){
            return $data;
        }
    }
    //分类列表
    public function cate_list()
    { 
       $res = Category::get()->toArray();
        //  dd($res);
        $cate = Category::createTree($res);
        foreach($res as $k=>$v){
            //$aa = Goods::where('cate_id',$v['cate_id'])->count();
            // dd($aa);
           $cate[$k]['cate_count'] = Goods::where('cate_id',$v['cate_id'])->count();
        }
        return view('admin.cate_list',compact('cate'));
    }
    //分类删除
    public function cate_del($cate_id)
    {
    	$data=Category::where(['cate_id'=>$cate_id])->delete();
    	// dd($data);
    	if ($data){
            echo "<script>alert('删除成功');location.href='/admin/cate_list';</script>";
        }else{
            echo "<script>alert('删除失败');location.href='/admin/cate_list';</script>";
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
    //类型删除
    public function type_del($type_id)
    {
        $data=Type::where(['type_id'=>$type_id])->delete();
        // dd($data);
        if($data){
            return redirect('admin/type_list');
        }
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
         //生成商品货单号
        $goods_nb = '6789'.rand(00000,99999);
        // dd($good_img);
        $res=Goods::create([
            'goods_name'=>$data['goods_name'],
            'cate_id'=>$data['cate_id'],
            'goods_price'=>$data['goods_price'],
            'goods_nb'=>$goods_nb,
            'goods_img'=>$good_img,
            'content'=>$data['content']
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


    //登陆页面
    public function login()
    {
        return view('admin/login');
    }
    //登陆执行
    public function login_denglu(Request $request)
    {
        $data = $request->input();
    	//dd($data);
        $admin = DB::table('admin')->where('admin_name','=',$data['u_name'])->first();
        if (!$admin){
            return json_encode(['code'=>'201','msg'=>'用户名输入错误, 请重新输入']);
        }
    	//dd($admin);
        $admin = DB::table('admin')->where([['password','=',$data['u_password']],['admin_name','=',$data['u_name']]])->first();
        if(!$admin){
            return json_encode(['code'=>'202','msg'=>'密码输入错误, 请重新输入']);
        }
		//dd($admin);
        $request->session()->flush();
        $request->session()->put('admin', $admin);
        $admin_roel = DB::table('admin_role')->where('admin_id',$admin->admin_id)->first();
        if(!$admin_roel){
            dd('只有查看权限');
        }
        $quanxian = DB::table('role_right')->join('right','role_right.right_id','=','right.right_id')->where('role_right.role_id',$admin_roel->role_id)->get()->toArray();
    	//dd($quanxian);
        if($quanxian){
            $sess_id = $admin->admin_name.$admin->admin_id;
            $request->session()->put("$sess_id", $quanxian);
			//dd($request->session()->all());
            return json_encode(['code'=>200,'msg'=>'登录成功']);
        }
    }

    //管理员展示
     public function admin()
    {
        //dd(1);
        $admin = DB::table('admin')->get()->toArray();
        //($admin);
        return view('admin/admin',['admin'=>$admin]);
    }

    //管理员添加
    public function admin_insert(Request $request)
    {
            $right_id = $request->input('right_id');
            return view('admin/admin_insert',['right_id'=>$right_id]);
    }

    //管理员添加执行
    public function admin_submit(Request $request)
    {
        $data = $request->input();
		// dd($data);
        $admin = DB::table('admin')->insert([
            'admin_name' => $data['admin_name'],
            'password' => $data['password'],
            'mobile' => $data['mobile'],
            'email' => $data['email'],
            'create_time' => time()
        ]);
        if ($admin){
            echo "<script>alert('管理员添加成功');location='/admin/admin'</script>";die;
        }else{
            echo "<script>alert('添加失败,请重新添加');location='/admin/admin'</script>";die;
        }
    }

    //管理员角色设置
    public function admin_set(Request $request , $admin_id)
    {
        $right_id = $request->input('right_id');
        $role = DB::table('role')->get()->toArray();
        return view('admin/admin_set',['right_id'=>$right_id,'admin_id'=>$admin_id,'role'=>$role]);
    }
    //管理员角色设置执行
    public function admin_role_insert(Request $request )
    {
        $quanxian = $request->input('quanxian');
        $admin_id = $request->input('admin_id');
        foreach($quanxian as $v){
		//echo $v;
            $admin_role = DB::table('admin_role')->insert([
               'admin_id' => $admin_id,
                'role_id' => $v
            ]);
        }
        echo "<script>alert('权限设置成功');location='/admin/admin'</script>";die;
    }

    //管理员删除
    public function admin_delete(Request $request , $admin_id)
    {
        if($admin_id == 1){
            echo "<script>alert('超级管理员无权删除');location='/admin/admin'</script>";die;
        }
        $admin_role_id = DB::table('admin_role')->where('admin_id',$admin_id)->get()->toArray();
        if (!$admin_role_id){
            DB::table('admin')->where('admin_id','=',$admin_id)->delete();
            echo "<script>alert('管理员删除成功');location='/admin/admin'</script>";die;
        }
            DB::table('admin')->where('admin_id','=',$admin_id)->delete();
            DB::table('admin_role')->where('admin_id','=',$admin_id)->delete();
            echo "<script>alert('管理员 , 删除成功');;window.history.go(-1)</script>";die;
    }


     /**
     * 管理员密码修改
     */
    public function admin_update(Request $request)
    {
        $admin_id = $request->input('admin_id');
        $admin = $request->session()->get('admin');
        if($admin_id == $admin->admin_id){
            return view('admin/admin_update',['admin_id'=>$admin_id]);
        }else{
            echo "<script>alert('您无权操作,请联系管理员本人进行操作');window.history.go(-1)</script>";die;
        }
    }

    //管理员密码修改执行
    public function admin_update_add(Request $request)
    {
        $data = $request->input();
		//dd($data['admin_id']);
        $admin = DB::table('admin')->where('admin_id',$data['admin_id'])->first();
		//dd($admin);
		//$admin = $request->session()->get('admin');
        if($data['password'] == $admin->password){
            echo "<script>alert('密码重复 , 请输入与原密码不相同的密码');window.history.go(-1)</script>";die;
        }
        $admin_update = DB::table('admin')->where('admin_id',$data['admin_id'])->update(['password'=>$data['password']]);
        if($admin_update){
            $request->session()->flush();
            echo "<script>alert('密码修改成功 , 请去重新登录');location='/admin/login'</script>";die;
        }else{
            echo "<script>alert('密码修改失败 , 请重试');location='/admin/admin'</script>";die;
        }
    }


    /**
     * @param
     * @param 无限极分类
     */
    public function getCateInfo($data,$pid = 0)
    {
            static $info=[];
            foreach($data as $k=>$v){
                if($v->p_id==$pid){
                    $info[]=$v;
                    $this->getCateInfo($data,$v->right_id);
                }
            }
            return $info;
    }
    

    //角色展示
    public function role()
    {
        $data = DB::table('role')->get()->toArray();
		//dd($data);
        return view('admin/role',['role'=>$data]);
    }

    //角色添加页面
    public function role_select()
    {
        return view('admin/role_select');
    }

    //角色添加执行
    public function role_insert(Request $request)
    {
        $right_id = $request->input('right_id');
		//dd($right_id);
        $description = $request->input('description');
        $role_name = $request->input('role_name');
		//dd($role_name);
        if(empty($description) || empty($role_name)){
            return json_encode(['code'=>201,'msg'=>'内容不能为空']);
        }
        $role = DB::table('role')->insertGetId([
            'role_name'=>$role_name,
            'description'=>$description,
            'create_time'=>time()
        ]);
        if($role){
            return json_encode(['code'=>200,'msg'=>'角色创建成功 , 跳转授权','role_id'=>$role]);
        }else{
            return json_encode(['code'=>202,'msg'=>'未知错误请重试']);
        }
    }
    /**
     * 角色删除
     */
    public function role_delete(Request $request , $role_id)
    {
        $admin_role = DB::table('admin_role')->where('admin_id',$role_id)->count();
		//dd($admin_role);
        if($admin_role){
            echo "<script>alert('不可删除 , 请先移除相对的管理员 , 在进行此操作');location='/admin/role'</script>";die;
        }else{
            $role = DB::table('role')->where('role_id',$role_id)->delete();
            if($role){
                echo "<script>alert('角色删除成功');location='/admin/role'</script>";die;
            }else{
                echo "<script>alert('删除失败 , 请重试');location='/admin/role'</script>";die;
            }
        }
    }
    /**
     * 角色授权
     */
    public function right(Request $request)
    {
        $role_id = $request->input('role_id');
		//dd($role_id);
		//$right = DB::table('right')->where('role_id',$role_id)->get()->toArray();
		//dd($right);
        $data = DB::table('right')->get()->toArray();
		//dd($data);
        $date = $this->getCateInfo($data);
		//dd($date);
        $data = json_decode(json_encode($data),1);
		//dd($data);
        $date = [];
        foreach($data as $k=>$v){
            if($v['p_id'] == 1){
                    $date[$k] = $v;
                    $date[$k]['name'] = [];
            }
        }
        foreach($data as $k=>$v){
            foreach($date as $ki=>$vi){
                if($vi['right_id'] == $v['p_id']){
                    $date[$ki]['name'][] = $v;
					//dd($v);
                }
            }
        }
		//print_r($date);
        return view('admin/role_right',['role_right'=>$date,'role_id'=>$role_id]);
    }
    /**
     * 角色权限关联
     */
    public function role_right(Request $request)
    {
        $data = $request->input('role_id');
		//dd($data);
        $datas = $request->post();
        $role_id = $datas['r_id'];
        foreach($datas['r_id'] as $k=>$v){
            $date = DB::table('role_right')->insert([
                'role_id' => $data,
                'right_id' => $v
            ]);
        }
        if( $date ){
            echo "<script>alert('添加成功');location='/admin/role'</script>";die;
        }else{
            return "<script>alert('添加失败,请重试');window.history.go(-1)</script>";die;
        }
    }

    //注册汇总
    public function stat_index(Request $request)
    {
        $query=$request->all();
        $where=[];
        //搜索
        if($query['admin_name']??''){
            $where[]=['admin_name','like',"%$query[admin_name]%"];
        }
        $pageSize=config('app.pageSize');//每页显示条数
        $data=Admin::where($where)->paginate($pageSize);
        //dd($data);
        return view('admin.stat_index',['data'=>$data,'query'=>$query]);
    }
    //商品库存汇总
    public function inventory_index(Request $request)
    {
        $query=$request->all();
        $where=[];
        //搜索
        if($query['goods_name']??''){
            $where[]=['goods_name','like',"%$query[goods_name]%"];
        }
        $pageSize=config('app.pageSize');//每页显示条数
        $data = Product::join('goods','goods.goods_id','=','product.goods_id')->where($where)->paginate($pageSize);
        //dd($data);
        return view('admin.inventory_index',['data'=>$data,'query'=>$query]);
    }
    //订单汇总
    public function summary_index(Request $request)
    {
        $query=$request->all();
        $where=[];
        //搜索
        if($query['or_num']??''){
            $where[]=['or_num','like',"%$query[or_num]%"];
        }
        $pageSize=config('app.pageSize');//每页显示条数
        $data=Summary::where($where)->paginate($pageSize);
        //dd($data);
        return view('admin.summary_index',['data'=>$data,'query'=>$query]);
    }

 	  //广告模板
 	  
 	  //广告添加
   public function advert()
 	{
 		return view('admin/advert');
 	}

  public function advert_do(Request $request)
 	{
 		 $data=$request->all();
         $path = "";
        if($request->hasFile('ad_img')){
            $path = Storage::putFile('avatars', $request->file('ad_img'));
        };
        // dd($path);
        $ad_img="http://www.blog5.com/".$path;
        // dd($ad_img);
         $time = time();
         // dd($time);
 		 $res=DB::table('advert')->insert([
 		 	'ad_name'=>$data['ad_name'],
 		 	'ad_title'=>$data['ad_title'],
 		 	'ad_img'=>$ad_img,
 		 	'time'=> $time
 		 ]);
 		 // dd($res);
       	if($res){
            echo "<script>alert('添加成功');location.href='/admin/advertlist';</script>";
		}else{                          
            echo "<script>alert('添加失败');location.href='/admin/advert';</script>";
		}
 	}
 	//广告列表
     public function advertlist(Request $request)
    {
    	$query=$request->all();
        $where=[];
        if($query['ad_name']??''){
            $where[] = ['ad_name','like',"%$query[ad_name]%"];
        }
        $data=Advert::where($where)->paginate(3);
        // dd($data);
        return view('admin/advertlist',compact('data','query'));

    }
        //广告删除
    public function ad_del($ad_id)
    {
    	$data=DB::table('advert')->where('ad_id','=',$ad_id)->delete();
    	// dd($data);
    	if($data) {
            echo "<script>alert('删除成功');location.href='/admin/advertlist';</script>";
		}else{                          
            echo "<script>alert('删除失败');location.href='/admin/advertlist';</script>";
		}
    }
  //  
}