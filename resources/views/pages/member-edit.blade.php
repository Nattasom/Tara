@extends('layouts.default')
@section('title')
สมัครสมาชิก
@stop
@section('content')
    <div id="page-menu-key" data-menu="m_member" data-sub="member_ddl" data-url="member" ></div>
    <!-- Page Header-->
    <div class="page-header no-margin-bottom">
        <div class="container-fluid">
        <h2 class="h5 no-margin-bottom">แก้ไขสมาชิก</h2>
        </div>
    </div>
    <!-- Breadcrumb-->
    <div class="container-fluid">
        <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="member">สมาชิก</a></li>
        <li class="breadcrumb-item active">แก้ไขสมาชิก</li>
        </ul>
    </div>

    <section class="no-padding-top">
        <div class="container-fluid">
            <div class="block">
                <div class="block-body">
                    @if($status == "01")
                        <div class="alert alert-success alert-dismissible in" role="alert"> 
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button> 
                            <strong>เรียบร้อย !</strong> แก้ไขข้อมูลเรียบร้อย
                        </div>
                    @endif
                    <form id="mainForm" class="form-horizontal" method="post" action="" novalidate>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">รหัสสมาชิก <sup class="text-danger">*</sup></label>
                            <div class="col-sm-6">
                            <input type="text" class="form-control" name="member_no" maxlength="10" value="{{$member->Member_ID}}" required>
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">ชื่อ <sup class="text-danger">*</sup></label>
                            <div class="col-sm-6">
                            <input type="text" class="form-control" name="member_name" value="{{$member->First_Name}}"  required>
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">นามสกุล </label>
                            <div class="col-sm-6">
                            <input type="text" class="form-control" name="member_lastname" value="{{$member->Last_Name}}" >
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">ที่อยู่ </label>
                            <div class="col-sm-6">
                            <textarea name="member_addr" id="" rows="5" class="form-control" >{{$member->Address}}</textarea>
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">เบอร์โทร </label>
                            <div class="col-sm-6">
                            <input type="text" class="form-control" name="member_tel" value="{{$member->Tel_No}}" >
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">พนักงานเชียร์แขก <sup class="text-danger">*</sup></label>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <input type="text" class="form-control readonly" name="recept_code" value="{{$member->Reception_ID}}:{{$member->Recept_Name}}">
                                    <input type="hidden"  name="recept_id" value="{{$member->SysReception_ID}}">
                                    <div class="input-group-append">
                                        <button type="button" data-toggle="modal" data-target="#receiptModal" class="btn btn-info"><i class="fa fa-search"></i></button>
                                        <button type="button" class="btn btn-danger" id="clearParent"  onclick="clearInputGroupText_(this,'#recept_name')" ><i class="fa fa-trash"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">ชื่อพนักงานเชียร์แขก </label>
                            <div class="col-sm-6">
                                <p class="form-control-static" id="recept_name">{{$member->Recept_Name}}</p>
                            <!-- <input type="text" class="form-control readonly" id="recept_name" name="recept_name" > -->
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">วันที่สมัคร <sup class="text-danger">*</sup></label>
                            <div class="col-sm-6">
                                <div class="input-group date"> 
                                    <input type="text" class="form-control readonly" onchange="autoExpiredDate(this)"  name="datefrom" value="{{$util->dateToThaiFormat(date("Y-m-d",strtotime($member->Member_From_Date)))}}" >
                                    <span class="input-group-btn"> 
                                        <button class="btn btn-info btn-datepicker" id=""  type="button"><i class="fa fa-calendar"></i></button> 
                                    </span> 
                                </div>
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">วันหมดอายุ </label>
                            <div class="col-sm-6">
                                <label>
                                    <input type="checkbox" name="chk_no_expired" value="T" {{is_null($member->Expired_Date) ? 'checked':''}}/> ไม่มีวันหมดอายุ
                                </label>
                                <div class="input-group date"> 
                                    <input type="text" class="form-control readonly"  name="dateexpired" value="{{is_null($member->Expired_Date) ? '':$util->dateToThaiFormat(date("Y-m-d",strtotime($member->Expired_Date)))}}" >
                                    <span class="input-group-btn"> 
                                        <button class="btn btn-info btn-datepicker" id=""  type="button"><i class="fa fa-calendar"></i></button> 
                                    </span> 
                                </div>
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">สถานะ </label>
                            <div class="col-sm-6">
                                <select name="member_status" class="form-control">
                                    @foreach($member_status as $key=>$value)
                                        <option value="{{$value->Ref_Code}}" {{$member->Member_Status==$value->Ref_Code ? "selected":""}}>{{$value->Ref_Desc}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        
                        {{ csrf_field() }}
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-8 ml-auto">
                                <input type="hidden" name="action" value="" />
                                <button type="submit"  class="btn btn-primary">บันทึกข้อมูล</button>
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
    $("#recept_name").text(name);
    
    $("#receiptModal").modal("hide");
        
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
            url: "../ajax/loadreceiptpopup",
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
            datefrom:{
                required:true
            }
        },
            errorPlacement: function(error, element) {
            if (element.attr("name") == "recept_code" || element.attr("name") == "datefrom") {
                var $ipt_group = element.parents(".input-group");
                error.insertAfter($ipt_group);

            } else {
                error.insertAfter(element);
            }
        },
            messages: {
                member_no: {
                    required:"กรุณากรอกข้อมูล"
                },
                member_name: {
                    required:"กรุณากรอกข้อมูล"
                }
                ,
                recept_code: {
                    required:"กรุณากรอกข้อมูล"
                },
                datefrom:{
                    required:"กรุณากรอกข้อมูล"
                }
                
            }
        });
        function autoExpiredDate(ele){
            var date = $(ele).val();
            var from = date.split("/")
            var f = new Date(from[2], from[1] - 1, from[0])
            f.setFullYear(f.getFullYear() + 1);
            $("input[name=dateexpired]").val(getFormattedDate(f));
        }
        function getFormattedDate(date) {
        var year = date.getFullYear();

        var month = (1 + date.getMonth()).toString();
        month = month.length > 1 ? month : '0' + month;

        var day = date.getDate().toString();
        day = day.length > 1 ? day : '0' + day;
        
        return day + '/' + month + '/' + year;
        }
</script>
@stop
