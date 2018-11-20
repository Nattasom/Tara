<div class="loading-box hide"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i><br/><br/>Loading...</div>
<div class="table-responsive"> 
    <table class="table table-striped table-hover">
    <thead>
        <tr>
        <th>รหัสประเภท</th>
        <th>ประเภท</th>
        <th>แก้ไข</th>
        </tr>
    </thead>
    <tbody>
        @foreach($roomtypelist as $key=>$value)
            <tr>
                <td>{{$value->Room_Type_Code}}</td>
                <td>{{$value->Room_Type_Desc}}</td>
                <td>
                    @if($flag_edit)
                        <a class="btn btn-primary btn-sm" href="roomtypeedit/{{$value->SysRoom_Type_ID}}"><i class="fa fa-edit"></i> แก้ไข</a>
                        <button class="btn btn-danger btn-sm btn-del" data-id="{{$value->SysRoom_Type_ID}}"><i class="fa fa-trash"></i> ลบ</button>
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