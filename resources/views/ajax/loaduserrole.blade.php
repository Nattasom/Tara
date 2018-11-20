<div class="loading-box hide"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i><br/><br/>Loading...</div>
<div class="table-responsive"> 
    <table class="table table-striped table-hover">
    <thead>
        <tr>
        <th>#</th>
        <th>รหัสกลุ่มผู้ใช้</th>
        <th>ชื่อกลุ่มผู้ใช้</th>
        <th>รายละเอียด</th>
        <th>จัดการ</th>
        </tr>
    </thead>
    <tbody>
        @foreach($rolelist as $key=>$value)
            <tr>
                <th scope="row">{{++$paging->start}}</th>
                <td>{{$value->Role_Code}}</td>
                <td>{{$value->Role_Name}}</td>
                <td>{{$value->Role_Desc}}</td>
                <td>
                    @if($flag_edit)
                        <a href="{{Config::get('app.context')}}userroleedit/{{$value->SysRole_ID}}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> แก้ไข</a >
                        <a href="{{Config::get('app.context')}}userrolepermission/{{$value->SysRole_ID}}" class="btn btn-info btn-sm"><i class="fa fa-lock"></i> กำหนดสิทธิ์</a >
                        <button class="btn btn-danger btn-sm btn-del" data-id = "{{$value->SysRole_ID}}" type="button"><i class="fa fa-trash"></i> ลบ</button>
                    @else
                    -
                    @endif
                </td>
            </tr>
        @endforeach
        
    </tbody>
    </table>
</div>
<div class="row">
    <div class="col-md-5">
        <div class="paging-detail-text">{{$paging->paging_detail}}</div>
    </div>
    <div class="col-md-7 text-md-right">
        <nav >
            <ul class="pagination">
                {!!$paging->renderHtml()!!}
            </ul>
        </nav>
    </div>
</div>