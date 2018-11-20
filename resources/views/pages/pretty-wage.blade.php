@extends('layouts.default')
@section('title')
จ่ายค่าตอบแทนพนักงานบริการ
@stop
@section('content')
    <div id="page-menu-key" data-menu="m_pay" data-sub="" data-url="prettywage" ></div>
    <!-- Page Header-->
    <div class="page-header no-margin-bottom">
        <div class="container-fluid">
        <h2 class="h5 no-margin-bottom">จ่ายค่าตอบแทนพนักงานบริการ</h2>
        </div>
    </div>
    <!-- Breadcrumb-->
    <div class="container-fluid">
        <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">พนักงาน</a></li>
        <li class="breadcrumb-item"><a href="{{Config::get('app.context')}}prettypay">จ่ายค่าตอบแทน</a></li>
        <li class="breadcrumb-item active">จ่ายค่าตอบแทนพนักงานบริการ</li>
        </ul>
    </div>
     <section class="no-padding-top">
        <div class="container-fluid">
            <div class="block">
                <div class="block-body">
                    @if($status == "01")
                        <div class="alert alert-success alert-dismissible in" role="alert"> 
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button> 
                            <strong>เรียบร้อย !</strong> จ่ายค่าตอบแทนเรียบร้อย
                        </div>
                    @endif
                    <div class="title"><strong>ข้อมูลพนักงานบริการ</strong></div>
                    <form id="mainForm" method="post" action="" class="form-horizontal">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <div class="col-sm-1"></div>
                                    <label class="col-sm-4 form-control-label">รหัสพนักงานบริการ </label>
                                    <div class="col-sm-7">
                                        {{$pretty->Angel_ID}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <div class="col-sm-1"></div>
                                    <label class="col-sm-4 form-control-label">วันที่ </label>
                                    <div class="col-sm-7">
                                    {{$txndate}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <div class="col-sm-1"></div>
                                    <label class="col-sm-4 form-control-label">ชื่อ </label>
                                    <div class="col-sm-7">
                                        {{$pretty->First_Name}} {{$pretty->Last_Name}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <div class="col-sm-1"></div>
                                    <label class="col-sm-4 form-control-label">ชื่อเล่น </label>
                                    <div class="col-sm-7">
                                        {{$pretty->Nick_Name}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <div class="col-sm-1"></div>
                                    <label class="col-sm-4 form-control-label">ประเภท </label>
                                    <div class="col-sm-7">
                                        {{$pretty->Angel_Type_Code}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <div class="col-sm-1"></div>
                                    <label class="col-sm-4 form-control-label"> </label>
                                    <div class="col-sm-7">
                                    
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="title"><strong>ค่าตอบแทน</strong></div>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th class="hide">เลือก</th>
                                        <th>ประเภทการชำระเงิน</th>
                                        <th>ห้องที่ให้บริการ</th>
                                        <th>เริ่มเวลา</th>
                                        <th>รวมเวลา</th>
                                        <th>รอบ</th>
                                        <th>ค่าตอบแทน</th>
                                        <th>ค่าการ์ด</th>
                                        <th>เพิ่ม</th>
                                        <th>คงเหลือ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $sumWage = 0;
                                        $sumComm = 0;
                                        $sumTotal = 0;
                                        $sumRound = 0;
                                        $sumOT = 0;
                                    ?>
                                    @php($i=0)
                                    @php($strAngel = "")
									@php($strOt = "")
                                    @foreach($listwage as $key=>$value)
                                        @if($i!=0)
                                            @php($strAngel .= ",")
											@php($strOt .=",")
                                        @endif
                                        <tr data-id="{{$value->SysMassage_Angel_List_ID}}" class="row-wage">
                                            <td class="hide">
                                                <input type="checkbox" class="chk-wage" onclick="calculateWage()" checked/>
                                            </td>
                                            <td>
                                                @php($textType = "")
                                                @if($value->Cash_Type > 0)
                                                    @php($textType .= "เงินสด")
                                                @endif
                                                @if($value->Credit_Type>0)
                                                    @if($textType!="")
                                                        @php($textType .= " และ ")
                                                    @endif
                                                    @php($textType .= "บัตรเครดิต")
                                                @endif
                                                @if($value->Member_Type>0)
                                                    @if($textType!="")
                                                        @php($textType .= " และ ")
                                                    @endif
                                                    @php($textType .= "หักสมาชิก")
                                                @endif
                                                @if($value->Unpaid_Type>0)
                                                    @if($textType!="")
                                                        @php($textType .= " และ ")
                                                    @endif
                                                    @php($textType .= "ค้างชำระ")
                                                @endif
                                                {{$textType}}
                                            </td>
                                            <td>{{$value->Room_No}}</td>
                                            <td>{{date("H:i",strtotime($value->Check_In_Time))}}</td>
                                            <td>{{$util->minsToHourString($value->Time_Amt)}}</td>
                                            <td class="text-center">{{$value->Round}}<input type="hidden" class="item-round" value="{{$value->Round}}" /></td>
                                            <td class="text-right">{{$util->numberFormat($value->Wage_Amt)}}<input type="hidden" class="item-wage" value="{{$value->Wage_Amt}}" /></td>
                                            <td class="text-right">{{$util->numberFormat($value->Comm)}}<input type="hidden" class="item-com" value="{{$value->Comm}}" /></td>
                                            <td class="text-right"><input type="input" class="form-control input-select item-ot" value="{{$value->OT_Wage}}" /></td>
                                            <td class="text-right"><span class="lbl-item-sum">{{$util->numberFormat($value->Wage_Amt - $value->Comm + $value->OT_Wage)}}</span><input type="hidden" class="item-sum" value="{{$value->Wage_Amt - $value->Comm + $value->OT_Wage}}" /></td>
                                            <?php
                                                $sumRound += $value->Round;
                                                $sumWage += ($value->Wage_Amt);
                                                $sumComm += $value->Comm;
                                                $sumOT += $value->OT_Wage;
                                                $sumTotal += ($value->Wage_Amt - $value->Comm + $value->OT_Wage);
                                                $i++;
                                                $strAngel .= ''.$value->SysMassage_Angel_List_ID;
												$strOt .= ''.$value->OT_Wage;
                                            ?>
                                        </tr>
                                    @endforeach

                                    <tr>
                                        <td colspan="4" class="text-right">รวม</td>
                                        <!-- <td class="text-center" id="lbl-sum-round">{{$util->numberFormat($sumRound,1)}}</td>
                                        <td class="text-right" id="lbl-sum-wage">{{$util->numberFormat($sumWage)}}</td>
                                        <td class="text-right" id="lbl-sum-com">{{$util->numberFormat($sumComm)}}</td>
                                        <td class="text-right" id="lbl-sum-ot">{{$util->numberFormat($sumOT)}}</td>
                                        <td class="text-right" id="lbl-sum-total">{{$util->numberFormat($sumTotal)}}</td> -->
                                        <td class="text-center" id="lbl-sum-round"></td>
                                        <td class="text-right" id="lbl-sum-wage"></td>
                                        <td class="text-right" id="lbl-sum-com"></td>
                                        <td class="text-right" id="lbl-sum-ot"></td>
                                        <td class="text-right" id="lbl-sum-total"></td>
                                    </tr>
                                    
                                </tbody>
                            </table>
                            <input type="hidden" name="angel_list" value="{{$strAngel}}" />
							<input type="hidden" name="ot_list" value="{{$strOt}}" />
                        </div>
                        <div class="line gap-bottom"></div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="title"><strong>หนี้ของพนักงาน</strong></div>
                                 <table class="table table-striped table-hover size-xs">
                                <thead>
                                    <tr>
                                        <th>รายการ</th>
                                        <th>ยอดค้าง</th>
                                        <th >หักเพิ่ม</th>
                                        <th>หักไว้แล้ว</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($pretty->Debt1 > 0)
                                        <tr class="debt-item">
                                            <td>ค่าประชุม</td>
                                            <td>{{$util->numberFormat($pretty->Debt1)}}</td>
                                            <td ><input type="number" class="form-control input-debt" id="pay_debt1" name="pay_debt1" onblur="calculateWage()" style="width:100px;"/></td>
                                            <td>{{(!is_null($perform) ? $util->numberFormat($perform->Pay_Debt1) : "0")}}</td>
                                        </tr>
                                    @endif
                                    @if($pretty->Debt2 > 0)
                                        <tr class="debt-item">
                                            <td>ค่าตะกร้า</td>
                                            <td>{{$util->numberFormat($pretty->Debt2)}}</td>
                                            <td ><input type="number" class="form-control input-debt" id="pay_debt2" name="pay_debt2" onblur="calculateWage()" style="width:100px;"/></td>
                                            <td>{{(!is_null($perform) ? $util->numberFormat($perform->Pay_Debt2) : "0")}}</td>
                                        </tr>
                                    @endif
                                    @if($pretty->Debt3 > 0)
                                        <tr class="debt-item">
                                            <td>ค่าตะกร้าใหม่ </td>
                                            <td>{{$util->numberFormat($pretty->Debt3)}}</td>
                                            <td ><input type="number" class="form-control input-debt" id="pay_debt3" name="pay_debt3" onblur="calculateWage()" style="width:100px;"/></td>
                                            <td>{{(!is_null($perform) ? $util->numberFormat($perform->Pay_Debt3) : "0")}}</td>
                                        </tr>
                                    @endif
                                    @if($pretty->Debt4 > 0)
                                        <tr class="debt-item">
                                            <td>ค่าตะกร้าใหม่ D3</td>
                                            <td>{{$util->numberFormat($pretty->Debt4)}}</td>
                                            <td ><input type="number" class="form-control input-debt" id="pay_debt4" name="pay_debt4" onblur="calculateWage()" style="width:100px;"/></td>
                                            <td>{{(!is_null($perform) ? $util->numberFormat($perform->Pay_Debt4) : "0")}}</td>
                                        </tr>
                                    @endif
                                    @if($pretty->Debt5 > 0)
                                        <tr class="debt-item">
                                            <td>ค่าหมอ</td>
                                            <td>{{$util->numberFormat($pretty->Debt5)}}</td>
                                            <td ><input type="number" class="form-control input-debt" id="pay_debt5" name="pay_debt5" onblur="calculateWage()" style="width:100px;"/></td>
                                            <td>{{(!is_null($perform) ? $util->numberFormat($perform->Pay_Debt5) : "0")}}</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                            <div class="title"><strong>ค่าใช้จ่ายอื่นๆ</strong></div>
                            <div class="form-group row">
                                <label for="" class="col-sm-4 form-control-label">ค่าใช้จ่ายอื่นๆ</label>
                                <div class="col-sm-8">
                                    <input type="number" class="form-control" onblur="calculateWage()" id="other_debt" value="" name="other_debt">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-sm-4 form-control-label">หมายเหตุ</label>
                                <div class="col-sm-8">
                                    <textarea name="other_debt_remark" id="other_debt_remark"  class="form-control" rows="5"></textarea>
                                </div>
                            </div>
                            <div class="title"><strong>รายได้</strong></div>
                                <div class="form-group row">
                                    <label for="" class="col-sm-4 form-control-label">รายได้อื่น ๆ</label>
                                    <div class="col-sm-8">
                                        <input type="number" class="form-control" onblur="calculateWage()" id="other_income" value="" name="other_income">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-sm-4 form-control-label">หมายเหตุ</label>
                                    <div class="col-sm-8">
                                        <textarea name="other_income_remark" id="other_income_remark"  class="form-control" rows="5"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                
                                <div class="title"><strong>สรุปรายการ</strong></div>
                                <div class="form-group row">
                                    <label class="col-sm-5 form-control-label">ค่าตอบแทน</label>
                                    <div class="col-sm-5 text-right">
                                        <p class="form-control-static" id="lbl-sub-total">{{$util->numberFormat($sumTotal)}}</p>
                                        <input type="hidden" name="sum_wage" id="hd_sum_wage" value="{{$sumTotal}}">
                                        <input type="hidden" name="sum_comm" id="hd_sum_comm" value="{{$sumComm}}">
                                        <input type="hidden" name="sum_round" id="hd_sum_round" value="{{$sumRound}}">
                                    </div>
                                    <div class="col-sm-2 ">
                                        <p class="form-control-static">บาท</p>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 form-control-label">รายได้</label>
                                    <div class="col-sm-5 text-right">
                                        <p class="form-control-static" id="pr_income_other">0</p>
                                        <input type="hidden" name="sum_income" id="hd_sum_income">
                                    </div>
                                    <div class="col-sm-2 ">
                                        <p class="form-control-static">บาท</p>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 form-control-label">หักค่าเสริมสวย</label>
                                    <div class="col-sm-5 text-right">
                                        <!-- <p class="form-control-static" id="">{{$util->numberFormat($makeup->Ref_Desc)}}</p> -->
                                        <input type="number" class="form-control input-select" name="sum_make_up" onblur="calculateWage()" id="hd_sum_make_up" value="{{$makeup->Ref_Desc}}">
                                    </div>
                                    <div class="col-sm-2 ">
                                        <p class="form-control-static">บาท</p>
                                    </div>
                                </div>
                                <div class="form-group row">
                                        <label class="col-sm-5 form-control-label">หักค่าใช้จ่ายอื่นๆ</label>
                                        <div class="col-sm-5 text-right">
                                            <p class="form-control-static" id="pr_debt_other">0</p>
                                            <input type="hidden" name="sum_debt" id="hd_sum_debt">
                                        </div>
                                        <div class="col-sm-2 ">
                                            <p class="form-control-static">บาท</p>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-sm-5 form-control-label">คงเหลือ</label>
                                        <div class="col-sm-5 text-right">
                                            <p class="form-control-static" id="pr_total">{{$util->numberFormat($sumTotal-$makeup->Ref_Desc)}}</p>
                                            <input type="hidden" name="net_amt" value="{{$sumTotal-$makeup->Ref_Desc}}" id="hd_net_amt">
                                        </div>
                                        <div class="col-sm-2 ">
                                            <p class="form-control-static">บาท</p>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-5 form-control-label">รับเงิน</label>
                                        <label class="col-sm-5 text-right">
                                            <p class="form-control-static text-danger" id="pr_total_rec">{{$util->numberFormat($sumTotal-$makeup->Ref_Desc)}}</p>
    </label>
                                        <div class="col-sm-2">
                                            <p class="form-control-static">บาท</p>
                                        </div>
                                    </div>
                                <!-- @if(is_null($perform))
                                    
                                @else
                                @php($sumDebt = $perform->Pay_Debt1+$perform->Pay_Debt2+$perform->Pay_Debt3+$perform->Pay_Debt4+$perform->Pay_Debt5+$perform->Other_Debt)
                                    <div class="form-group row">
                                        <label class="col-sm-5 form-control-label">หักค่าใช้จ่ายอื่นๆ</label>
                                        <div class="col-sm-5 text-right">
                                            <p class="form-control-static" id="pr_debt_other">{{$util->numberFormat($sumDebt)}}</p>
                                            <input type="hidden" name="sum_debt" id="hd_sum_debt">
                                        </div>
                                        <div class="col-sm-2 ">
                                            <p class="form-control-static">บาท</p>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-5 form-control-label">คงเหลือ</label>
                                        <div class="col-sm-5 text-right">
                                            <p class="form-control-static" id="pr_total">{{$util->numberFormat($sumTotal-$makeup->Ref_Desc-$sumDebt)}}</p>
                                            <input type="hidden" name="net_amt" value="" id="hd_net_amt">
                                        </div>
                                        <div class="col-sm-2 ">
                                            <p class="form-control-static">บาท</p>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-5 form-control-label">รับเงินแล้ว</label>
                                        <label class="col-sm-5 text-right">
                                            <p class="form-control-static text-success" id="pr_total_rec">{{$util->numberFormat($sumTotal-$makeup->Ref_Desc-$sumDebt)}}</p>
    </label>
                                        <div class="col-sm-2">
                                            <p class="form-control-static">บาท</p>
                                        </div>
                                    </div>
                                @endif -->
                                
                            </div>
                        </div>
                        {{csrf_field()}}
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-8 ml-auto">
                            <button type="submit" class="btn btn-primary">จ่ายค่าตอบแทน</button>
                                @if(is_null($perform))
                                    
                                @endif
                                <a href="{{Config::get('app.context')}}prettypay" class="btn btn-secondary">กลับ</a>
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
calculateWage();
$("form").submit(function(){
    var $rowwage = $(".chk-wage:checked").parents("tr.row-wage");
    if($rowwage.length == 0){
         $.confirm({
            theme:'dark',
            type:'red',
            title:'ผิดพลาด',
            content:'กรุณาเลือกรายการที่จะจ่ายค่าตอบแทน',
            buttons: {
            OK: function () {
                    
                }
            }
        });
        return false;
    }
    var angel_list = "";
    var i =0;
    $rowwage.each(function(){
        if(i!=0){
            angel_list += ",";
        }
        angel_list += $(this).attr("data-id");
        i++;
    });
    $("input[name=angel_list]").val(angel_list);
});
function calculateWage(){
	var $rowwage = $(".chk-wage:checked").parents("tr.row-wage");
	var sumround = 0;
	var sumitemwage = 0;
	var sumcomm = 0;
	var sumot = 0;
	var sumitemtotal = 0;
	var rowcount = 0;
	var otlist = "";
	$rowwage.each(function(){
		if(rowcount != 0){
			otlist += ",";
		}
		var wage = parseFloat($(this).find(".item-wage").val());
		var comm = parseFloat($(this).find(".item-com").val());
		var ot = parseFloat(($(this).find(".item-ot").val()=="") ? "0" : $(this).find(".item-ot").val());
		sumround += parseFloat($(this).find(".item-round").val());
		sumitemwage += wage;
		sumcomm += comm;
		sumot += ot;
		sumitemtotal += (wage-comm+ot);
		$(this).find(".lbl-item-sum").text(addCommas(wage-comm+ot));
		otlist += ot;
		rowcount++;
	});
	$("#lbl-sum-round").text(addCommas(sumround));
	$("#lbl-sum-wage").text(addCommas(sumitemwage));
	$("#lbl-sum-com").text(addCommas(sumcomm));
	$("#lbl-sum-ot").text(addCommas(sumot));
	$("#lbl-sum-total").text(addCommas(sumitemtotal));
	$("#lbl-sub-total").text(addCommas(sumitemtotal));
	$("#hd_sum_wage").val(sumitemtotal);
	$("input[name=ot_list]").val(otlist);
	
    var sumincome = $("#other_income").val()=="" ? 0 : parseInt($("#other_income").val());
    var subtotal = parseInt($("#hd_sum_wage").val()) - parseInt(($("#hd_sum_make_up").val()=="" ? "0" : $("#hd_sum_make_up").val()))+sumincome;
    var sumdebt = 0;
    var $debt_item = $("tr.debt-item");
    $debt_item.each(function(){
        if($(this).find(".input-debt").val()!=""){
            sumdebt += parseInt($(this).find(".input-debt").val());
        }
    });
    var other_debt = $("#other_debt").val()=="" ? 0 : parseInt($("#other_debt").val());
    sumdebt += other_debt;
    $("#pr_debt_other").text(addCommas(sumdebt));
    $("#hd_sum_debt").val(sumdebt);
    console.log(subtotal);

    $("#pr_income_other").text(addCommas(sumincome));
    $("#pr_total").text(addCommas(subtotal-sumdebt));
    $("#pr_total_rec").text(addCommas(subtotal-sumdebt));
    $("#hd_net_amt").val(subtotal-sumdebt);
}
</script>
@stop
