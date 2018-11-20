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
            <th>แก้ไข</th>
            </tr>
        </thead>
        <tbody>
            @foreach($memberlist as $key=>$value)
                <tr>
                    <th scope="row">{{$value->Member_ID}}</th>
                    <td>{{$value->First_Name}} {{$value->Last_Name}}</td>
                    <td>{{$util->numberFormat($value->Credit_Amt)}}</td>
                    <td>{{$util->dateToThaiFormat($value->Expired_Date)}}</td>
                    <td>{{$value->Recept_Name}} {{$value->Recept_Lastname}}</td>
                    <td>
                        @if($flag_edit)
                            <a class="btn btn-primary btn-sm" href="{{Config::get('app.context')}}memberedit/{{$value->SysMember_ID}}"><i class="fa fa-edit"></i> แก้ไข</a>
                            <a class="btn btn-success btn-sm" href="{{Config::get('app.context')}}membertopup/{{$value->SysMember_ID}}"><i class="fa fa-usd"></i> เติมเงิน</a>
                            <a class="btn btn-info btn-sm" href="{{Config::get('app.context')}}memberdeduct/{{$value->SysMember_ID}}"><i class="fa fa-minus"></i> ตัดยอด</a>
                            <button type="button" class="btn btn-danger btn-sm btn-del" data-id="{{$value->SysMember_ID}}"><i class="fa fa-trash"></i> ลบ</button>
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