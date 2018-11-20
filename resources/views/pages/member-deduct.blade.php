@extends('layouts.default')
@section('title')
ตัดยอดสมาชิก
@stop
@section('content')
    <div id="page-menu-key" data-menu="m_member" data-sub="member_ddl" data-url="member" ></div>
    <!-- Page Header-->
    <div class="page-header no-margin-bottom">
        <div class="container-fluid">
        <h2 class="h5 no-margin-bottom">ตัดยอดสมาชิก</h2>
        </div>
    </div>
    <!-- Breadcrumb-->
    <div class="container-fluid">
        <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="member">สมาชิก</a></li>
        <li class="breadcrumb-item active">ตัดยอดสมาชิก</li>
        </ul>
    </div>

    <section class="no-padding-top">
        <div class="container-fluid">
            <div class="block">
                <div class="block-body">
                    @if($status == "01")
                        <div class="alert alert-success alert-dismissible in" role="alert"> 
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button> 
                            <strong>เรียบร้อย !</strong> ตัดยอดสมาชิกเรียบร้อย
                        </div>
                    @endif
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
                        <div class="title"><strong class="d-block">ตัดยอด</strong></div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">จำนวนเงินคงเหลือ </label>
                            <div class="col-sm-6">
                                <input type="number" class="form-control"  id="" name="" value="{{$member->Credit_Amt}}"  readonly>
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">จำนวนเงินที่ตัดยอด </label>
                            <div class="col-sm-6">
                                <input type="number" class="form-control input-select"  id="member_price" name="member_price"  >
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">Whisky คงเหลือ </label>
                            <div class="col-sm-6">
                                <div class="form-inline">
                                    <div class="form-group">
                                        <label class="control-label mr-sm-3">Blue</label>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control mr-sm-3" style="width:50px;" value="{{$util->numberFormat($member->Whisky)}}" name="" readonly/>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label mr-sm-3">Black</label>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control mr-sm-3" style="width:50px;" value="{{$util->numberFormat($member->Whisky2)}}" name="" readonly/>
                                    </div>
                                    <!-- <div class="form-group">
                                        <label class="control-label mr-sm-3">Beer</label>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control mr-sm-3" style="width:50px;" value="{{$util->numberFormat($member->Beer)}}" name="" readonly/>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">Whisky ที่ตัดยอด</label>
                            <div class="col-sm-6">
                                <div class="form-inline">
                                     <div class="form-group">
                                        <label class="control-label mr-sm-3">Blue</label>
                                    </div>
                                    <div class="form-group">
                                        <input type="number" class="form-control mr-sm-3" style="width:50px;" name="whisky" />
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label mr-sm-3">Black</label>
                                    </div>
                                    <div class="form-group">
                                        <input type="number" class="form-control mr-sm-3" style="width:50px;" name="whisky2" />
                                    </div>
                                    <!-- <div class="form-group">
                                        <label class="control-label mr-sm-3">Beer</label>
                                    </div>
                                    <div class="form-group">
                                        <input type="number" class="form-control mr-sm-3" style="width:50px;" name="beer" />
                                    </div> -->
                                </div>
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">Suite คงเหลือ</label>
                            <div class="col-sm-2">
                                <input type="number" class="form-control " name="" value="{{$member->Suite}}" readonly >
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">Suite ที่ตัดยอด</label>
                            <div class="col-sm-2">
                                <input type="number" class="form-control " name="member_suite"  >
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">Suite 7/1 คงเหลือ</label>
                            <div class="col-sm-2">
                                <input type="number" class="form-control " name="" value="{{$member->Suite2}}" readonly >
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">Suite 7/1 ที่ตัดยอด</label>
                            <div class="col-sm-2">
                                <input type="number" class="form-control " name="member_suite2"  >
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">Spa คงเหลือ</label>
                            <div class="col-sm-2">
                                <input type="number" class="form-control " name="" value="{{$member->Suite3}}" readonly >
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">Spa ที่ตัดยอด</label>
                            <div class="col-sm-2">
                                <input type="number" class="form-control " name="member_suite3"  >
                            </div>
                        </div>
                        <div class="line"></div>
                        
                        {{ csrf_field() }}
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-8 ml-auto">
                                <input type="hidden" name="action" value="" />
                                <button type="button" onclick="doAction('save')" class="btn btn-primary">บันทึกข้อมูล</button>
                                <a href="{{Config::get('app.context')}}member" class="btn btn-secondary">กลับ</a>
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
    $("#mainForm input[name='recept_name']").val(name);
    
    $("#receiptModal").modal("hide");
        
    });
    $("#receiptModal").on("show.bs.modal",function(){
        loadDataReceipt(1,10);
    });
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
</script>
@stop
