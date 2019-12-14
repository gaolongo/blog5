@extends('layouts.admin')
@section('content')
<form action="{{url('/admin/type_add_do')}}" method="post">
<div class="form-group">
    <label for="exampleInputEmail1">类型名称</label>
    <input type="text" class="form-control" name="type_name">
  </div>
  <button type="submit" class="btn btn-default">添加</button>
  <button type="submit" class="btn btn-default"><a href="{{url('/admin/type_list')}}">类型列表</a></button>
</form>
@endsection