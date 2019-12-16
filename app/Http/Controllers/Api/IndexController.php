<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Model\User;
use App\Model\Category;
use DB;
class IndexController extends Controller
{
    //登录
	public function login(Request $request)
	{
		//接收用户名 密码
		$username=$request->input('username');
		$password=$request->input('password');
		//验证用户名和密码是否正确
		$userData = User::where(['username'=>$username,'password'=>$password])->first();
		if (!$userData) {
			//报错
			echo '用户名密码错误';die;
		}else{
			//登陆成功
			//生成一个令牌
			$token=md5($userData['id'].time());  //MD5(用户id+时间戳)
			//存储到数据库中
			$userData->token= $token;
			$userData->expire_time =time()+7200;
			$userData->save();
			return json_encode(['code'=>200,'msg'=>'登陆成功','data'=>$token]);
		}
	}

	//登陆查询用户信息
	public function login_do(Request $request)
	{
		$token = $request->input("token"); //接受token令牌
		if (empty($token)) {
			return json_encode(['code'=>201,'msg'=>"请先登录"]);
		}
		//检测token是否正确
		$userData = User::where(['token'=>$token])->first();
		if (!$userData) {
			return json_encode(['code'=>202,'msg'=>"token错误"]);
		}
		//判断有效期
		if(time()>$userData['expire_time']){
			return json_encode(['code'=>203,'msg'=>"token已过期请从新登陆"]);
		}
		//更新token有效期(业物)
		$userData->expire_time=time()+7200;
		$userData->save();

		//查询用户信息
		echo "admin";die;
	}

	//商品分类
	public function cate()
	{
		$cateData=Category::limit(8)->get();
		return json_encode($cateData);
	}
}
