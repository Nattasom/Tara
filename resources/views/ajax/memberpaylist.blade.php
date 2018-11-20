<div class="loading-box hide"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i><br/><br/>Loading...</div>
                            <div class="table-responsive"> 
                                <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                    <th>วันที่ทำรายการ</th>
                                    <th>หมายเลขสมาชิก</th>
                                    <th>ยอดชำระ</th>
                                    <th>สูท</th>
                                    <th>Gold</th>
                                    <th>Black</th>
                                    <th>Beer</th>
                                    <th>ประเภทชำระ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($memberlist as $key=>$value)
                                        <tr>
                                            <td>{{$util->dateToThaiFormat($value->Paid_Date)}}</td>
                                            <td>{{$value->Member_ID}}:{{$value->First_Name}}</td>
                                            <td>{{$util->numberFormat($value->Add_Credit)}}</td>
                                            <td>{{$value->Add_Suite}}</td>
                                            <td>{{$value->Add_Whisky}}</td>
                                            <td>{{$value->Add_Whisky2}}</td>
                                            <td>{{$value->Add_Beer}}</td>
                                            <td>
                                                @php($strType = "")
                                                @if($value->Cash_Amt > 0)
                                                    @php($strType .= "เงินสด")
                                                @endif
                                                @if($value->Credit_Amt > 0)
                                                    @if($strType != "")
                                                        @php($strType .= ",")
                                                        @php($strType .= "บัตรเครดิต")
                                                    @endif
                                                @endif
                                                {{$strType}}
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