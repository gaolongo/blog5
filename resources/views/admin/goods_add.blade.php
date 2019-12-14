@extends('layouts.admin')

@section('content')
	<h3>商品添加</h3>
	<ul class="nav nav-tabs">
	  <li role="presentation" class="active"><a href="javascript:;" name='basic'>基本信息</a></li>
	  <li role="presentation" ><a href="javascript:;" name='attr'>商品属性</a></li>
	  <li role="presentation" ><a href="javascript:;" name='detail'>商品详情</a></li>
	</ul>
	<br>
	<form action="{{url('/admin/goods_add_do')}}" method="post" enctype="multipart/form-data" id='form'>
		
		<div class='div_basic div_form'>
			<div class="form-group">
				<label for="exampleInputEmail1">商品名称</label>
				<input type="text" class="form-control" name='goods_name'>
			</div>
			<div class="form-group">
				<label for="exampleInputEmail1">商品分类</label>
				<select class="form-control" name='cate_id'>
				    <option value="">请选择</option>
				@foreach($cateData as $v)
					<option value='{{$v->cate_id}}'>{{$v->cate_name}}</option>
				@endforeach	
				</select>
			</div>
			<div class="form-group">
				<label for="exampleInputEmail1">商品货号</label>
				<input type="text" class="form-control" name='goods_price'>
			</div>

			<div class="form-group">
				<label for="exampleInputEmail1">商品价钱</label>
				<input type="text" class="form-control" name='goods_price'>
			</div>

			<div class="form-group">
				<label for="exampleInputFile">商品图片</label>
				<input type="file" name='goods_img'>
			</div>
		</div>	
		<div class='div_detail div_form' style='display:none'>
			<div class="form-group">
				<label for="exampleInputFile">商品详情</label>
				<textarea class="form-control" rows="3"></textarea>
			</div>
		</div>
		<div class='div_attr div_form' style='display:none'>
			<div class="form-group">
				<label for="exampleInputEmail1">商品类型</label>
				<select class="form-control" name='type_id' >
				<option value="">请选择</option>
				@foreach($typeData as $v)
					<option value="{{$v->type_id}}">{{$v->type_name}}</option>
				@endforeach	
				</select>
			</div>	
			<br>

			<table width="100%" id="attrTable" class='table table-bordered'>
				<!-- <tr>
					<td>前置摄像头</td>
					<td>
						<input type="hidden" name="attr_id_list[]" value="211">
						<input name="attr_value_list[]" type="text" value="" size="20">  
						<input type="hidden" name="attr_price_list[]" value="0">
					</td>
				</tr>
				<tr>
					<td><a href="javascript:;">[+]</a>颜色</td>
					<td>
						<input type="hidden" name="attr_id_list[]" value="214">
						<input name="attr_value_list[]" type="text" value="" size="20"> 
						属性价格 <input type="text" name="attr_price_list[]" value="" size="5" maxlength="10">
					</td>
				</tr> -->
			</table>
			<!-- <div class="form-group">
					颜色:
					<input type="text" name='attr_value_list[]'>
			</div> -->
			<!-- <div class="form-group" style='padding-left:26px'>
				<a href="javascript:;">[+]</a>内存:
				<input type="text" name='attr_value_list[]'>
				属性价格:<input type="text" name='attr_price_list[][]'>
			</div> -->
			
		</div>

	  <button type="submit" class="btn btn-default" id='btn'>添加</button>
	  <button type="submit" class="btn btn-default" id='btn'><a href="{{url('/admin/goods_list')}}">商品列表</a></button>
	</form>

	<script type="text/javascript">
		//标签页 页面渲染
		$(".nav-tabs a").on("click",function(){
			$(this).parent().siblings('li').removeClass('active');
			$(this).parent().addClass('active');
			var name = $(this).attr('name');  // attr basic
			$(".div_form").hide();
			$(".div_"+name).show();  // $(".div_"+name)
		})
		$("[name='type_id']").on('change',function(){
			var type_id=$(this).val();
			$.ajax({
				url:"{{url('admin/getAttr')}}",
				data:{type_id:type_id},
				dataType:"json",
				success:function(res){
					$("#attrTable").empty();
					$.each(res,function(i,v){	
						if(v.attr_hua==1){
							var tr='<tr>\
						        <td>'+v.attr_name+'</td>\
									<td><input type="hidden" name="attr_id_list[]" value='+v.attr_id+'>\
										<input name="attr_value_list[]" type="text" value="" size="20">\
										<input type="hidden" name="attr_price_list[]" value="0">\
									</td>\
								</tr>';
						}else{
							var tr='<tr>\
										<td><a href="javascript:;" class="addRow">[+]</a>'+v.attr_name+'</td>\
										<td>\
											<input type="hidden" name="attr_id_list[]" value='+v.attr_id+'>\
											<input name="attr_value_list[]" type="text" value="" size="20"> \
											属性价格 <input type="text" name="attr_price_list[]" value="" size="5" maxlength="10">\
										</td>\
									</tr>';
						}
			        $("#attrTable").append(tr);
					})
				}
			})
		})	
		//+   -  号
		$(document).on('click','.addRow',function(){
			// alert(12);
			var val=$(this).html();
			if(val=="[+]"){
				$(this).html("[-]");
				var tr_clone=$(this).parent().parent().clone();
				$(this).parent().parent().after(tr_clone);
				$(this).html("[+]");
			}else{
				$(this).parent().parent().remove();
			}
		})
	</script>
@endsection