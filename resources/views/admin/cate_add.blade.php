@extends('layouts.admin')
@section('content')
<form action="{{url('/admin/cate_add_do')}}" method="post">
<div class="form-group">
    <label for="exampleInputEmail1">分类名称</label>
    <input type="text" class="form-control" name="cate_name" id="cate_name">
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
<script>
        $('#cate_name').blur(function() {
            // alert(111);
            var cate_name = $('#cate_name').val();
            // console.log(cate_name);
            var _this = $(this);
            $.ajax({
                url:"/admin/nameOnly",
                data:{cate_name:cate_name},
                type:'get',
                dataType:'json',
                success:function(res){
                    // console.log(res);
                    if (res.font==2){
                        alert('该分类名可以使用');
                        $("#btn").prop('disabled',true);
                    }else{
                        alert('该分类名已被占用');
                        $("#btn").prop('disabled',false);
                    }
                }
            })
        })
@endsection