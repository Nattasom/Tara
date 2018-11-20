<div class="loading-box hide"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i><br/><br/>Loading...</div>
                                    <div class="table-responsive"> 
                                        <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                            <th>วันที่</th>
                                            <th>พนักงาน</th>
                                            <th>รอบ</th>
                                            <th>ค่าตอบแทน</th>
                                            <th>ค่าการ์ด/แม่บ้าน</th>
                                            <th>ค่าเสริมสวย</th>
                                            <th>หักหนี้ค้างชำระ</th>
                                            <th>รับแล้ว</th>
                                            <!-- <th>จ่ายค่าตอบแทน</th> -->
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($pretty_list1 as $key=>$value)
                                                <tr>
                                                    
                                                    <td>{{$util->dateToThaiFormat($value->Txn_Date)}}</td>
                                                    <td>{{$value->Angel_ID}}</td>
                                                    <td>{{$value->Round}}</td>
                                                    <td>{{$util->numberFormat($value->Wage_Amt)}}</td>
                                                    <td>{{$util->numberFormat($value->Comm)}}</td>
                                                    <td>{{$util->numberFormat($value->Makeup)}}</td>
                                                    <td>{{$util->numberFormat($value->Debt)}}</td>
                                                    <td>
                                                        {{$util->numberFormat($value->Receive)}}
                                                    </td>
                                                    <!-- <td>
                                                    <a href="{{Config::get('app.context')}}prettywage/{{$value->SysAngel_ID}}?txndate={{$value->Txn_Date}}" class="btn btn-success btn-xs"><i class="fa fa-usd"></i></a>
                                                    </td> -->
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        </table>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="paging-detail-text">{{$paging1->paging_detail}}</div>
                                        </div>
                                        <div class="col-md-7 text-md-right">
                                            <nav >
                                                <ul class="pagination">
                                                    {!!$paging1->renderHtml()!!}
                                                </ul>
                                            </nav>
                                        </div>
                                    </div> 