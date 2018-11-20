@extends('layouts.default')
@section('title')
จ่ายค้างชำระสมาชิก
@stop
@section('content')
    <div id="page-menu-key" data-menu="m_member" data-sub="member_ddl" data-url="memberdebt" ></div>
    <!-- Page Header-->
    <div class="page-header no-margin-bottom">
        <div class="container-fluid">
        <h2 class="h5 no-margin-bottom">จ่ายค้างชำระสมาชิก</h2>
        </div>
    </div>
    <!-- Breadcrumb-->
    <div class="container-fluid">
        <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="member">สมาชิก</a></li>
        <li class="breadcrumb-item active">จ่ายค้างชำระสมาชิก</li>
        </ul>
    </div>

    <section class="no-padding-top">
        <div class="container-fluid">
            <div class="block">
                <div class="block-body">
                    <form id="mainForm" class="form-horizontal" method="post" action="" novalidate>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">รหัสสมาชิก <sup class="text-danger">*</sup></label>
                            <div class="col-sm-6">
                            <input type="text" class="form-control" name="member_no" value="{{$member->Member_ID}}" readonly>
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">ชื่อ <sup class="text-danger">*</sup></label>
                            <div class="col-sm-6">
                            <input type="text" class="form-control" name="member_name" value="{{$member->First_Name}}"  readonly>
                            <input type="hidden" name="recept_id" value="{{$member->SysReception_ID}}" />
                            </div>
                        </div>
                        <div class="title"><strong class="d-block">ข้อมูลค้างชำระ</strong></div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">ยอดเงิน</label>
                            <div class="col-sm-2">
                                <input type="number" class="form-control " id="member_price" name="" value="{{$member_app->Add_Credit}}"  readonly>
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">Whisky</label>
                            <div class="col-sm-6">
                                <div class="form-inline">
                                    <div class="form-group">
                                        <label class="control-label mr-sm-3">Gold</label>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control mr-sm-3" style="width:50px;" value="{{$util->numberFormat($member_app->Add_Whisky)}}" name="" readonly/>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label mr-sm-3">Black</label>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control mr-sm-3" style="width:50px;" value="{{$util->numberFormat($member_app->Add_Whisky2)}}" name="" readonly/>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label mr-sm-3">Beer</label>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control mr-sm-3" style="width:50px;" value="{{$util->numberFormat($member_app->Add_Beer)}}" name="" readonly/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">Suite</label>
                            <div class="col-sm-2">
                                <input type="number" class="form-control " name="" value="{{$member_app->Add_Suite}}" readonly >
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="title">
                            <strong class="d-block">การชำระเงิน</strong>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-4">
                                        <input id="chk_mem_cash" value="T" name="chk_mem_cash" onclick="checkPay(this)" type="checkbox" value="">
                                        <label for="chk_mem_cash"> เงินสด</label>
                                        <input type="number" class="form-control hide" onblur="calculatePayment()" placeholder="จำนวนเงิน" id="member_cash" name="member_cash" step="100" />
                                    </div>
                                    <div class="col-md-4">
                                        <input id="chk_mem_credit" value="T" name="chk_mem_credit" onclick="checkPay(this)" type="checkbox" value="">
                                        <label for="chk_mem_credit"> บัตรเครดิต</label>
                                        <input type="number" class="form-control hide" onblur="calculatePayment()" placeholder="จำนวนเงิน" id="member_credit" name="member_credit" step="100" />
                                    </div>
                                    <div class="col-md-4">
                                        <input id="chk_mem_debt" value="T" name="chk_mem_debt" onclick="checkPay(this)" type="checkbox" value="">
                                        <label for="chk_mem_debt"> ค้างชำระ</label>
                                        <input type="number" class="form-control hide" onblur="calculatePayment()" placeholder="จำนวนเงิน" id="member_debt" name="member_debt" step="100" />
                                    </div>
                                </div>
                                
                            </div>
                            <div class="col-md-6">
                                <div class="text-right">
                                    <h6>รวม <span id="sum_total">0</span> บาท</h6>
                                    <h5>รวมชำระเงิน
                                        <span class="text-success" id="pay_sum">0</span> บาท</h5>
                                    <h5>คงค้าง
                                        <span class="text-danger" id="debt_sum">{{$util->numberFormat($member_app->Add_Credit)}}</span> บาท</h5>
                                </div>
                            </div>
                        </div>
                        <div class="line"></div>
                        
                        {{ csrf_field() }}
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-8 ml-auto">
                                <input type="hidden" name="action" value="" />
                                <button type="button" onclick="doAction('save')" class="btn btn-primary">ชำระเงิน</button>
                                <a href="{{Config::get('app.context')}}member" class="btn btn-secondary">กลับ</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
        </div>
    </section>
@stop

@section('script')
<script>
    
    function doAction(action){
        $("#mainForm input[name='action']").val(action);
        $("#mainForm").submit();
    }
    function clickPrice(element){
        var val = $(element).attr("data-value");
        $("#member_price").val(val);
        calculatePayment();
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
    function calculatePayment(){
        var change_payment = 0;
        var total = parseInt($("#member_price").val());

        var cash = $("#member_cash").val()=="" ? 0 : $("#member_cash").val();
        var credit = $("#member_credit").val()=="" ? 0 : $("#member_credit").val();
        var debt = $("#member_debt").val()=="" ? 0 : $("#member_debt").val();
        var sumPay = parseInt(cash)+parseInt(credit)+parseInt(debt);
        var sumDebt = 0;
        if(sumPay > total){
            sumDebt = 0;
        }
        else{
            sumDebt = total- sumPay ;
        }
        $("#sum_total").text(addCommas(total));
        $("#pay_sum").text(addCommas(sumPay));
        $("#debt_sum").text(addCommas(sumDebt));
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
    $("#mainForm").validate({
        rules: {
            member_no: {
                required:true
            },
            member_name: {
                required:true
            },
            member_price:{
                required: function(element) {
                    var action = $("input[name='action']").val();
                    if(action=="topup"){
                        return true;
                    }else{
                        return false;
                    }
                }
            }
        },
            messages: {
                member_no: {
                    required:"กรุณากรอกข้อมูล"
                },
                member_name: {
                    required:"กรุณากรอกข้อมูล"
                },
                member_price:{
                    required:"กรุณากรอกข้อมูล"
                }
            },submitHandler: function(form) { //submit
            var debSum = $("#debt_sum").text().replace(',','');
            //check role checkbox
            if(parseInt(debSum) > 0){
                $.alert({
                    theme:'dark',
                    type:'red',
                    title:'ผิดพลาด',
                    content:'ยังชำระยอดเงินไม่ครบถ้วน'
                });
                return false;
            }
            form.submit();
         }
        });
</script>
@stop
