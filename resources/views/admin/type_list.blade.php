@extends('layouts.admin')
@section('content')
<table class="table table-bordered">
    <tr>
        <td>ID</td>
        <td>类型名称</td>
        <td>属性数</td>
        <td>操作</td>
    </tr>
    @foreach($data as $v)
    <tr>
        <td>{{$v->type_id}}</td>
        <td>{{$v->type_name}}</td>
        <td>{{$v->attr_count}}</td>
        <td>
            <a href="/admin/attr_attrlist?type_id={{$v['type_id']}}">属性列表</a>
        </td>
    </tr>
    @endforeach
</table>
@endsection