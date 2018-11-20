<div class="loading-box hide"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i><br/><br/>Loading...</div>
<div class="table-responsive"> 
    <table class="table table-striped table-hover">
    <thead>
        <tr>
        <th>วันที่ทำรายการ</th>
        <th>พนักงานต้อนรับ</th>
        <th>หมายเหตุ</th>
        <th>จำนวนเงินที่ค้างจ่าย</th>
        <th>ชำระเงิน</th>
        </tr>
    </thead>
    <tbody>
        @foreach($list as $key=>$value)
            <tr>
                <td>{{$util->dateToThaiFormat($value->Txn_Date)}}</td>
                <td>{{$value->Recept_Name}}</td>
                <td>{{$value->Remark}}</td>
                <td>{{$util->numberFormat($value->Total_Unpaid)}}</td>
                <td>
                @if($flag_edit)
<a class="btn btn-primary btn-xs" href="{{Config::get('app.context')}}debtdetail?txn={{$value->Txn_No}}&txndate={{$value->Txn_Date}}"><i class="fa fa-usd"></i> ชำระเงิน</a>
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