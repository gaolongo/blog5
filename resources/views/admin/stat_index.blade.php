@extends('layouts.admin')
@section('content')
    <form action="">
        <input type="text"  name="admin_name" id="" value="{{$query['admin_name']??''}}" placeholder="用户名">
        <button>搜索</button>
    </form>
   <table class="table table-bordered">
       <tr>
           <td>ID</td>
           <td>用户名</td>
           <td>注册时间</td>
       </tr>
       @foreach($data as $k=>$v)
       <tr>
           <td>{{$v['admin_id']}}</td>
           <td>{{$v['admin_name']}}</td>
           <td>{{ date( "Y-m-d H:i:s",$v['create_time'])}}</td>
       </tr>
           @endforeach
   </table>
   {{ $data->appends($query)->links() }}

@endsection