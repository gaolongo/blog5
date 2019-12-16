@extends('layouts.admin')
@section('content')
<form action="{{url('/admin/advert_do')}}" method="post" enctype="multipart/form-data">
<div class="form-group">
    <label for="exampleInputEmail1">广告名称</label>
    <input type="text" class="form-control" name="ad_name">
  </div>
 <div class="form-group">
    <label for="exampleInputEmail1">描述</label>
   <textarea class="form-control" name="ad_title" rows="3"></textarea>
  </div>

  <div class="form-group">
    <label for="exampleInputEmail1">头像</label>
     <input type="file" name="ad_img">
              <div id="is_show"></div>
  </div>
  <button type="submit" class="btn btn-default">添加</button>
  <button type="submit" class="btn btn-default"><a href="{{url('/admin/advertlist')}}">属性列表</a></button>
</form>
@endsection
