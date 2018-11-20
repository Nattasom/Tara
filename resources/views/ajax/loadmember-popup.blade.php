<div class="loading-box hide"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i><br/><br/>Loading...</div>
    <div class="table-responsive"> 
        <table class="table table-striped table-hover">
        <thead>
            <tr>
            <th>หมายเลขสมาชิก</th>
            <th>ชื่อสมาชิก</th>
            <th>ยอดคงเหลือ</th>
            <th>วันหมดอายุ</th>
            <th>พนักงานต้อนรับ</th>
            </tr>
        </thead>
        <tbody>
            @foreach($memberlist as $key=>$value)
                <tr class="selected-item" data-id="{{$value->SysMember_ID}}" data-suite="{{$value->Suite}}" data-suite2="{{$value->Suite2}}" data-bal="{{$value->Credit_Amt}}" data-code="{{$value->Member_ID}}">
                    <th scope="row">{{$value->Member_ID}}</th>
                    <td class="text-name">{{$value->First_Name}} {{$value->Last_Name}}</td>
                    <td>{{$util->numberFormat($value->Credit_Amt)}}</td>
                    <td>{{$util->dateToThaiFormat($value->Expired_Date)}}</td>
                    <td>{{$value->Recept_Name}} {{$value->Recept_Lastname}}</td>
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