@extends('layouts.admin')
@section('content')
<form action="{{url('/admin/cate_add_do')}}" method="post">
<div class="form-group">
    <label for="exampleInputEmail1">分类名称</label>
    <input type="text" class="form-control" name="cate_name" id="add">
    <span style="color:red" id="sp"></span>
</div>
  <label for="exampleInputEmail1">顶级分类</label>
  <select name='cate_pid' class="form-control">
       <option value="0">顶级分类</option>
        @foreach($cate as $v)
            <option value="{{$v['cate_id']}}">@php echo str_repeat("&nbsp;&nbsp;&nbsp;",$v['level'])@endphp {{$v['cate_name']}}</option>
        @endforeach
        </select>
  <button type="submit" class="btn btn-default" id="sub">添加</button>
  <button type="submit" class="btn btn-default" id="sub"><a href="{{url('admin/cate_list')}}">分类列表</a></button>
</form>
@endsection