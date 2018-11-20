@extends('layouts.default')
@section('title')
สถานะการใช้งาน
@stop
@section('content')
    <div id="page-menu-key" data-menu="m_checkout" data-sub="" data-url="checkoutlist" ></div>
    <!-- Page Header-->
    <div class="page-header no-margin-bottom">
        <div class="container-fluid">
        <h2 class="h5 no-margin-bottom">สถานะการใช้งาน</h2>
        </div>
    </div>
    <!-- Breadcrumb-->
    <div class="container-fluid">
        <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">สถานะการใช้งาน</a></li>
        <li class="breadcrumb-item active">รายการสถานะการใช้งาน</li>
        </ul>
        <button class="btn btn-primary " id="rfid-read" style="position: absolute;
    right: 25px;
    top: auto;
    margin-top: -15px;" ><i class="fa fa-barcode fa-2x"></i></button>
    </div>
    <section class="no-padding-top">
        <div class="container-fluid">
            
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" ><a class="active" href="#working"  role="tab" data-toggle="tab">รายการอยู่ระหว่างการใช้งาน</a></li>
                <li role="presentation"><a href="#payment"  role="tab" data-toggle="tab">รายการรอชำระเงิน</a></li>
                
            </ul>
            
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="working">
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
                </div>
                <div role="tabpanel" class="tab-pane" id="payment">
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
                </div>
            </div>

           
            
        </div>
    </section>
    <input type="text" class="pretty-reader" name='rfid' />

    <div class="modal fade" id="addTimeModal" tabindex="-1" role="dialog" >
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" i>ต่อรอบ</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="formAddTime" class="form-horizontal" action="">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="" class="col-sm-5 control-label">ห้อง</label>
                                <div class="col-sm-7">
                                    <p id="lbl_at_room" class="form-control-static"></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="" class="col-sm-3 control-label">ประเภท</label>
                                <div class="col-sm-9">
                                    <p id="lbl_at_room_type" class="form-control-static"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="" class="col-sm-5 control-label">พนักงานบริการ</label>
                                <div class="col-sm-7">
                                    <p id="lbl_at_pr" class="form-control-static"></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="" class="col-sm-3 control-label">รอบเดิม</label>
                                <div class="col-sm-9">
                                    <p id="lbl_at_pr_round" class="form-control-static"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="" class="col-sm-5 control-label">รอบที่จะต่อ</label>
                                <div class="col-sm-7">
                                    <input type="number" step=".5" id="round_up" class="form-control readonly" name="round_up">
                                    <input type="hidden" name="update_id" id="update_id">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="" class="col-sm-3 control-label"></label>
                                <div class="col-sm-9">
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                
            </div>
            <div class="modal-footer">
                <button type="button" onclick="callAddPrettyTime(this)" class="btn btn-primary" >ยืนยัน</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
            </div>
            </div>
        </div>
    </div>
<div class="modal fade" id="receiptModal" tabindex="-1" role="dialog" >
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" i>เลือกเชียร์แขก</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div data-type="receipt" class="ajax-paging-block">
                    <div class="loading-box hide"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i><br/><br/>Loading...</div>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="hd_change_recept_id"  />
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editRoomTimeModal" tabindex="-1" role="dialog" >
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" i>แก้ไขเวลา</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="formRoomEditTime" class="form-horizontal" action="">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="" class="col-sm-5 control-label">ห้อง</label>
                                <div class="col-sm-7">
                                    <p id="lbl_edit_room" class="form-control-static"></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="" class="col-sm-3 control-label">เวลาเข้า</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static" id="lbl_edit_room_in"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label for="" class="col-sm-4 control-label">เพิม - ลด เวลาเข้า (นาที)</label>
                                <div class="col-sm-8">
                                    <input type="number" class="form-control readonly input-select" style="width:80px;" name="txt_edit_time" id="txt_edit_time" step="5" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="edit_id" id="hd_edit_room_id" />
                    <input type="hidden" name="edit_date" id="hd_edit_room_date" />
                </form>
                
            </div>
            <div class="modal-footer">
                <button type="button" onclick="callEditRoomTime(this)" class="btn btn-primary" >ยืนยัน</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
            </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editPrettyTimeModal" tabindex="-1" role="dialog" >
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" i>แก้ไขเวลา</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="formPrettyEditTime" class="form-horizontal" action="">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="" class="col-sm-5 control-label">พนักงาน</label>
                                <div class="col-sm-7">
                                    <p id="lbl_edit_pretty" class="form-control-static"></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="" class="col-sm-3 control-label">เวลาเข้า</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static" id="lbl_edit_pretty_in"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label for="" class="col-sm-4 control-label">เพิม - ลด เวลาเข้า (นาที)</label>
                                <div class="col-sm-8">
                                    <input type="number" class="form-control readonly input-select" style="width:80px;" name="txt_edit_time" id="txt_edit_time_pretty" step="5" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="edit_id" id="hd_edit_pretty_id" />
                    <input type="hidden" name="edit_date" id="hd_edit_pretty_date" />
                </form>
                
            </div>
            <div class="modal-footer">
                <button type="button" onclick="callEditPrettyTime(this)" class="btn btn-primary" >ยืนยัน</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
            </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addRoomTimeModal" tabindex="-1" role="dialog" >
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" i>ต่อเวลาห้อง</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="formRoomAddTime" class="form-horizontal" action="">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="" class="col-sm-5 control-label">ห้อง</label>
                                <div class="col-sm-7">
                                    <p id="lbl_rat_room" class="form-control-static"></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="" class="col-sm-3 control-label">เวลาสิ้นสุด</label>
                                <div class="col-sm-9">
                                    <p id="lbl_rat_room_limit" class="form-control-static"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="" class="col-sm-5 control-label">รอบที่จะต่อ</label>
                                <div class="col-sm-7">
                                    <input type="number" step=".5" id="round_up" class="form-control readonly" name="round_up">
                                    <input type="hidden" name="update_id" id="update_id">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="" class="col-sm-3 control-label"></label>
                                <div class="col-sm-9">
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                
            </div>
            <div class="modal-footer">
                <button type="button" onclick="callAddRoomTime(this)" class="btn btn-primary" >ยืนยัน</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
            </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="moveRoomModal" tabindex="-1" role="dialog" >
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" i>ย้ายห้อง</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-horizontal">
                        <input type="hidden" id="hd_move_from_txn" value="" />
                        <input type="hidden" id="hd_move_from_date" value="" />
                        <input type="hidden" id="hd_move_from_room" value="" />
                        <div class="form-group row">
                        <label class="col-sm-4 form-control-label">เลือกตึก</label>
                        <div class="col-sm-8">
                            <select class="form-control" id="building">
                                @foreach($buildinglist as $key=>$value)
                                    <option value="{{$value->building_id}}">{{$value->building_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div id="choose-room">
                    <div class="loading-box text-center hide"><i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i><br/><br/>Loading...</div>
                </div>
            </div>
            </div>
        </div>
    </div>
@stop

@section('script')
<script>
$(".pretty-reader").on("change",function(){
    getPretty($(this).val());
});
$("#rfid-read").on("click",function(){
    $(".pretty-reader").val('');
    $(".pretty-reader").focus();
});
$(document).ready(function(){
    $(".pretty-reader").focus();
    setInterval(loadWorkingList,60000);// 1 minutes reload
});
$(document).on("change","#building",function(){
    if($(this).val()!=""){
        loadRoom($(this).val());
    }
});
$(document).on("click",'div.room-click:not(.room-ss):not(.used):not(.clicked):not(.closed)',function(){
    var id = $(this).attr("data-id");
    var code = $(this).attr("data-code");
    var name = $(this).attr("data-name");
    $.confirm({
    theme: 'dark',
    type:'red',
    title: 'ยืนยันการย้ายห้อง',
    content: 'ต้องการย้ายห้องจาก '+$("#hd_move_from_room").val()+' ไปที่ห้อง '+code+' หรือไม่?',
    buttons: {
        ยืนยัน: 
        {
            btnClass: 'btn-primary',
            action:function () {
                confirmMoveRoom(id);
            }
        },
        ยกเลิก: {
            btnClass: ''
        }
    }
    });
});
$(document).on("click",'div.room-click.room-ss:not(.used):not(.clicked):not(.closed)',function(){
    console.log("ss");
    var id = $(this).attr("data-id");
    var code = $(this).attr("data-code");
    var name = $(this).attr("data-name");
    var parent = $(this).attr("data-parent");

    $.confirm({
    theme: 'dark',
    type:'red',
    title: 'ยืนยันการย้ายห้อง',
    content: 'ต้องการย้ายห้องจาก '+$("#hd_move_from_room").val()+' ไปที่ห้อง '+code+' หรือไม่?',
    buttons: {
        ยืนยัน: 
        {
            btnClass: 'btn-primary',
            action:function () {
                confirmMoveRoom(id);
            }
        },
        ยกเลิก: {
            btnClass: ''
        }
    }
    });
});
$(document).on("click",'div.room-click-suite:not(.used):not(.clicked):not(.closed)',function(){
    var id = $(this).attr("data-id");
    var code = $(this).attr("data-code");
    var name = $(this).attr("data-name");

    $.confirm({
    theme: 'dark',
    type:'red',
    title: 'ยืนยันการย้ายห้อง',
    content: 'ต้องการย้ายห้องจาก '+$("#hd_move_from_room").val()+' ไปที่ห้อง '+code+' หรือไม่?',
    buttons: {
        ยืนยัน: 
        {
            btnClass: 'btn-primary',
            action:function () {
                confirmMoveRoom(id);
            }
        },
        ยกเลิก: {
            btnClass: ''
        }
    }
    });
});
function editTimeRoom(element){
    var id = $(element).attr("data-id");
    var room = $(element).attr("data-room");
    var date = $(element).attr("data-date");
    var time = $(element).attr("data-time");
    $("#lbl_edit_room").text(room);
    $("#lbl_edit_room_in").text(time);
    $("#txt_edit_time").val('');
    $("#hd_edit_room_id").val(id);
    $("#hd_edit_room_date").val(id);
    $("#editRoomTimeModal").modal("show");
}
function editTimePretty(element){
    var id = $(element).attr("data-id");
    var pr = $(element).attr("data-pretty");
    var date = $(element).attr("data-date");
    var time = $(element).attr("data-time");
    $("#lbl_edit_pretty").text(pr);
    $("#lbl_edit_pretty_in").text(time);
    $("#txt_edit_time_pretty").val('');
    $("#hd_edit_pretty_id").val(id);
    $("#hd_edit_pretty_date").val(id);
    $("#editPrettyTimeModal").modal("show");
}
function loadRoom(building){
    var params = "building_id="+building;
    //console.log(params);
    var $loading = $("#choose-room .loading-box");
    $loading.removeClass("hide");
    var $box = $("#choose-room");
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
    $.ajax({
        url: "ajax/loadcheckinroom",
        type: "POST",
        data:params,
        success: function(data){
            $box.html(data);
            $loading.addClass("hide");
        }
    });
}
function confirmMoveRoom(to_room){
    var params = "txn="+$("#hd_move_from_txn").val()+"&date="+$("#hd_move_from_date").val()+"&to_room="+to_room;
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
    $.ajax({
        url: "ajax/moveroom",
        type: "POST",
        data:params,
        success: function(data){
            // console.log(data);
            if(data.status == "01"){ //success then reload
                $("#moveRoomModal").modal("hide");
                loadWorkingList();
            }else if(data.status =="02"){
                $.alert({
                    theme:'dark',
                    type:'red',
                    title:'ผิดพลาด',
                    content:'ไม่สามารถย้ายห้องสูทไปห้องประเภทอื่นได้'
                });
            }else if(data.status =="03"){
                $.alert({
                    theme:'dark',
                    type:'red',
                    title:'ผิดพลาด',
                    content:'ไม่สามารถย้ายห้องสูทได้เนื่องจากเกินเวลา 15 นาทีแล้ว'
                });
            }
            else{
                console.log(data.status);
            }
        }
    });
}
function moveRoom(element){
    var room = $(element).attr("data-roomno");
    var txn = $(element).attr("data-txn");
    var date = $(element).attr("data-date");
    $("#hd_move_from_txn").val(txn);
    $("#hd_move_from_date").val(date);
    $("#hd_move_from_room").val(room);
    loadRoom($("#building").val());
    $("#moveRoomModal").modal("show");
    
}
function toggleSubSuite(element){
    if($("#sub_suite").hasClass("hide")){
        $(element).text("ปิด");
    }else{
        $(element).text("แบ่งห้องสูท");
    }
    $("#sub_suite").toggleClass("hide");
}
function getPretty(card_no){
    var params = "card="+card_no;
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
    $.ajax({
        url: "ajax/getprettybycard",
        type: "POST",
        data:params,
        success: function(data){
            if(data.status=="01"){
                var id = data.id;
                var $tr = $("tr.pr-item[data-angel='"+id+"']");
                if($tr.length > 0){
                    $tr.find(".btn-checkout").trigger("click");
                }
            }
        }
    });
}
function roomCheckout($this){
    var data_name = $($this).attr("data-name");
    var data_id = $($this).attr("data-id");
    $.confirm({
        theme: 'dark',
        type:'red',
        title: 'ยืนยันการเช็คเอาท์',
        content: 'ยืนยันเช็คเอาท์ห้อง: '+data_name+' หรือไม่ ?',
        buttons: {
            ยืนยัน: 
            {
                btnClass: 'btn-primary',
                action:function () {
                    //checkout
                    callRoomCheckout(data_id);
                }
            },
            ยกเลิก: {
                btnClass: ''
            }
        }
    });
}
function callRoomCheckout(id){
    var params = "id="+id;
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
    $.ajax({
        url: "ajax/roomcheckout",
        type: "POST",
        data:params,
        success: function(data){
            // console.log(data);
            if(data.status == "01"){ //success then reload
                loadWorkingList();
            }else{
                console.log("something wrong");
            }
        }
    });
}
function addRoomTime(element){
    var $parent = $(element).parents("tr");
    var id= $(element).attr("data-id");
    var room_no = $parent.find(".room-no").text();
    var limit = $parent.find(".room-limit").text();
    //setting value
    $("#lbl_rat_room").text(room_no);
    $("#lbl_rat_room_limit").text(limit);
    $("#formRoomAddTime #round_up").val("0.5");
    $("#formRoomAddTime #update_id").val(id);

    $("#addRoomTimeModal").modal("show");
}
function callEditRoomTime(element){
    var params = $("#formRoomEditTime").serialize();
    $(element).prop("disabled",true);
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
    $.ajax({
        url: "ajax/roomedittime",
        type: "POST",
        data:params,
        success: function(data){
            // console.log(data);
            if(data.status == "01"){ //success then reload
                loadWorkingList();
            }else{
                console.log("something wrong");
            }
            $(element).prop("disabled",false);
            $("#editRoomTimeModal").modal("hide");
        }
    });
}
function callEditPrettyTime(element){
    var params = $("#formPrettyEditTime").serialize();
    $(element).prop("disabled",true);
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
    $.ajax({
        url: "ajax/prettyedittime",
        type: "POST",
        data:params,
        success: function(data){
            // console.log(data);
            if(data.status == "01"){ //success then reload
                loadWorkingList();
            }else{
                console.log("something wrong");
            }
            $(element).prop("disabled",false);
            $("#editPrettyTimeModal").modal("hide");
        }
    });
}
function callAddRoomTime(element){
    var params = $("#formRoomAddTime").serialize();
    $(element).prop("disabled",true);
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
    $.ajax({
        url: "ajax/roomaddtime",
        type: "POST",
        data:params,
        success: function(data){
            // console.log(data);
            if(data.status == "01"){ //success then reload
                loadWorkingList();
            }else{
                console.log("something wrong");
            }
            $(element).prop("disabled",false);
            $("#addRoomTimeModal").modal("hide");
        }
    });
}
function addPrettyTime(element){
    var $parent = $(element).parents("tr");
    var round = $(element).attr("data-round");
    var id= $(element).attr("data-id");
    var room_no = $parent.find(".room-no").text();
    var pr_name = $parent.find(".pr-name").text();
    var room_type =$parent.find(".room-type").text();
    //setting value
    $("#lbl_at_room").text(room_no);
    $("#lbl_at_room_type").text(room_type);
    $("#lbl_at_pr").text(pr_name);
    $("#lbl_at_pr_round").text(round);
    $("#formAddTime #round_up").val("0.5");
    $("#formAddTime #update_id").val(id);

    $("#addTimeModal").modal("show");
    
}
function callAddPrettyTime(element){
    var params = $("#formAddTime").serialize();
    $(element).prop("disabled",true);
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
    $.ajax({
        url: "ajax/prettyaddtime",
        type: "POST",
        data:params,
        success: function(data){
            // console.log(data);
            if(data.status == "01"){ //success then reload
                loadWorkingList();
            }else{
                console.log("something wrong");
            }
            $(element).prop("disabled",false);
            $("#addTimeModal").modal("hide");
        }
    });
}
function prettyCheckout($this){
    var data_name = $($this).attr("data-name");
    var data_id = $($this).attr("data-id");
    $.confirm({
        theme: 'dark',
        type:'red',
        title: 'ยืนยันการเช็คเอาท์',
        content: 'ยืนยันเช็คเอาท์พนักงาน '+data_name+' หรือไม่ ?',
        buttons: {
            ยืนยัน: 
            {
                btnClass: 'btn-primary',
                keys: ['enter'],
                action:function () {
                    //checkout
                    callPrettyCheckout(data_id);
                }
            },
            ยกเลิก: {
                btnClass: ''
            }
        }
    });
}
function updateRecept(element){
    var id = $(element).attr("data-id");
    $("#hd_change_recept_id").val(id);
    $("#receiptModal").modal("show");

}
$(document).on("click","#receiptModal .selected-item",function(){
    var data_id = $(this).attr("data-id");
    var data_code = $(this).attr("data-code");
    var name = $(this).find(".text-name").text();
        $.confirm({
        theme: 'dark',
        type:'red',
        title: 'ยืนยันการเปลี่ยนเชียร์',
        content: 'ต้องการเปลี่ยนเชียร์แขกเป็น '+name+" หรือไม่?",
        buttons: {
            ยืนยัน: 
            {
                btnClass: 'btn-primary',
                action:function () {
                    callUpdateRecept($("#hd_change_recept_id").val(),data_id);
                    $("#receiptModal").modal("hide");
                }
            },
            ยกเลิก: {
                btnClass: ''
            }
        }
        });
    });
function callUpdateRecept(id,recept){
    var params = "id="+id+"&recept="+recept;
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
    $.ajax({
        url: "ajax/updateRecept",
        type: "POST",
        data:params,
        success: function(data){
            // console.log(data);
            if(data.status == "01"){ //success then reload
                loadWorkingList();
            }else{
                console.log("something wrong");
            }
        }
    });
}
$("#receiptModal").on("hide.bs.modal",function(){
    $("#hd_change_recept_id").val("");
});
$("#receiptModal").on("show.bs.modal",function(){
        loadDataReceipt(1,10);
    });
function gotopage(element){
    var $parent = $(element).parents(".ajax-paging-block");
    var type = $parent.attr("data-type");
    if(type=="receipt"){
        loadDataReceipt($(element).attr("data-page"),10);
    }
}
function loadDataReceipt(page,perpage){
    var params = "page="+page+"&perpage="+perpage;
    //console.log(params);
    var $loading = $("#receiptModal .ajax-paging-block .loading-box");
    $loading.removeClass("hide");
    var $box = $("#receiptModal .ajax-paging-block");
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
    $.ajax({
        url: "ajax/loadreceiptpopup",
        type: "POST",
        data:params,
        success: function(data){
            $box.html(data);
            $loading.addClass("hide");
        }
    });
}
function cancelBill(element){
    var data_txn = $(element).attr("data-txn");
    var data_date = $(element).attr("data-date");
    $.confirm({
        theme: 'dark',
        type:'red',
        title: 'ยืนยันการยกเลิก',
        content: 'ยืนยันการยกเลิกรายการนี้หรือไม่ ?',
        buttons: {
            ยืนยัน: 
            {
                btnClass: 'btn-primary',
                keys: ['enter'],
                action:function () {
                    //checkout
                    callCancelBill(data_txn,data_date);
                }
            },
            ยกเลิก: {
                btnClass: ''
            }
        }
    });
}
function callCancelBill(txn,date){
    var params = "txn="+txn+"&date="+date;
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
    $.ajax({
        url: "ajax/cancelBill",
        type: "POST",
        data:params,
        success: function(data){
            // console.log(data);
            if(data.status == "01"){ //success then reload
                loadWorkingList();
            }else{
                console.log("something wrong");
            }
        }
    });
}
function callPrettyCheckout(id){
    var params = "id="+id;
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
    $.ajax({
        url: "ajax/prettycheckout",
        type: "POST",
        data:params,
        success: function(data){
            // console.log(data);
            if(data.status == "01"){ //success then reload
                loadWorkingList();
            }else{
                console.log("something wrong");
            }
        }
    });
}
function loadWorkingList(){
    var params = "";
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
    $.ajax({
        url: "ajax/loadworkinglist",
        type: "POST",
        data:params,
        success: function(data){
            loadPaymentList();
            $("#working").html(data);
        }
    });
}
function loadPaymentList(){
    var params = "";
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
    $.ajax({
        url: "ajax/loadpaymentlist",
        type: "POST",
        data:params,
        success: function(data){
            $(".pretty-reader").focus();
            $("#payment").html(data);
        }
    });
}
function telSave(id){
    var params = "id="+id;
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
    $.ajax({
        url: "ajax/telsave",
        type: "POST",
        data:params,
        success: function(data){
            loadWorkingList();
        }
    });
}
function telRoomSave(id){
    var params = "id="+id;
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
    $.ajax({
        url: "ajax/telroomsave",
        type: "POST",
        data:params,
        success: function(data){
            loadWorkingList();
        }
    });
}
</script>
@stop
