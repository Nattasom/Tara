<div class="loading-box hide"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i><br/><br/>Loading...</div>
                            <div class="table-responsive"> 
                                <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                    <th>วันที่ทำรายการ</th>
                                    <th>หมายเลขสมาชิก</th>
                                    <th>ยอดค้างชำระ</th>
                                    <th>ชำระเงิน</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($memberlist as $key=>$value)
                                        <tr>
                                            <td>{{$util->dateToThaiFormat($value->Txn_Date)}}</td>
                                            <td>{{$value->Member_ID}}:{{$value->First_Name}}</td>
                                            <td>{{$util->numberFormat($value->Add_Credit)}}</td>
                                            <td>
                                                @if($flag_edit)
                                                    <a href="{{Config::get('app.context')}}memberdebtpay/{{$value->SysMember_Application_ID}}" class="btn btn-success btn-xs"><i class="fa fa-usd"></i></a>
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