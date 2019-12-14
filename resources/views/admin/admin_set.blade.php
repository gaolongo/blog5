@extends('layouts.admin')
 @section('content')
    <form action="{{url('/admin/admin_role_insert')}}" method="post">
        <input type="hidden" name="right_id" value="{{$right_id}}">
        <input type="hidden" name="admin_id" value="{{$admin_id}}">
    <table class="table">
        <tr align="center">
            <td>序号</td>
            <td>名称</td>
            <td>介绍</td>
            <td>权限角色选中</td>
        </tr>
        @foreach($role as $v)
            <tr align="center">
                <td>{{$v->role_id}}</td>
                <td>{{$v->role_name}}</td>
                <td>{{$v->description}}</td>
                <td>
                    <input type="checkbox" name="quanxian[]" value="{{$v->role_id}}" id="">
                </td>
            </tr>
        @endforeach
        <tr>
            <td colspan="4" align="right">
                <input type="submit" value="提交">
            </td>
        </tr>
    </table>
    </form>
    @endsection
