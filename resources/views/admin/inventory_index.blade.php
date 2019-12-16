@extends('layouts.admin')
@section('content')
    <form action="">
        <input type="text"  name="goods_name" id="" value="{{$query['goods_name']??''}}" placeholder="商品名称">
        <button>搜索</button>
    </form>
   <table class="table table-bordered">
       <tr>
           <td>ID</td>
           <td>商品名称</td>
           <td>商品库存</td>
       </tr>
       @foreach($data as $k=>$v)
       <tr>
           <td>{{$v['pro_id']}}</td>
           <td>{{$v['goods_name']}}</td>
           <td>{{$v['pro_number']}}</td>
       </tr>
           @endforeach
   </table>
   {{ $data->appends($query)->links() }}

@endsection