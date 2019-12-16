@extends('layouts.admin')
@section('content')
<form action="{{url('/admin/update')}}" method="post" enctype="multipart/form-data">
    <input type="hidden" name="ad_id" value="{{$data['ad_id']}}">
<div class="form-group">
    <label for="exampleInputEmail1">广告名称</label>
    <input type="text" class="form-control" name="ad_name" value="{{$data['ad_name']}}">
  </div>
 <div class="form-group">
    <label for="exampleInputEmail1">描述</label>
   <textarea class="form-control" name="ad_title" rows="3" value="{{$data['ad_title']}}"></textarea>
  </div>

  <div class="form-group">
    <label for="exampleInputEmail1">头像</label>
     <input type="file" name="ad_img">
              <div id="is_show"></div>
  </div>
  <button type="submit" class="btn btn-default">修改</button>
</form>
@endsection
