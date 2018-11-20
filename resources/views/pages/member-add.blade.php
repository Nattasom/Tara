@extends('layouts.default')
@section('title')
สมัครสมาชิก
@stop
@section('content')
    <div id="page-menu-key" data-menu="m_member" data-sub="member_ddl" data-url="memberadd" ></div>
    <!-- Page Header-->
    <div class="page-header no-margin-bottom">
        <div class="container-fluid">
        <h2 class="h5 no-margin-bottom">สมัครสมาชิก</h2>
        </div>
    </div>
    <!-- Breadcrumb-->
    <div class="container-fluid">
        <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="member">สมาชิก</a></li>
        <li class="breadcrumb-item active">สมัครสมาชิก</li>
        </ul>
    </div>

    <section class="no-padding-top">
        <div class="container-fluid">
            <div class="block">
                <div class="block-body">
                    @if($status == "01")
                        <div class="alert alert-success alert-dismissible in" role="alert"> 
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button> 
                            <strong>เรียบร้อย !</strong> สมัครสมาชิกเรียบร้อย
                        </div>
                    @endif
                    <form id="mainForm" class="form-horizontal" method="post" action="" novalidate>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">รหัสสมาชิก <sup class="text-danger">*</sup></label>
                            <div class="col-sm-6">
                            <input type="text" class="form-control" name="member_no" maxlength="10" required>
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">ชื่อ <sup class="text-danger">*</sup></label>
                            <div class="col-sm-6">
                            <input type="text" class="form-control" name="member_name"  required>
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">นามสกุล </label>
                            <div class="col-sm-6">
                            <input type="text" class="form-control" name="member_lastname"  >
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">ที่อยู่ </label>
                            <div class="col-sm-6">
                            <textarea name="member_addr" id="" rows="5" class="form-control" ></textarea>
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">เบอร์โทร </label>
                            <div class="col-sm-6">
                            <input type="text" class="form-control" name="member_tel"  >
                            </div>
                        </div>
                        <!-- <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">วันเดือนปีเกิด </label>
                            <div class="col-sm-6">
                                <div class="input-group date"> 
                                    <input type="text" class="form-control readonly"  name="member_birth" value="" >
                                    <span class="input-group-btn"> 
                                        <button class="btn btn-info btn-datepicker"   type="button"><i class="fa fa-calendar"></i></button> 
                                    </span> 
                                </div>
                            </div>
                        </div> -->
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">พนักงานเชียร์แขก <sup class="text-danger">*</sup></label>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <input type="text" class="form-control readonly" name="recept_code">
                                    <input type="hidden"  name="recept_id">
                                    <div class="input-group-append">
                                        <button type="button" data-toggle="modal" data-target="#receiptModal" class="btn btn-info"><i class="fa fa-search"></i></button>
                                        <button type="button" class="btn btn-danger hide" id="clearParent"  onclick="clearInputGroupText_(this,'#recept_name')" ><i class="fa fa-trash"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">ชื่อพนักงานเชียร์แขก </label>
                            <div class="col-sm-6">
                                <p class="form-control-static" id="recept_name"></p>
                            <!-- <input type="text" class="form-control readonly" id="recept_name" name="recept_name" > -->
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">ประเภทเติมเงิน </label>
                            <div class="col-sm-6">
                                @foreach($member_type as $key=>$value)
                                    <button type="button" class="btn btn-default member-type" onclick="clickPrice(this)" data-value="{{$util->numberNoDigit($value->Member_Fee)}}" >{{$util->numberFormat($value->Member_Fee)}}</button>
                                @endforeach
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">จำนวนเงิน <sup class="text-danger">*</sup></label>
                            <div class="col-sm-6">
                                <input type="number" class="form-control input-select"  id="member_price" name="member_price"  required>
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">Whisky </label>
                            <div class="col-sm-6">
                                <div class="form-inline">
                                    <div class="form-group">
                                        <label class="control-label mr-sm-3">Blue</label>
                                    </div>
                                    <div class="form-group">
                                        <input type="number" step="1" class="form-control mr-sm-3" style="width:50px;" name="whisky" />
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label mr-sm-3">Black</label>
                                    </div>
                                    <div class="form-group">
                                        <input type="number" step="1" class="form-control mr-sm-3" style="width:50px;" name="whisky2" />
                                    </div>
                                    <!-- <div class="form-group">
                                        <label class="control-label mr-sm-3">Beer</label>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control mr-sm-3" style="width:50px;" name="beer" />
                                    </div> -->
                                </div>
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">Suite</label>
                            <div class="col-sm-2">
                                <input type="number" class="form-control " name="member_suite"  >
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">Suite 7/1</label>
                            <div class="col-sm-2">
                                <input type="number" class="form-control " name="member_suite2"  >
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">Spa</label>
                            <div class="col-sm-2">
                                <input type="number" class="form-control " name="member_suite3"  >
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
                                        <span class="text-danger" id="debt_sum">0</span> บาท</h5>
                                </div>
                            </div>
                        </div>
                        
                        {{ csrf_field() }}
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-8 ml-auto">
                                <button type="submit" class="btn btn-primary">บันทึก</button>
                                <a href="member" class="btn btn-secondary">กลับ</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
        </div>
    </section>
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
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
    </div>
@stop

@section('script')
<script>
    $(document).on("click","#receiptModal .selected-item",function(){
    var data_id = $(this).attr("data-id");
    var data_code = $(this).attr("data-code");
    var name = $(this).find(".text-name").text();
    $("#clearParent").removeClass("hide");
    $("#mainForm input[name='recept_code']").val(data_code+":"+name);
    $("#mainForm input[name='recept_id']").val(data_id);
    $("#recept_name").text(name);
    
    $("#receiptModal").modal("hide");
        
    });
    $("#receiptModal").on("show.bs.modal",function(){
        loadDataReceipt(1,10);
    });
    function clickPrice(element){
        var val = $(element).attr("data-value");
        if($("#member_price").val()!=""){
            var ex_price = parseInt($("#member_price").val());
            val = ex_price + parseInt(val);
        }
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
    $("#mainForm").validate({
        rules: {
            member_no: {
                required:true
            },
            member_name: {
                required:true
            },
            recept_code:{
                required:true
            },
            member_price:{
                required:true
            }
        },
            messages: {
                member_no: {
                    required:"กรุณากรอกข้อมูล"
                },
                member_name: {
                    required:"กรุณากรอกข้อมูล"
                },
                recept_code:{
                    required:"กรุณากรอกข้อมูล"
                },
                member_price:{
                    required:"กรุณากรอกข้อมูล"
                }
            },
            errorPlacement: function(error, element) {
            if (element.attr("name") == "recept_code") {
                var $ipt_group = element.parents(".input-group");
                error.insertAfter($ipt_group);

            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function(form) { //submit
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
