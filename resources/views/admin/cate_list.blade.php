@extends('layouts.admin')
@section('content')
<table class="table table-bordered">
    <tr>
        <td>ID</td>
        <td>分类名称</td>
        <td>操作</td>
    </tr>
    @foreach($data as $v)
    <tr>
        <td>{{$v->cate_id}}</td>
        <td>{{$v->cate_name}}</td>
        <td><a href="{{url('admin/cate_del',['cate_id'=>$v->cate_id])}}">删除</a></td>
    </tr>
    @endforeach
</table>
@endsection