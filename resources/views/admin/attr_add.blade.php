@extends('layouts.admin')
@section('content')
<form action="{{url('/admin/attr_add_do')}}" method="post">
<div class="form-group">
    <label for="exampleInputEmail1">属性名称</label>
    <input type="text" class="form-control" name="attr_name">
  </div>
  <div class="form-group">
    <label for="exampleInputEmail1">所属商品类型</label>
    <select class="form-control input-sm" name="type_id">
          @foreach($info as $v)
            <option value="{{$v->type_id}}">{{$v->type_name}}</option>
            @endforeach
    </select>
  </div>
  <div class="form-group">
      <label for="exampleInputEmail1">属性是否可选:</label>
          <input type="radio" name="attr_hua" value="1" >参数
          <input type="radio" name="attr_hua" value="2" >规格
        </label>
  </div>
  <button type="submit" class="btn btn-default">添加</button>
  <button type="submit" class="btn btn-default"><a href="{{url('/admin/attr_list')}}">属性列表</a></button>
</form>
@endsection