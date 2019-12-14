@extends('layouts.admin')

@section('content')
<h3>货品添加</h3>
<form action="{{url('/admin/product_add_do')}}" method="post">
<table width="100%" id="table_list" class='table table-bordered'>
<input type="hidden" name="goods_id" value="{{$goodsData['goods_id']}}">
    <tbody>
    <tr>
      <th colspan="20" scope="col">商品名称：oppo11&nbsp;&nbsp;&nbsp;&nbsp;货号：ECS000075</th>
    </tr>

    <tr>
      <!-- start for specifications -->
      @foreach($spec as $k=>$v)
      <td scope="col"><div align="center"><strong>{{$v[0]['attr_name']}}</strong></div></td>
      <input type="hidden" name="attr_name[]" value="{{$v[0]['attr_name']}}">
      @endforeach
            <!-- end for specifications -->
      <td class="label_2">货号</td>
      <td class="label_2">库存</td>
      <td class="label_2">&nbsp;</td>
    </tr>
    
    <tr id="attr_row">
	    <!-- start for specifications_value -->
      @foreach($spec as $k=>$v)
		<td align="center" style="background-color: rgb(255, 255, 255);">
			<select name="value_list[]">
				<option value="" selected="">请选择...</option>
              @foreach($v as $kk=>$vv)
				   <option value="{{$vv['attr_value']}}">{{$vv['attr_value']}}</option>
			  @endforeach
			</select>
		</td>
    @endforeach
		<!-- <td align="center" style="background-color: rgb(255, 255, 255);">
			<select name="attr[214][]">
				<option value="" selected="">请选择...</option>
			    <option value="土豪金">土豪金</option>
			    <option value="太空灰">太空灰</option>
			</select>
		</td> -->
	    <!-- end for specifications_value -->
		<td class="label_2" style="background-color: rgb(255, 255, 255);"><input type="text" name="product_sn[]" value="" size="20"></td>
		<td class="label_2" style="background-color: rgb(255, 255, 255);"><input type="text" name="pro_number[]" value="1" size="10"></td>
		<td style="background-color: rgb(255, 255, 255);"><a href="javascript:;" class="addRow">[+]</a></td>
    </tr>

    <tr>
      <td align="center" colspan="5" style="background-color: rgb(255, 255, 255);">
        <input type="submit" class="button" value=" 保存 ">
      </td>
    </tr>
  </tbody>
</table>
</form>
<script type="text/javascript">
    //+   -  号
		$(document).on('click','.addRow',function(){
			// alert(12);
			var val=$(this).html();
			if(val=="[+]"){
				$(this).html("[-]");
				var tr_clone=$(this).parent().parent().clone();
				console.log(tr_clone);
				$(this).parent().parent().after(tr_clone);
				$(this).html("[+]");
			}else{
				$(this).parent().parent().remove();
			}
		})
</script>
@endsection