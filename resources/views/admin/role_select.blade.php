@extends('layouts.admin')
 @section('content')
        <table  class="table">
            <tr>
                <td>
                    角色名称:
                    <input type="text" name="role_name" id="">
                </td>
            </tr>
            <tr>
                <td>
                    角色介绍:
                    <textarea name="description" id="description" cols="30" rows="10"></textarea>
                </td>
            </tr>
            <tr>
                <td>
                    <input type="submit" value="提交">
                </td>
            </tr>
        </table>
    
    <script>
        $('[type="submit"]').on('click',function(){
            // alert(1);
            var description = $('#description').val();
            var role_name = $('[name="role_name"]').val();
            // alert(role_name);die;
            // if(description == '' || role_name==''){
            //     alert('不能为空');return;
            // }
            $.ajax({
                url : 'role_insert',
                data : {description:description,role_name:role_name},
                type : 'post',
                dataType : 'json',
                success : function (res) {
                    alert(res.msg);
                    if(res.code == 200){
                        location.href = 'right?role_id='+res.role_id+'&&'+'right_id=17';
                    }
                }
            })
        })
    </script>
@endsection