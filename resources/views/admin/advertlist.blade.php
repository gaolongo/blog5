 @extends('layouts.admin')
@section('content')
<form action="">
    <input type="text" name="ad_name" value="{{$sear['ad_name']??''}}"  placeholder="请输入">
        <input type="submit" class='search btn btn-info' value="搜索">
</form>
<table class="table table-bordered">
     <tr>
          <td>id</td>
        <td>动态名称</td>
        <td>描述</td>
        <td>添加时间</td>
        <td>图片</td>
        <td>操作</td>
     </tr>
     @foreach($data as $v)
     <tr>
         <td>{{$v->ad_id}}</td>
         <td>{{$v->ad_name}}</td>
         <td>{{$v->ad_title}}</td>
         <td>{{date('Y-m-d'),$v->time}}</td>
          <td><img src="{{$v->ad_img}}" height="100" alt=""></td>
         <td><a href="{{url('admin/ad_del',['ad_id'=>$v->ad_id])}}">删除</a>
        <!-- <a href="{{url('admin/ad_up',['ad_id'=>$v->ad_id])}}">修改</a></td> -->
     </tr>
     @endforeach
</table>
  {{$data->appends($query)->links()}}
@endsection

