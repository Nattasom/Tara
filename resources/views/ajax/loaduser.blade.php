<div class="loading-box hide"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i><br/><br/>Loading...</div>
<div class="table-responsive"> 
    <table class="table table-striped table-hover">
    <thead>
        <tr>
        <th>#</th>
        <th>ชื่อผู้ใช้</th>
        <th>ชื่อ-นามสกุล</th>
        <th>จัดการ</th>
        </tr>
    </thead>
    <tbody>
        @foreach($userlist as $key=>$value)
            <tr>
                <th scope="row">{{++$paging->start}}</th>
                <td>{{$value->Login_Name}}</td>
                <td>{{$value->User_Fullname}}</td>
                <td>
                    @if($flag_edit)
                        <a class="btn btn-primary btn-sm" href="{{Config::get('app.context')}}useredit/{{$value->SysUser_ID}}"><i class="fa fa-edit"></i> แก้ไข</a>
                        <button type="button" class="btn btn-danger btn-sm btn-del" data-id="{{$value->SysUser_ID}}"><i class="fa fa-trash"></i> ลบ</button>
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