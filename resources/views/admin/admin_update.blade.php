@extends('layouts.admin')
 @section('content')
    <form action="{{url('/admin/admin_update_add')}}" method="post">
        <input type="hidden" name="admin_id" value="{{$admin_id}}">
        <table class="table">
            <tr>
                <td>密码</td>
                <td>
                    <input type="password" name="password" id="">
                </td>
            </tr>
            <tr>
                <td colspan="2" align="right">
                    <input type="submit" value="提交">
                </td>
            </tr>
        </table>
    </form>
@endsection