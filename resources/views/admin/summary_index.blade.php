@extends('layouts.admin')
@section('content')
    <form action="">
        <input type="text"  name="or_num" id="" value="{{$query['or_num']??''}}" placeholder="订单号">
        <button>搜索</button>
    </form>
   <table class="table table-bordered">
       <tr>
           <td>ID</td>
           <td>用户名</td>
          
       </tr>
       @foreach($data as $k=>$v)
       <tr>
           <td>{{$v['sum_id']}}</td>
           <td>{{$v['or_num']}}</td>
          
       </tr>
           @endforeach
   </table>
   {{ $data->appends($query)->links() }}

@endsection