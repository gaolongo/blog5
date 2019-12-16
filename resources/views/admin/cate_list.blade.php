@extends('layouts.admin')
@section('content')
<table class="table table-bordered">
    <tr>
        <td>ID</td>
        <td>分类名称</td>
        <td>商品数量</td>
        <td>操作</td>
    </tr>
    @foreach($cate as $k=>$v)
        <tr >
            <td>
                {{$v['cate_id']}}
            </td>
            <td>@php echo str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",$v['level'])@endphp {{$v['cate_name']}}</td>
            <td>{{$v['cate_count']}}</td>
            <td><a href="{{url('admin/cate_del',['cate_id'=>$v['cate_id']])}}">删除</a></td>
        </tr>
    @endforeach
</table>
@endsection