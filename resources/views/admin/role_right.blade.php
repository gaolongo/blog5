@extends('layouts.admin')
 @section('content')
    <form action="{{url('/admin/role_right')}}" method="post">
        <input type="hidden" name="role_id" value="{{$role_id}}">
        <table align="table">
            @foreach($role_right as $v)
                <tr>
                    <td>{{$v['right_name']}}</td>
                    <td></td>
                </tr>

                <tr>
                    <td></td>
                    @foreach($v['name'] as $vi)
                        <td>
                            <input type="checkbox" name="r_id[]" id="" class="aaa" value="{{$vi['right_id']}}">{{$vi['description']}}
                        </td>
                    @endforeach
                </tr>
            @endforeach
            <tr>
                <td><input type="submit" value="添加"></td>
            </tr>
        </table>
    </form>
@endsection