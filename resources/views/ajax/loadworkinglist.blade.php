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
                                            <th>รอบ</th>
                                            <th>เวลาขึ้น</th>
                                            <th>เวลาลง</th>
                                            <th>เวลาที่เหลือ</th>
                                            <th>โทรเตือน</th>
                                            <th>เวลาโทรล่าสุด</th>
                                            <th>โทร</th>
                                            <th width="180">จัดการ</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($pretty_list as $key=>$value)
                                            <tr class="pr-item" data-angel="{{$value->SysAngel_ID}}">
                                                <td class="room-no">{{$value->Room_No}}</td>
                                                <td class="pr-name">{{$value->Angel_ID}}: {{$value->Angel_Nick}}</td>
                                                <td class="room-type">{{$value->Room_Type_Desc}}</td>
                                                <td>{{$value->Reception_ID}}: {{$value->Recept_Nick}} 

                                                @if($flag_B1 || $flag_B2)
                                                <button type="button" onclick="updateRecept(this)"  data-id="{{$value->SysMassage_Angel_List_ID}}" class="btn btn-info btn-xs" title="เปลี่ยนเชียร์"><i class="fa fa-exchange"></i></button>
                                                @endif
                                                </td>
                                                <td>{{$value->Round}}</td>
                                                <td style="width:110px;">{{date("H:i",strtotime($value->Check_In_Time))}} 
                                                @if($flag_B1 || $flag_B2)
                                                <button class="btn btn-info btn-xs" data-pretty="{{$value->Angel_ID}}" data-id="{{$value->SysMassage_Angel_List_ID}}" data-date="{{date("Y-m-d",strtotime($value->Check_In_Time))}}" data-time="{{date("H:i",strtotime($value->Check_In_Time))}}"  onclick="editTimePretty(this)"><i class="fa fa-edit"></i></button>
                                                @endif
                                                </td>
                                                <td>{{date("H:i",strtotime($value->Limited_Time))}}</td>
                                                <td>
                                                @if($value->diff_minutes > 15)
                                                    <span class="text-success ">{{$util->minsToHourString($value->diff_minutes)}}</span>
                                                @elseif($value->diff_minutes >= 0)
                                                    <span class="text-warning">{{$util->minsToHourString($value->diff_minutes)}}</span>
                                                @else
                                                    <span class="text-danger {{ ($value->Warning_Flag=='N') ? "" : "animated-display" }}">{{$util->minsToHourString($value->diff_minutes)}}</span>
                                                @endif
                                                
                                                </td>
                                                <td>{!! ($value->Warning_Flag=='N') ? "<span class='label label-danger'>ห้ามโทร</span>":"-" !!}</td>
                                                <td>{{!empty($value->Last_Call_Time) ? date("H:i",strtotime($value->Last_Call_Time)):"-"}} {{($value->Call_Count > 0) ? "(".$value->Call_Count.")":""}}</td>
                                                <td>
                                                    <button class="btn btn-danger btn-xs " onclick="telSave({{$value->SysMassage_Angel_List_ID}})" type="button"  title="บันทึกเวลาโทร"><i class="fa fa-phone"></i></button>
                                                </td>
                                                <td>
                                                @if($flag_B1 || $flag_B2)
                                                    <a href="{{Config::get('app.context')}}checkin/edit?txn={{$value->Txn_No}}&txndate={{$value->Txn_Date}}" class="btn btn-primary btn-xs" title="จัดการห้อง"><i class="fa fa-edit"></i></a>
                                                    <a href="{{Config::get('app.context')}}checkout/paymentbill?txn={{$value->Txn_No}}&txndate={{$value->Txn_Date}}" target="_blank" class="btn btn-danger btn-xs" title="สรุปค่าใช้จ่าย"><i class="fa fa-usd"></i></a>
                                                    <button type="button" onclick="moveRoom(this)" data-roomno = "{{$value->Room_No}}" data-txn="{{$value->Txn_No}}" data-date="{{$value->Txn_Date}}" class="btn btn-default btn-xs" title="ย้ายห้อง"><i class="fa fa-sign-out"></i></button>
                                                    <button type="button" onclick="addPrettyTime(this)" data-round="{{$value->Round}}" data-id="{{$value->SysMassage_Angel_List_ID}}" class="btn btn-info btn-xs" title="ต่อเวลา"><i class="fa fa-clock-o"></i></button>
                                                    <button type="button" onclick="prettyCheckout(this)" data-id="{{$value->SysMassage_Angel_List_ID}}" data-name="{{$value->Angel_ID}}: {{$value->Angel_Nick}}" class="btn btn-success btn-xs btn-checkout" title="ลงเวลาเช็คเอาท์"><i class="fa fa-check"></i></button>
                                                    <button type="button" onclick="cancelBill(this)" data-txn="{{$value->Txn_No}}" data-date="{{$value->Txn_Date}}" class="btn btn-danger btn-xs" title="ยกเลิกรายการ"><i class="fa fa-close"></i></button>
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
                                            <!-- <th>เวลาลง</th>
                                            <th>เวลาที่เหลือ</th> -->
                                            <th>โทรเตือน</th>
                                            <th>เวลาโทรล่าสุด</th>
                                            <th>โทร</th>
                                            <th width="180">จัดการ</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($room_list as $key => $value)
                                            <tr >
                                                <td class="room-no">{{$value->Room_No}}</td>
                                                <td>{{$value->Reception_ID}}: {{$value->Recept_Nick}}</td>
                                                <td>{{date("H:i",strtotime($value->Check_In_Time))}} 
                                                @if($flag_B1 || $flag_B2)
                                                    <button class="btn btn-info btn-xs" data-room="{{$value->Room_No}}" data-id="{{$value->SysMassage_Room_List_ID}}" data-date="{{date("Y-m-d",strtotime($value->Check_In_Time))}}" data-time="{{date("H:i",strtotime($value->Check_In_Time))}}"  onclick="editTimeRoom(this)"><i class="fa fa-edit"></i></button>
                                                @endif
                                                </td>
                                                <!-- <td class="room-limit">{{date("H:i",strtotime($value->Limited_Time))}}</td>
                                                <td>
                                                    @if($value->diff_minutes > 15)
                                                        <span class="text-success ">{{$util->minsToHourString($value->diff_minutes)}}</span>
                                                    @elseif($value->diff_minutes >= 0)
                                                        <span class="text-warning">{{$util->minsToHourString($value->diff_minutes)}}</span>
                                                    @else
                                                        <span class="text-danger animated-display">{{$util->minsToHourString($value->diff_minutes)}}</span>
                                                    @endif
                                                </td> -->
                                                <td>{!! ($value->Warning_Flag=='N') ? "<span class='label label-danger'>ห้ามโทร</span>":"-" !!}</td>
                                                <td>{{!empty($value->Last_Call_Time) ? date("H:i",strtotime($value->Last_Call_Time)):"-"}} {{($value->Call_Count > 0) ? "(".$value->Call_Count.")":""}}</td>
                                                <td>
                                                    <button class="btn btn-danger btn-xs " onclick="telRoomSave({{$value->SysMassage_Room_List_ID}})" type="button"  title="บันทึกเวลาโทร"><i class="fa fa-phone"></i></button>
                                                </td>
                                                <td>
                                                @if($flag_B1 || $flag_B2)
                                                    <a href="{{Config::get('app.context')}}checkin/edit?txn={{$value->Txn_No}}&txndate={{$value->Txn_Date}}" class="btn btn-primary btn-xs" title="จัดการห้อง"><i class="fa fa-edit"></i></a>
                                                    <a href="{{Config::get('app.context')}}checkout/paymentbill?txn={{$value->Txn_No}}&txndate={{$value->Txn_Date}}" target="_blank" class="btn btn-danger btn-xs" title="สรุปค่าใช้จ่าย"><i class="fa fa-usd"></i></a>
                                                    <button type="button" onclick="moveRoom(this)" data-roomno = "{{$value->Room_No}}" data-txn="{{$value->Txn_No}}" data-date="{{$value->Txn_Date}}" class="btn btn-default btn-xs" title="ย้ายห้อง"><i class="fa fa-sign-out"></i></button>
                                                    <button type="button" onclick="addRoomTime(this)" data-id="{{$value->SysMassage_Room_List_ID}}" class="btn btn-info btn-xs" title="ต่อเวลา"><i class="fa fa-clock-o"></i></button>
                                                    <button type="button" onclick="roomCheckout(this)" data-id="{{$value->SysMassage_Room_List_ID}}" data-name="{{$value->Room_No}}" class="btn btn-success btn-xs" title="ลงเวลาเช็คเอาท์"><i class="fa fa-check"></i></button>
                                                    <button type="button" onclick="cancelBill(this)" data-txn="{{$value->Txn_No}}" data-date="{{$value->Txn_Date}}" class="btn btn-danger btn-xs" title="ยกเลิกรายการ"><i class="fa fa-close"></i></button>
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