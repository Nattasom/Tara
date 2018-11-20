@extends('layouts.default')
@section('title')
ชำระเงิน
@stop
@section('content')
    <div id="page-menu-key" data-menu="m_checkout" data-sub="" data-url="checkoutlist"></div>
    <!-- Page Header-->
    <div class="page-header no-margin-bottom">
        <div class="container-fluid">
            <h2 class="h5 no-margin-bottom">สถานะการใช้งาน</h2>
        </div>
    </div>
    <!-- Breadcrumb-->
    <div class="container-fluid">
        <ul class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="#">เช็คเอาท์</a>
            </li>
            <li class="breadcrumb-item active">ค้างชำระเงิน</li>
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
                                            <label class="col-sm-4 form-control-label">ค้นหาสมาชิก</label>
                                            <div class="col-sm-8">
                                                <div class="input-group">
                                                    <input type="text" class="form-control readonly" name="member_code">
                                                    <input type="hidden"  name="member_id">
                                                    <div class="input-group-append">
                                                        <button type="button" data-toggle="modal" data-target="#memberModal" class="btn btn-info"><i class="fa fa-search"></i></button>
                                                        <button type="button" class="btn btn-danger hide" id="clearParent"  onclick="clearInputMemberText(this,'#member_name,#member_amt,#member_suite')" ><i class="fa fa-trash"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-horizontal">
                                        <div class="form-group row">
                                            <label class="col-sm-4 form-control-label">ชื่อ </label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="member_name" name="member_name">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-horizontal">
                                        <div class="form-group row">
                                            <label class="col-sm-4 form-control-label">จำนวนเงินที่เหลือ</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control readonly" name="member_amt" id="member_amt"  />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-horizontal">
                                        <div class="form-group row">
                                            <label class="col-sm-4 form-control-label">Suite </label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control readonly" id="member_suite" name="member_suite" >
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
                                        <strong class="d-block">ค่าบริการ</strong>
                                    </div>
                                    <div class="block-body">
                                        <div class="form-horizontal">
                                            <div class="form-group row">
                                                <label class="col-sm-4 form-control-label">ค่าอาหารและเครื่องดื่ม</label>
                                                <div class="col-sm-7 text-right">
                                                    {{$util->numberFormat($prFood)}}
                                                </div>
                                                <div class="col-sm-1">
                                                บาท
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-4 form-control-label">ค่าพนักงานบริการ</label>
                                                <div class="col-sm-7 text-right">
                                                    {{$util->numberFormat($pr_sum)}}
                                                </div>
                                                <div class="col-sm-1">
                                                บาท
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-4 form-control-label">ค่าห้องพัก</label>
                                                <div class="col-sm-7 text-right">
                                                    {{$util->numberFormat($room_sum)}}
                                                </div>
                                                <div class="col-sm-1">
                                                บาท
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-4 form-control-label">ค่าบริการอื่นๆ</label>
                                                <div class="col-sm-7 text-right">
                                                    {{$util->numberFormat($other_charge)}}
                                                </div>
                                                <div class="col-sm-1">
                                                บาท
                                                </div>
                                            </div>
                                            <div class="line gap-bottom"></div>
                                            <div class="form-group row">
                                                <label class="col-sm-4 form-control-label">รวม</label>
                                                <div class="col-sm-7 text-right">
                                                    {{$util->numberFormat($prFood+$pr_sum+$room_sum+$other_charge)}}
                                                </div>
                                                <div class="col-sm-1">
                                                บาท
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-4 form-control-label">ชำระแล้ว</label>
                                                <div class="col-sm-7 text-right">
                                                    {{$util->numberFormat($pay)}}
                                                </div>
                                                <div class="col-sm-1">
                                                บาท
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <h5 class="col-sm-4 form-control-label">คงค้าง</h5>
                                                <div class="col-sm-7 text-right">
                                                    <h5>{{$util->numberFormat($debt)}}</h5>
                                                </div>
                                                <div class="col-sm-1">
                                                <h5>บาท</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="title">
                                        <strong class="d-block">การชำระเงิน</strong>
                                    </div>
                                    <div class="text-right">
                                        <h5>รวมชำระเพิ่ม
                                            <span class="text-success" id="pay_massage">0</span> บาท</h5>
                                        <h5>คงค้าง
                                            <span class="text-danger" id="debt_massage">{{$util->numberFormat($debt)}}</span> บาท</h5>
                                            <input type="hidden" id="hd_debt" name="sum_debt" value="{{$debt}}" />
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <div class="gap-bottom">
                                                <input id="chk_mas_cash" value="T" name="chk_mas_cash" onclick="checkPay(this)" type="checkbox" value="">
                                                <label for="chk_mas_cash"> เงินสด</label>
                                                <div class="form-inline">
                                                    <div class="form-group">
                                                        <input type="number" class="form-control hide" onblur="calculatePayment()" placeholder="จำนวนเงิน" id="mas_cash" name="mas_cash" step="100" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="gap-bottom">
                                                <input id="chk_mas_credit" value="T" name="chk_mas_credit" onclick="checkPay(this)"  type="checkbox" value="">
                                                <label for="chk_mas_credit"> บัตรเครดิต</label>
                                                <div class="form-inline size-xs">
                                                    <div class="form-group">
                                                        <input type="number" class="form-control mr-sm-3 hide"  placeholder="เลขบัตร" id="mas_credit_no" name="mas_credit_no" step="100" />
                                                    </div>
                                                    <div class="form-group">
                                                        <select class="form-control mr-sm-3 hide" id="mas_credit_type" name="mas_credit_type">
                                                            @foreach($credit_type as $key=>$value)
                                                            <option value="{{$value->Ref_Code}}">{{$value->Ref_Desc}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <input type="number" class="form-control hide" onblur="calculatePayment()" placeholder="จำนวนเงิน" id="mas_credit" name="mas_credit" step="100" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="gap-bottom hide" id="mas_member_group">
                                                <input id="chk_mas_member" value="T" name="chk_mas_member" onclick="checkPay(this)"  type="checkbox" value="">
                                                <label for="chk_mas_member"> หักจากสมาชิก</label>
                                                <div class="form-inline">
                                                    <div class="form-group">
                                                        <input type="number" class="form-control hide" onblur="checkMember(this)" placeholder="จำนวนเงิน" id="mas_member" name="mas_member" step="100" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="title">
                                        <strong class="d-block">ยอดชำระเงินสดและบัตรเครดิต</strong>
                                    </div>
                                    <div class="block-body">
                                        <div class="form-horizontal">
                                            <div class="form-group row">
                                                <div class="col-sm-2"></div>
                                                <label class="col-sm-3 form-control-label">เงินสด</label>
                                                <div class="col-sm-4 text-right">
                                                    <p class="form-control-static" id="total_cash_amt">0</p>
                                                </div>
                                                <div class="col-sm-3 ">
                                                    <p class="form-control-static">บาท</p>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-2"></div>
                                                <label class="col-sm-3 form-control-label">บัตรเครดิต</label>
                                                <div class="col-sm-4 text-right">
                                                    <p class="form-control-static" id="total_credit_amt">0</p>
                                                </div>
                                                <div class="col-sm-3 ">
                                                    <p class="form-control-static">บาท</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <h5>รวมยอดชำระจริง
                                            <span class="text-success" id="total_pay_amt">0</span> บาท</h5>
                                        <h5>เงินทอน
                                            <span class="" id="change_payment">0</span> บาท</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="line gap-bottom"></div>
                            <div class="text-center">
                                {{ csrf_field() }}
                                <button type="button" onclick="doAction('payment')" class="btn btn-success">รับชำระเงิน</button>
                                <a href="{{Config::get('app.context')}}paymentdebtlist" class="btn btn-secondary">ยกเลิก</a>
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
        
        var debt_mas = $("#debt_massage").text().replace(',','');

        if(debt_mas!=0){
            $.alert({
                theme:'dark',
                type:'red',
                title:'ผิดพลาด',
                content:'ยังชำระยอดเงินไม่ครบถ้วน'
            });
            return false;
        }

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
        var debtTotal = parseInt($("#hd_debt").val());

        


        var masCash = $("#mas_cash").val()=="" ? 0 : $("#mas_cash").val();
        var masCredit = $("#mas_credit").val()=="" ? 0 : $("#mas_credit").val();
        var masMember = $("#mas_member").val()=="" ? 0 : $("#mas_member").val();

        var masSum = debtTotal;
        var masPay = 0;
        var masDebtTotal = 0;
        masPay += parseInt(masCash)+parseInt(masCredit)+parseInt(masMember);
        masDebtTotal = masSum-masPay;
        if(masPay > masSum){
            masDebtTotal = 0;
            change_payment += masPay - masSum;
        }
        $("#pay_massage").text(addCommas(masPay));
        $("#debt_massage").text(addCommas(masDebtTotal));

        //split credit and cash
        var sumCash = parseInt(masCash);
        var sumCredit = parseInt(masCredit);
        $("#total_cash_amt").text(addCommas(sumCash));
        $("#total_credit_amt").text(addCommas(sumCredit));
        $("#total_pay_amt").text(addCommas(sumCash+sumCredit));
        // $("#all_total").text(addCommas(parseInt(prFood)+sum_pr+sum_room+parseInt(other_service)));
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
