<div class="loading-box hide"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i><br/><br/>Loading...</div>
<div class="table-responsive"> 
    <table class="table table-striped table-hover">
    <thead>
        <tr>
        <th>ห้อง</th>
        <th>ชั้น</th>
        <th>ประเภทห้อง</th>
        <th>สถานะ</th>
        <th>แก้ไข</th>
        </tr>
    </thead>
    <tbody>
        @foreach($roomlist as $key=>$value)
            <tr>
                <th scope="row">{{$value->Room_No}}</th>
                <td>{{$value->Floor}}</td>
                <td>{{$value->Room_Type_Desc}}</td>
                <td>
                     @if($value->Room_Status == 'IN')
                        <span class="label label-danger">{{$value->Room_Status_Text}}</span>
                    @elseif($value->Room_Status == 'VA')
                        <span class="label label-success">{{$value->Room_Status_Text}}</span>
                    @endif
                </td>
                <td>
                    @if($flag_edit)
                        <a class="btn btn-primary btn-sm" href="{{Config::get('app.context')}}roomedit/{{$value->SysRoom_ID}}"><i class="fa fa-edit"></i> แก้ไข</a>
                        <button type="button" class="btn btn-danger btn-sm btn-del" data-id="{{$value->SysRoom_ID}}"><i class="fa fa-trash"></i> ลบ</button>
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