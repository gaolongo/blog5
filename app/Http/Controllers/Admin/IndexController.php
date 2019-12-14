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
<<<<<<< Updated upstream
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

=======

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
>>>>>>> Stashed changes
}
