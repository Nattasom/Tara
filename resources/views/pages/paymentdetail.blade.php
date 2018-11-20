@extends('layouts.default')
@section('title')
ชำระเงิน
@stop
@section('content')
    <div id="page-menu-key" data-menu="m_payment" data-sub="checkout_ddl" data-url="paymentlist" ></div>
    <!-- Page Header-->
    <div class="page-header no-margin-bottom">
        <div class="container-fluid">
            <h2 class="h5 no-margin-bottom">ชำระเงิน</h2>
        </div>
    </div>
    <!-- Breadcrumb-->
    <div class="container-fluid">
        <ul class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="#">ข้อมูลการชำระเงิน</a>
            </li>
            <li class="breadcrumb-item active">ชำระเงิน</li>
        </ul>
    </div>
    <section class="no-padding-top">
        <div class="container-fluid">
            <form id="mainForm" method="post" action="">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="block">
                            <div class="title">
                                <strong class="d-block">ข้อมูลชำระเงิน</strong>
                            </div>
                            <div class="row">
                                <div class="col-md-1"></div>
                                <div class="col-md-6">
                                    <div class="block-body">
                                        <div class="form-horizontal">
                                            <div class="form-group row">
                                                <label class="col-sm-3 form-control-label">รายการเลขที่</label>
                                                <div class="col-sm-9">
                                                    <p class="form-control-static">{{$docNo}}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-1"></div>
                                <div class="col-md-6 ">
                                    <div class="block-body">
                                        <div class="form-horizontal">
                                            <div class="form-group row">
                                                <label class="col-sm-3 form-control-label">วันที่ทำรายการ</label>
                                                <div class="col-sm-9">
                                                    <p class="form-control-static">{{$strDate}}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="block-body">
                                        <div class="form-horizontal">
                                            <div class="form-group row">
                                                <label class="col-sm-3 form-control-label">เชียร์แขก</label>
                                                <div class="col-sm-9">
                                                    <p class="form-control-static">{{$strRecept}}</p>
                                                    <input type="hidden" name="recept_id" value="{{$recept_id}}" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="block">
                            <div class="title">
                                <strong class="d-block">สมาชิก</strong>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-horizontal">
                                        <div class="form-group row">
                                            <label class="col-sm-4 form-control-label">รหัสสมาชิก</label>
                                            <div class="col-sm-8">
                                                <p class="form-control-static">{{$member_code}}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-horizontal">
                                        <div class="form-group row">
                                            <label class="col-sm-4 form-control-label">ชื่อ </label>
                                            <div class="col-sm-8">
                                                <p class="form-control-static">{{$member_name}}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="block">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="title">
                                        <strong class="d-block">ค่าอาหารและเครื่องดื่ม</strong>
                                    </div>
                                    <div class="block-body">
                                        <div class="form-horizontal">
                                            <div class="form-group row">
                                                <label class="col-sm-4 form-control-label">ค่าอาหารและเครื่องดื่ม</label>
                                                <div class="col-sm-8">
                                                    <p class="form-control-static">{{$util->numberNoDigit($prFood)}}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="title">
                                        <strong class="d-block">รายการพนักงานบริการ</strong>
                                    </div>
                                    <div class="table-responsive gap-bottom">
                                        <table class="table table-hover table-xs">
                                            <thead>
                                                <tr>
                                                    <th>รายการ</th>
                                                    <th>ประเภท</th>
                                                    <th>เวลา</th>
                                                    <th>ราคา/รอบ</th>
                                                    <th>จำนวน</th>
                                                    <th>ส่วนลด</th>
                                                    <th>เพิ่ม</th>
                                                    <th>ค่าบริการ</th>
                                                    
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php($total = 0)
                                                @php($prCount=0)
                                                @foreach($prettylist as $key => $value)
                                                    <tr class="pretty-item" data-id="{{$value->SysMassage_Angel_List_ID}}">
                                                        <td>{{$value->Angel_ID}}/{{$value->Room_No}}</td>
                                                        <td>{{$value->Angel_Type_Code}}</td>
                                                        <td>{{date("H:i",strtotime($value->Check_In_Time))}}-{{date("H:i",strtotime($value->Check_Out_Time))}}</td>
                                                        <td>{{$util->numberFormat($value->Angel_Fee)}}
                                                        </td>
                                                        <td width="100">
                                                            {{$value->Round}}
                                                        </td>
                                                        <td>
                                                        {{$util->numberFormat($value->Angel_Discount_Amt)}}
                                                        </td> 
                                                        <td>{{$util->numberFormat($value->OT_Fee)}}</td>
                                                        <td>
                                                        {{$util->numberFormat(($value->Angel_Fee * $value->Round)-$value->Angel_Discount_Amt + $value->OT_Fee)}}
                                                        </td>
                                                        
                                                    </tr>
                                                    @php($total += ($value->Angel_Fee * $value->Round)-$value->Angel_Discount_Amt + $value->OT_Fee)
                                                    @php($prCount += 1)
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 text-right">
                                            <h5>รวมค่าบริการพนักงาน
                                                <span id="sum-pretty">{{$util->numberFormat($total)}}</span> บาท</h5>
                                        </div>
                                    </div>
    
                                    <div class="title">
                                        <strong class="d-block">รายการบริการห้องพัก</strong>
                                    </div>
                                    <div class="table-responsive gap-bottom">
                                        <table class="table  table-hover  table-xs">
                                            <thead>
                                                <tr>
                                                    <th>รายการ</th>
                                                    <th>ประเภท</th>
                                                    <th>เวลา</th>
                                                    <th>ส่วนลด</th>
                                                    <th>ค่าบริการ</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php($roomTotal=0)
                                                @php($roomCount=0)
                                                @php($suiteUse = 0)
                                                @foreach($roomlist as $key=>$value)
                                                    <tr class="room-item" data-id="{{$value->SysMassage_Room_List_ID}}" data-suite="{{$value->Suite_Flag}}" data-start-rate = "{{$value->Rate_Start}}" data-rate = "{{$value->Rate}}" data-over = "{{$value->Over_Round}}">
                                                        
                                                        <td>{{$value->Room_No}}</td>
                                                        <td>{{$value->Room_Type_Desc}}</td>
                                                        <td>{{$value->Time}}</td>
                                                        <td>
                                                            {{$util->numberFormat($value->Discount)}}
                                                        </td>
                                                        <td><span class="room-lbl-fee">{{$util->numberFormat($value->Fee-$value->Discount-$value->Suite_Amt)}}</span>
                                                        </td>
                                                    </tr>
                                                    @php($roomTotal +=($value->Fee-$value->Discount-$value->Suite_Amt))
                                                    @php($roomCount += $roomCount)
                                                    @php($suiteUse += $value->Suite_Used)
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="row gap-bottom" id="suite_member">
                                        <div class="col-md-7">&nbsp;</div>
                                        <div class="col-md-5 text-right">
                                            <label for="chk_suite"> หักสมาชิกห้องสูท</label>
                                            <p>{{$util->numberFormat($suiteUse)}}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 text-right">
                                            <h5>รวมค่าบริการห้องพัก
                                                <span id="lbl_sum_room">{{$util->numberFormat($roomTotal)}}</span> บาท</h5>
                                        </div>
                                    </div>
    
                                    <div class="title">
                                        <strong class="d-block">ค่าบริการเพิ่มเติม</strong>
                                    </div>
                                    <div class="block-body">
                                        <div class="form-horizontal">
                                            <div class="form-group row">
                                                <label class="col-sm-4 form-control-label">ค่าบริการอื่น ๆ</label>
                                                <div class="col-sm-8">
                                                <p class="form-control-static">{{$util->numberNoDigit($other_charge)}}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-horizontal">
                                            <div class="form-group row">
                                                <label class="col-sm-4 form-control-label">หมายเหตุ</label>
                                                <div class="col-sm-8">
                                                    <p class="form-control-static">{{$other_charge_remark}}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="title">
                                        <strong class="d-block">การชำระเงิน (อาหารและเครื่องดื่ม)</strong>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <div class="gap-bottom">
                                                <div class="form-horizontal">
                                                    <div class="form-group row">
                                                        <div class="col-sm-2"></div>
                                                        <label class="col-sm-3 form-control-label">เงินสด</label>
                                                        <div class="col-sm-4 text-right">
                                                            <p class="form-control-static" id="">{{$util->numberFormat($food_cash)}}</p>
                                                        </div>
                                                        <div class="col-sm-3 ">
                                                            <p class="form-control-static">บาท</p>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="col-sm-2"></div>
                                                        <label class="col-sm-3 form-control-label">บัตรเครดิต</label>
                                                        <div class="col-sm-4 text-right">
                                                            <p class="form-control-static" id="">{{$util->numberFormat($food_credit)}}</p>
                                                        </div>
                                                        <div class="col-sm-3 ">
                                                            <p class="form-control-static">บาท</p>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="col-sm-2"></div>
                                                        <label class="col-sm-3 form-control-label">หักสมาชิก</label>
                                                        <div class="col-sm-4 text-right">
                                                            <p class="form-control-static" id="">{{$util->numberFormat($food_member)}}</p>
                                                        </div>
                                                        <div class="col-sm-3 ">
                                                            <p class="form-control-static">บาท</p>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="col-sm-2"></div>
                                                        <label class="col-sm-3 form-control-label">ค้างชำระ</label>
                                                        <div class="col-sm-4 text-right">
                                                            <p class="form-control-static" id="">{{$util->numberFormat($food_debt)}}</p>
                                                        </div>
                                                        <div class="col-sm-3 ">
                                                            <p class="form-control-static">บาท</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
    
                                    <div class="title">
                                        <strong class="d-block">การชำระเงิน (ส่วนนวด)</strong>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                             <div class="gap-bottom">
                                                <div class="form-horizontal">
                                                    <div class="form-group row">
                                                        <div class="col-sm-2"></div>
                                                        <label class="col-sm-3 form-control-label">เงินสด</label>
                                                        <div class="col-sm-4 text-right">
                                                            <p class="form-control-static" id="">{{$util->numberFormat($mas_cash)}}</p>
                                                        </div>
                                                        <div class="col-sm-3 ">
                                                            <p class="form-control-static">บาท</p>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="col-sm-2"></div>
                                                        <label class="col-sm-3 form-control-label">บัตรเครดิต</label>
                                                        <div class="col-sm-4 text-right">
                                                            <p class="form-control-static" id="">{{$util->numberFormat($mas_credit)}}</p>
                                                        </div>
                                                        <div class="col-sm-3 ">
                                                            <p class="form-control-static">บาท</p>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="col-sm-2"></div>
                                                        <label class="col-sm-3 form-control-label">หักสมาชิก</label>
                                                        <div class="col-sm-4 text-right">
                                                            <p class="form-control-static" id="">{{$util->numberFormat($mas_member)}}</p>
                                                        </div>
                                                        <div class="col-sm-3 ">
                                                            <p class="form-control-static">บาท</p>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="col-sm-2"></div>
                                                        <label class="col-sm-3 form-control-label">ค้างชำระ</label>
                                                        <div class="col-sm-4 text-right">
                                                            <p class="form-control-static" id="">{{$util->numberFormat($mas_debt)}}</p>
                                                        </div>
                                                        <div class="col-sm-3 ">
                                                            <p class="form-control-static">บาท</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="line gap-bottom"></div>
                            <div class="text-center">
                                {{ csrf_field() }}
                                <a href="{{Config::get('app.context')}}checkout/receiptbill?txn={{$txn}}&txndate={{$txndate}}" target="_blank"  class="btn btn-primary">พิมพ์ใบเสร็จ</a>
                                <a href="{{Config::get('app.context')}}paymentlist" class="btn btn-secondary">ยกเลิก</a>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </form>
    
        </div>
    </section>
    <!-- Modal -->
<div class="modal fade" id="memberModal" tabindex="-1" role="dialog" >
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <h4 class="modal-title" i>เลือกสมาชิก</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <form id="formPopFilter" class="form-horizontal">
            <div class="row">
                <div class="col-md-4">
                    <div class="block-body">
                        <div class="form-group">
                        <label class="form-control-label">หมายเลขสมาชิก</label>
                        <input type="text" name="member_no" placeholder="" class="form-control">
                        </div>
                    </div>
                </div>  
                <div class="col-md-4">
                    <div class="block-body">
                        <div class="form-group">
                        <label class="form-control-label">ชื่อสมาชิก</label>
                        <input type="text" name="member_name" placeholder="" class="form-control">
                        </div>
                    </div>
                </div> 
                <div class="col-md-4">
                    <div class="block-body">
                        <div class="form-group">
                            <label class="form-control-label">&nbsp; </label>
                            <div>
                                <input type="button" value="ค้นหา" onclick="formMemberSearch()" id="btn-search" class="btn btn-info">
                            </div>
                            
                        </div>
                    </div>
                    
                </div>
            </div>
        </form>
        <div data-type="member" class="ajax-paging-block">
            <div class="loading-box hide"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i><br/><br/>Loading...</div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@stop

@section('script')
<script>
    $("#mainForm").submit(function(){
        var act = $("#mainForm input[name='action']").val();
        var $pretty = $("tr.pretty-item");
        if($pretty.length > 0){
            var i = 0;
            var strPrettySave = '';
            $pretty.each(function(){
                var id = $(this).attr("data-id");
                var round = $(this).find(".pretty-round").val();
                var sumitem = $(this).find(".pretty-sum-item").val();
                var disitem = $(this).find(".pretty-discount-item").val();
                var remark  = $(this).next().find(".discount-remark").val();
                if(i!=0){
                    strPrettySave += ',';
                }
                strPrettySave += id+'='+round+'='+sumitem+'='+disitem+'='+remark;
                i++;
            });
            $(this).find("input[name='pretty_save']").val(strPrettySave);
        }
        //room
        var $room = $("tr.room-item");
        if($room.length > 0){
            var j = 0;
            var strRoomSave ='';
            $room.each(function(){
                //discount only
                var id = $(this).attr("data-id");
                var fee = $(this).find(".room-fee").val();
                var disitem = $(this).find(".room-discount-item").val();
                var used = $(this).find(".room-suite-used").val();
                var remark = $(this).next().find(".discount-remark").val();
                if(j!=0){
                    strRoomSave += ',';
                }
                strRoomSave += id+'='+fee+'='+disitem+'='+remark+'='+used;
                j++;
            });
            $(this).find("input[name='room_save']").val(strRoomSave);
        }


        if(act=="payment"){ //required payment
            var debt_food = $("#debt_prFood").text().replace(',','');
            var debt_mas = $("#debt_massage").text().replace(',','');

            if(debt_food!=0 || debt_mas!=0){
                $.alert({
                    theme:'dark',
                    type:'red',
                    title:'ผิดพลาด',
                    content:'ยังชำระยอดเงินไม่ครบถ้วน'
                });
                return false;
            }
            
        }
        // console.log($(this).find("input[name='pretty_save']").val());
        // console.log($(this).find("input[name='room_save']").val());
        // return false;

    });
    function doAction(action){
        $("#mainForm input[name='action']").val(action);
        $("#mainForm").submit();
    }
    function checkPay(element){
        if($(element).is(":checked")){
            $(element).parent().find(".form-control").removeClass("hide");
        }else{
            $(element).parent().find(".form-control").val('');
            $(element).parent().find(".form-control").addClass("hide");
            calculatePayment();
        }
    }
    function checkDiscount(element){
        if($(element).is(":checked")){
            $(element).parents("tr").next().removeClass("hide");
        }else{
            $(element).parents("tr").next().find(".form-control").val('');
            $(element).parents("tr").next().find("input[type=radio]").prop("checked",false);
            $(element).parents("tr").next().addClass("hide");
            calculatePayment();
        }
    }
    function checkUnpaid(element){

    }
    function checkMember(element){
        var member_amt = $("#mas_member").val();
        var food_amt = $("#food_member").val();
        var value = 0;
        if(member_amt != ""){
            value += parseInt(member_amt);
        }
        if(food_amt!=""){
            value += parseInt(food_amt);
        }
        var memberVal = parseInt($("#member_amt").val());
        if(value > memberVal){
            $(element).val('');
            $.alert({
                    theme:'dark',
                    type:'red',
                    title:'ผิดพลาด',
                    content:'ยอดเงินสมาชิกไม่เพียงพอ'
                });
            return false;
        }
        calculatePayment();

    }
    function checkMemberSuite(element){
        var value = parseFloat($(element).val());
        var memberVal = parseFloat($("#member_suite").val());
        if(value > memberVal){
            $(element).val('');
            $.alert({
                    theme:'dark',
                    type:'red',
                    title:'ผิดพลาด',
                    content:'ยอดห้องสูทสมาชิกไม่เพียงพอ'
                });
            return false;
        }
        if($("tr.room-item[data-suite=1]").length > 0){
            var countLap = 0;
            $("tr.room-item[data-suite=1]").each(function(){
                var over = parseFloat($(this).attr("data-over"));
                if(over > 0){
                    countLap = countLap + 1 + over;
                }
                else{
                    countLap++;
                }
            });
            if(value > countLap){
                $(element).val('');
                $.alert({
                    theme:'dark',
                    type:'red',
                    title:'ผิดพลาด',
                    content:'ยอดห้องเกินจากรายการ'
                });
             calculatePayment();   
                return false;
            }
        }
calculatePayment();
    }
    function calculatePayment(){

        var change_payment = 0;
        //food
        var prFood = $("#prFood").val()=="" ? 0:$("#prFood").val();
        var foodCash = $("#food_cash").val()=="" ? 0 : $("#food_cash").val();
        var foodCredit = $("#food_credit").val()=="" ? 0 : $("#food_credit").val();
        var foodMember = $("#food_member").val()=="" ? 0 : $("#food_member").val();
        var foodDebt = $("#food_debt").val()=="" ? 0 : $("#food_debt").val();
        var foodDebtText = 0;
        var foodPayText = 0;
        foodPayText += parseInt(foodCash)+parseInt(foodCredit)+parseInt(foodMember)+parseInt(foodDebt);
        foodDebtText = prFood - foodPayText;
        if(foodPayText > prFood){
            foodDebtText = 0;
            change_payment += foodPayText - prFood;
        }
        $("#sum_prFood").text(addCommas(prFood));
        $("#pay_prFood").text(addCommas(foodPayText));
        $("#debt_prFood").text(addCommas(foodDebtText));

        //pretty
        var sum_pr = 0;
        var sum_room = 0;
        var $pretty = $("tr.pretty-item");
        if($pretty.length > 0){
            $pretty.each(function(){
                var dis_pr_amt = 0;
                var round = $(this).find(".pretty-round").val();
                var fee = $(this).find(".pretty-fee").val();
                var $discount = $(this).next();
                var $dis_amt = $discount.find(".discount-amt");
                var $disChk = $discount.find("input[name^='rad_pr_dis_']:checked");
                if($disChk.length > 0 && $dis_amt.val()!=""){ //is checked
                    var chkval = $disChk.val();
                    if(chkval=="C"){
                        dis_pr_amt = parseInt($dis_amt.val());
                    }else if(chkval=="P"){
                        dis_pr_amt = (round*fee) * parseInt($dis_amt.val())/100;
                    }
                    else if(chkval=="L"){
                        dis_pr_amt = (round*parseInt($dis_amt.val()));
                    }
                }
                $(this).find(".pretty-lbl-sum").text(addCommas((round*fee)-dis_pr_amt));
                $(this).find(".pretty-sum-item").val(round*fee);
                $(this).find(".pretty-discount-item").val(dis_pr_amt);
                sum_pr += (round*fee)-dis_pr_amt;
            });
            $("#sum-pretty").text(addCommas(sum_pr));
            $("#hd_sum_pretty").val(sum_pr);
        }

        //room
        var $room = $("tr.room-item");
        if($room.length > 0){
            var countCut =$("#suite_minus").val()=="" ? 0 : parseFloat($("#suite_minus").val());
            $room.each(function(){
                //discount only
                var dis_room_amt = 0;
                var $discount = $(this).next();
                var $dis_amt = $discount.find(".discount-amt");
                var $disChk = $discount.find("input[name^='rad_room_dis_']:checked");
                if($disChk.length > 0 && $dis_amt.val()!=""){ //is checked
                    var chkval = $disChk.val();
                    if(chkval=="C"){
                        dis_room_amt = parseInt($dis_amt.val());
                    }else if(chkval=="P"){
                        dis_room_amt = (round*fee) * parseInt($dis_amt.val())/100;
                    }
                }
                
                var fee = $(this).find(".room-fee").val();
                var suite = $(this).attr("data-suite");
                var row_suite_used = 0;
                if(suite=="1"){
                    var over = parseFloat($(this).attr("data-over"));
                    var start = parseInt($(this).attr("data-start-rate"));
                    var rate = parseInt($(this).attr("data-rate"));
                    if(countCut>0){
                        var rowlap = 1+over;
                        
                        if(countCut > rowlap){
                            fee = fee - start;
                            fee = fee - (over*rate);
                            countCut = countCut-rowlap;
                            row_suite_used = rowlap;
                        }else if(countCut == rowlap){
                            fee = 0;
                            // var diff = rowlap-countCut;
                            // if(diff == 0){
                            //     fee = fee - start;
                            // }
                            // else if(diff > 1){
                            //     fee = fee - start;
                            //     fee = fee - (diff*rate);
                            // }
                            // else if(diff < 1){
                            //     fee = fee - (start*diff);
                            // }
                            // else{
                            //     fee = fee - (start*countCut);
                            // }
                            row_suite_used = countCut;
                            countCut = 0;
                        }
                        else{
                            if(countCut < 1){
                                fee = fee - start*countCut;
                            }
                            else if(countCut == 1){
                                fee = fee - start;
                            }
                            else{
                                fee = fee - start;
                                fee = fee - (over*rate);
                            }
                            row_suite_used = countCut;
                            countCut = 0;
                        }
                        dis_room_amt = 0;
                    }
                }
                $(this).find(".room-lbl-fee").text(addCommas(parseInt(fee)-dis_room_amt));
                $(this).find(".room-discount-item").val(dis_room_amt);
                $(this).find(".room-suite-used").val(row_suite_used);
                sum_room += parseInt(fee)-dis_room_amt;
            });
        }
        $("#lbl_sum_room").text(addCommas(sum_room));
        $("#hd_sum_room").val(sum_room);

        //more
        var other_service = $("#other_service").val()=="" ? 0:$("#other_service").val();

        var masCash = $("#mas_cash").val()=="" ? 0 : $("#mas_cash").val();
        var masCredit = $("#mas_credit").val()=="" ? 0 : $("#mas_credit").val();
        var masMember = $("#mas_member").val()=="" ? 0 : $("#mas_member").val();
        var masDebt = $("#mas_debt").val()=="" ? 0 : $("#mas_debt").val();

        var masSum = sum_pr+sum_room+parseInt(other_service);
        var masPay = 0;
        var masDebtTotal = 0;
        masPay += parseInt(masCash)+parseInt(masCredit)+parseInt(masMember)+parseInt(masDebt);
        masDebtTotal = masSum-masPay;
        if(masPay > masSum){
            masDebtTotal = 0;
            change_payment += masPay - masSum;
        }
        $("#sum_massage").text(addCommas(masSum));
        $("#pay_massage").text(addCommas(masPay));
        $("#debt_massage").text(addCommas(masDebtTotal));

        //split credit and cash
        var sumCash = parseInt(foodCash)+parseInt(masCash);
        var sumCredit = parseInt(foodCredit)+parseInt(masCredit);
        $("#total_cash_amt").text(addCommas(sumCash));
        $("#total_credit_amt").text(addCommas(sumCredit));
        $("#total_pay_amt").text(addCommas(sumCash+sumCredit));
        $("#all_total").text(addCommas(parseInt(prFood)+sum_pr+sum_room+parseInt(other_service)));
        $("#change_payment").text(addCommas(change_payment));
    }

    $("#memberModal").on("show.bs.modal",function(){
        loadDataMember(1,10);
    });
    $(document).on("change","#mainForm input[name='member_code']",function(){
        console.log("val = "+$(this).val());
    });
    $(document).on("click","#memberModal .selected-item",function(){
        var data_id = $(this).attr("data-id");
        var data_code = $(this).attr("data-code");
        var bal = $(this).attr("data-bal");
        var suite = $(this).attr("data-suite");
        var name = $(this).find(".text-name").text();
        $("#clearParent").removeClass("hide");
        $("#mainForm input[name='member_code']").val(data_code);
        $("#mainForm input[name='member_id']").val(data_id);
        $("#mainForm input[name='member_name']").val(name);
        $("#mainForm input[name='member_amt']").val(bal);
        $("#mainForm input[name='member_suite']").val(suite);
        
        if(parseInt(bal) > 0){
            $("#mas_member_group").removeClass("hide");
            $("#food_member_group").removeClass("hide");
        }

        if(parseInt(suite) > 0){
            if($("tr.room-item[data-suite=1]").length > 0){
                $("#suite_member").removeClass("hide");
            }
        }
        $("#memberModal").modal("hide");

    });
    function clearInputMemberText(element,other){
        var arrID = other.split(',');
        $(element).parents(".input-group").find("input").val('');
        $(element).addClass("hide");

        for(var i = 0;i<arrID.length;i++){
            $(arrID[i]).val('');
        }
        $("#mas_member_group").addClass("hide");
        $("#food_member_group").addClass("hide");
        $("#suite_member").addClass("hide");
    }
    function formMemberSearch(){
        loadDataMember(1,10);
    }
    function gotopage(element){
        var $parent = $(element).parents(".ajax-paging-block");
        var type = $parent.attr("data-type");
        if(type=="member"){
            loadDataMember($(element).attr("data-page"),10);
        }
        
    }
    function loadDataMember(page,perpage){
        var params = $("#formPopFilter").serialize()+"&page="+page+"&perpage="+perpage;
        //console.log(params);
        var $loading = $("#memberModal .ajax-paging-block .loading-box");
        $loading.removeClass("hide");
        var $box = $("#memberModal .ajax-paging-block");
        $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
        });
        $.ajax({
            url: "ajax/loadmemberpopup",
            type: "POST",
            data:params,
            success: function(data){
                $box.html(data);
                $loading.addClass("hide");
            }
        });
    }
</script>
@stop
