@extends('layouts.admin')
@section('content')
<table class="table table-bordered">
     <tr>
         <td>编号</td>
         <td>属性名称</td>
         <td>所属商品类型</td>
         <td>属性是否可选</td>
         <td>操作</td>
     </tr>
     @foreach($res as $v)
     <tr>
         <td>{{$v->attr_id}}</td>
         <td>{{$v->attr_name}}</td>
         <td>{{$v->type_name}}</td>
         <td>{{$v->attr_hua}}</td>
         <td><a href="">删除</a></td>
     </tr>
     @endforeach
</table>
@endsection