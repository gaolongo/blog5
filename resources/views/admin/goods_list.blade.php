@extends('layouts.admin')
@section('content')
<form action="">
	<input type="text" name="goods_name" value="{{$sear['goods_name']??''}}"  placeholder="请输入商品名称关键字">
    <input type="text" name="goods_price" value="{{$sear['goods_price']??''}}" placeholder="请输入商品价格关键字">
		<input type="submit" value="搜索">
</form>
<table class="table table-bordered">
    <tr>
        <td>ID</td>
        <td>商品名称</td>
        <td>分类名称</td>
        <td>商品价格</td>
        <td>商品图片</td>
        <td>上下架</td>
    </tr>
    @foreach($data as $v)
    <tr>
        <td>{{$v->goods_id}}</td>
        <td>{{$v->goods_name}}</td>
        <td>{{$v->cate_name}}</td>
        <td>{{$v->goods_price}}</td>
        <td><img src="{{$v->goods_img}}" width='100' heigth='100'></td>
        <td>           
            @if (($v['is_show'])== 1)
                <span class="se" style='color:red'>已上架</span>
            @else
                <span class="se" style='color:red'>以下架</span>
            @endif                 
        </td>
    </tr>
    @endforeach
</table>
{{$data->appends($query)->links()}}
<script type="text/javascript">
        $(document).on("click",".se",function (){
              //alert(111);
             var _this = $(this);
             var id = _this.parent().prev().prev().prev().prev().prev().text();
             //alert(id);
             var test =_this.html();
             // console.log(test);
             var is_show = '';
             if (test=='已上架') {
                 var test =_this.html('已下架');
                 var is_show = 2;
             }else{
                 var test =_this.html('已上架');
                 var is_show = 1;
             }
             //alert(is_show);
             $.ajax({
                 url:"{{url('admin/isshow')}}",
                 data:{is_show:is_show,id:id},
                 type:'get',
                 dataType:'json',
                 success:function(res){
                     console.log(res);
                 }
             })
         });
 </script>
@endsection