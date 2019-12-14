<?php

namespace App\Http\Middleware\admin;

use Closure;

class Login
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
        if ($request->session()->has('admin')) {

        }else{
            echo "<script>alert('请先登录');location='/admin/login'</script>";die;
        }

        return $next($request);
    }
}
