 @extends('layouts.admin')
 @section('content')
    <a href="{{url('/admin/admin_insert')}}?right_id=12">添加管理员</a>
    <table class="table">
        <tr align="center">
            <td>ID</td>
            <td>管理员</td>
            <td>手机号</td>
            <td>时间</td>
            <td>操作</td>
        </tr>
        @foreach($admin as $v)
        <tr align="center">
            <td>{{$v->admin_id}}</td>
            <td>{{$v->admin_name}}</td>
            <td>{{$v->mobile}}</td>
            <td>{{date('Y-m-d H:i:s',$v->create_time)}}</td>
            <td>
                <a href="{{url('/admin/admin_delete',['admin_id'=>$v->admin_id])}}?right_id=11">删除</a>
                <a href="{{url('/admin/admin_update')}}?admin_id={{$v->admin_id}}">密码修改</a>
                <a href="{{url('/admin/admin_set',['admin_id'=>$v->admin_id])}}?right_id=16">设置角色</a>
            </td>
        </tr>
        @endforeach
    </table>
    
@endsection
