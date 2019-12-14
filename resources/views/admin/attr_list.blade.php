@extends('layouts.admin')
@section('content')
<table class="table table-bordered">
     <tr>
         <td class="mail-subject"><input type="checkbox" class="i-checks"  id="all">全选</td>
         <td>编号</td>
         <td>属性名称</td>
         <td>所属商品类型</td>
         <td>属性是否可选</td>
         <td>操作</td>
     </tr>
     @foreach($data as $v)
     <tr>
         <td><input type="checkbox" class="i-checks" attr_id="{{$v->attr_id}}" name="interest"></td>
         <td>{{$v->attr_id}}</td>
         <td>{{$v->attr_name}}</td>
         <td>{{$v->type_name}}</td>
         <td>{{$v->attr_hua}}</td>
         <td><a href="">删除</a></td>
     </tr>
     @endforeach
</table>
<input type="button" value="删除" id="del">
<script type="text/javascript">
    $(function () {
// alert(111);
        $('#all').click(function() {
            // console.log($(this).prop('checked'));
            var bAll = $(this).prop('checked');
            if (bAll) {
                //全选
                $('tbody tr').addClass('selected');
                $('tbody :checkbox').prop('checked', true);
            } else {
                //全不选
                $('tbody tr').removeClass('selected');
                $('tbody :checkbox').prop('checked', false);
            }
        })
        $('#del').click(function () {
            // alert(111);


            var attr_id =[];//定义一个数组
            $('input[name="interest"]:checked').each(function(){//遍历每一个名字为interest的复选框，其中选中的执行函数
                attr_id.push($(this).attr('attr_id'));//将选中的值添加到数组chk_value中
            });
            // console.log(attr_id);
            $.ajax({
                url:"attr_del",
                data:{attr_id:attr_id},
                type:'get',
                dataType:'json',
                success:function(res){
                    // console.log(res);
                    if (res.code==200){
                        alert(res.msg);
                    }else{
                        alert(res.msg);
                    }
                }
            })


        })
    })
</script>
@endsection