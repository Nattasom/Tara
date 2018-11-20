<div class="row">
                        <div class="col-lg-12">
                            <div class="block paging-block">
                                <div class="ajax-paging-block">
                                    <div class="loading-box hide"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i><br/><br/>Loading...</div>
                                    <div class="table-responsive"> 
                                        <table class="table table-bordered table-striped table-hover">
                                        <thead>
                                            <tr>
                                            <th>หมายเลขห้อง</th>
                                            <th>พนักงาน</th>
                                            <th>ประเภทห้อง</th>
                                            <th>เชียร์แขก</th>
                                            <th>เวลาขึ้น</th>
                                            <th>เวลาลง</th>
                                            <th>เวลาที่ใช้ไป</th>
                                            <th>ชำระเงิน</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($pretty_checkout_list as $key=>$value)
                                            <tr>
                                                <td>{{$value->Room_No}}</td>
                                                <td>{{$value->Angel_ID}}: {{$value->Angel_Nick}}</td>
                                                <td>{{$value->Room_Type_Desc}}</td>
                                                <td>{{$value->Reception_ID}}: {{$value->Recept_Nick}}</td>
                                                <td>{{date("H:i",strtotime($value->Check_In_Time))}}</td>
                                                <td>{{date("H:i",strtotime($value->Limited_Time))}}</td>
                                                <td>
                                                {{$util->minsToHourString($value->Time_Amt)}}
                                                </td>
                                                <td>
                                                @if($flag_B1 || $flag_B2)
                                                    <a href="{{Config::get('app.context')}}checkout/paymentbill?txn={{$value->Txn_No}}&txndate={{$value->Txn_Date}}" target="_blank" class="btn btn-danger btn-xs" title="สรุปค่าใช้จ่าย"><i class="fa fa-usd"></i></a>
                                                    <a href="{{Config::get('app.context')}}payment?txn={{$value->Txn_No}}&txndate={{$value->Txn_Date}}" class="btn btn-success btn-xs" title="ชำระเงิน"><i class="fa fa-usd"></i></a>
                                                @else
                                                -
                                                @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                        </table>
                                    </div>
                                    <div class="line gap-bottom"></div>
                                   
                                </div>
                            </div>
                            <div class="block">
                                 <div class="table-responsive"> 
                                        <table class="table table-bordered table-striped table-hover">
                                        <thead>
                                            <tr>
                                            <th>สูท</th>
                                            <th>เชียร์แขก</th>
                                            <th>เวลาขึ้น</th>
                                            <th>เวลาลง</th>
                                            <th>เวลาที่ใช้ไป</th>
                                            <th>ชำระเงิน</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($room_checkout_list as $key => $value)
                                            <tr>
                                                <td>{{$value->Room_No}}</td>
                                                <td>{{$value->Reception_ID}}: {{$value->Recept_Nick}}</td>
                                                <td>{{date("H:i",strtotime($value->Check_In_Time))}}</td>
                                                <td>{{date("H:i",strtotime($value->Limited_Time))}}</td>
                                                <td>
                                                   {{$util->minsToHourString($value->Time_Amt)}}
                                                </td>
                                                <td>
                                                @if($flag_B1 || $flag_B2)
                                                    <a href="{{Config::get('app.context')}}payment?txn={{$value->Txn_No}}&txndate={{$value->Txn_Date}}" class="btn btn-success btn-xs" title="จัดการห้อง"><i class="fa fa-usd"></i></a>
                                                @else
                                                -
                                                @endif
                                                    
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        </table>
                                    </div>
                            </div>
                        </div>
                    </div>