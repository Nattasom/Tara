<div class="loading-box hide"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i><br/><br/>Loading...</div>
<div class="table-responsive"> 
    <table class="table table-striped table-hover">
    <thead>
        <tr>
        <th>รหัสพนักงาน</th>
        <th>ชื่อ-นามสกุล</th>
        <th>ชื่อเล่น</th>
        </tr>
    </thead>
    <tbody>
        @foreach($receiptlist as $key=>$value)
            <tr class="selected-item" data-id="{{$value->SysReception_ID}}" data-code="{{$value->Reception_ID}}">
                <th scope="row">{{$value->Reception_ID}}</th>
                <td class="text-name">{{$value->First_Name}} {{$value->Last_Name}}</td>
                <td>{{$value->Nick_Name}}</td>
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