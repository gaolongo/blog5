<?php

namespace App\Http\Middleware\admin;

use Closure;

class Quanxian
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
        $right_id = $request->input('right_id');
        $admin = $request->session()->get('admin');
        $sess_id = $admin->admin_name.$admin->admin_id;
        $quanxian = $request->session()->get("$sess_id");
        $data = [];
        foreach($quanxian as $v ){
            if($v->right_id == $right_id){
                $data[] = $v;
            }
        }
        if($data){

        }else{
            echo "<script>alert('无操作权限');;window.history.go(-1)</script>";die;
        }
        return $next($request);
    }
}
