<div class="loading-box hide"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i><br/><br/>Loading...</div>
                <div class="table-responsive"> 
                    <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                        <th>วันที่ทำรายการ</th>
                        <th>เลขที่รายการ</th>
                        <th>เชียร์แขก</th>
                        <th>ชื่อลูกค้า</th>
                        <th>ห้อง</th>
                        <th>พนักงาน</th>
                        <th>ดู</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($list as $key=>$value)
                            <tr>
                                <td>{{$util->dateToThaiFormat($value->Txn_Date)}}</td>
                                <td>{{str_replace('/','',$util->dateToThaiFormat($value->Txn_Date)).str_pad($value->Txn_No,5,'0',STR_PAD_LEFT)}}</td>
                                <td>{{$value->Recept_Fullname}}</td>
                                <td>{{$value->Member_Name}}</td>
                                <td>{{$value->room}}</td>
                                <td>{{$value->pretty}}</td>
                                <td>
                                    <a class="btn btn-primary btn-xs" href="{{Config::get('app.context')}}paymentdetail?txn={{$value->Txn_No}}&txndate={{$value->Txn_Date}}"><i class="fa fa-eye"></i> ดูรายการ</a>
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