@extends('layouts.admin')
 @section('content')
    <form action="{{url('/admin/admin_submit')}}" method="post">
        <input type="hidden" name="right_id" value="{{$right_id}}">
        <table class="table">
            <tr>
                <td>管理员名字</td>
                <td>
                    <input type="text" name="admin_name" id="">
                </td>
            </tr>
            <tr>
                <td>管理员密码</td>
                <td>
                    <input type="password" name="password" id="">
                </td>
            </tr>
            <tr>
                <td>手机号</td>
                <td>
                    <input type="tel" name="mobile" id="">
                </td>
            </tr>
            <tr>
                <td>电子邮箱</td>
                <td>
                    <input type="email" name="email" id="">
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <input type="submit" value="提交">
                </td>
            </tr>
        </table>
    </form>
    @endsection