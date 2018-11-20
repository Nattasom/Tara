<div class="loading-box hide"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i><br/><br/>Loading...</div>
<div class="table-responsive"> 
    <table class="table table-striped table-hover">
    <thead>
        <tr>
        <th>รหัสพนักงาน</th>
        <th>ชื่อ-นามสกุล</th>
        <th>ชื่อเล่น</th>
        <th>สถานะ</th>
        </tr>
    </thead>
    <tbody>
        @foreach($prettylist as $key=>$value)
            <tr class="selected-item" data-id="{{$value->SysAngel_ID}}" data-code="{{$value->Angel_ID}}" data-status="{{$value->Angel_Status}}">
                <th scope="row" >{{$value->Angel_ID}}</th>
                <td >{{$value->First_Name}} {{$value->Last_Name}}</td>
                <td class="text-name">{{$value->Nick_Name}}</td>
                <td>
                    @if($value->Angel_Status=="AB")
                    <span class="text-warning">ไม่มาทำงาน</span>
                    @elseif($value->Angel_Status=="NW")
                    <span class="text-success">พร้อมทำงาน</span>
                    @elseif($value->Angel_Status=="WK")
                    <span class="text-danger">กำลังทำงาน</span>
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