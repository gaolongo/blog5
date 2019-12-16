<?php

namespace App\Http\Middleware;

use Closure;
use App\Model\User;

class MyToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
       $userData = $this->checkToken($request);
       $mid_params = ['userData'=>$userData];
       $request->attributes->add($mid_params);  //添加参数
       return $next($request);
    }

    public function checkToken($request)
    {
        $token = $request->input("token"); //接受token令牌
        // $token=$_COOKIE['token'];
        // dd($token);
        // $token="ab8217f0ccaeb1415ef6d6dc1fa48e69";
        // $token = isset($_SERVER['HTTP_TOKEN']) ? $_SERVER['HTTP_TOKEN'] : "";
        // var_dump($token);die;
        if (empty($token)) {
            echo json_encode(['code'=>201,'msg'=>"请先登录"]);die;
        }
        //检测token是否正确
        $userData = User::where(['token'=>$token])->first();
        // dd($userData);
        if (!$userData) {
            echo json_encode(['code'=>202,'msg'=>"token错误"]);die;
        }
        //判断有效期
        if(time()>$userData['expire_time']){
            echo json_encode(['code'=>203,'msg'=>"请从新登陆"]);die;
        }
        //更新token有效期(业物)
        $userData->expire_time=time()+7200;
        $userData->save();
        return $userData;
    }
}