@isset($users)
    @foreach($users as $key=>$value)
        <tr>
            <td class="text-center">{{$sl_counter++}}</td>
            <td >{{$value->name}}</td>
            <td >{{$value->phone}}</td>
            <td >{{$value->email}}</td>
            <td >{{$value->university->name}}</td>
            <td >{{isset($value->approver->name)?$value->approver->name:""}}</td>
            <td >{{($value->status==1)?"Approved":(($value->status==2)?"Pending":"New")}}</td>
            <td class="text-center" >
                @if($value->role_id==2)
                    <a  href="{{url("admin/user-edit/".$value->id)}}"  class="text-info btn btn-info btn-xs  waves-effect tooltips" data-placement="top" data-toggle="tooltip" data-original-title="Edit" id=""><i class="fa fa-edit"></i></a>
                    <a onclick="confirmAction(event, this, 'Are You Sure?', 'Do you want to change the status?')" href="{{url("admin/control/".$value->id)}}" title="{{($value->status==1)?"Approved":(($value->status==2)?"Pending":"New")}}" class="btn btn-{{($value->status==1)?"success":(($value->status==2)?"danger":"primary")}}   btn-xs  waves-effect tooltips" data-placement="top" data-toggle="tooltip" data-original-title="View" id=""><i class="fa fa-check-circle"></i></a>
                    <a  href="{{url("admin/login/".$value->id)}}" onclick="" title="Login" class="btn btn-info   btn-xs  waves-effect tooltips" data-placement="top" data-toggle="tooltip" data-original-title="View" id=""><i class="fa fa-lock"></i></a>
                    <a onclick="confirmAction(event, this, 'Are you sure?', 'Do you want to completely delete this user and their apps?')" href="{{url("admin/user-delete/".$value->id)}}" title="Delete" class="btn btn-danger btn-xs waves-effect tooltips" data-placement="top" data-toggle="tooltip" data-original-title="Delete"><i class="fa fa-trash"></i></a>
                @endif
            </td>
        </tr>
    @endforeach
     <tr>
        <td colspan="8" class="text-center">{{$users->links()}}</td>
    </tr>
@endisset
