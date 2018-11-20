@extends('layouts.default')
@section('title')
บันทึกเวลาเข้า - ออก
@stop
@section('content')
    <div id="page-menu-key" data-menu="m_employee" data-sub="employee_ddl" data-url="time" ></div>
    <!-- Page Header-->
    <div class="page-header no-margin-bottom">
        <div class="container-fluid">
        <h2 class="h5 no-margin-bottom">บันทึกเวลาเข้า-ออก</h2>
        </div>
    </div>
    <!-- Breadcrumb-->
    <div class="container-fluid">
        <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">พนักงาน</a></li>
        <li class="breadcrumb-item active">บันทึกเวลาเข้า-ออก</li>
        </ul>
    </div>
    <section class="no-padding-top">
        <div class="container-fluid">
            <div class="block">
                <form id="mainForm" method="post" class="form-horizontal">
                    <div class="form-group row">
                        <div class="col-sm-1"></div>
                        <label class="col-sm-3 form-control-label">เข้า-ออก</label>
                        <div class="col-sm-6">
                            <label class="checkbox-inline">
                                <input id="" type="radio" name="type" value="IN" onclick="textFocus()" checked> เข้างาน
                            </label>
                            <label class="checkbox-inline">
                                <input id="" type="radio" name="type" onclick="textFocus()" value="OUT"> ออกงาน
                            </label>
                        </div>
                    </div>
                    <div class="line"></div>
                    <div class="form-group row">
                        <div class="col-sm-1"></div>
                        <label class="col-sm-3 form-control-label">หมายเลขการ์ด</label>
                        <div class="col-sm-6">
                            <input type="text" id="card_rfid" class="form-control input-select" name="card_no"   value=""/>
                        </div>
                        <div class="col-sm-1"><span id="wait_loading" class="hide"><i class="fa fa-spinner fa-pulse fa-fw"></i></span></div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@stop
@section("script")
<script>
    $(document).ready(function(){
        $("#card_rfid").focus();
    });
    function textFocus(){
        $("#card_rfid").focus();
    }
    $("#card_rfid").on("change",function(){

        //console.log($(this).val());
        callTime();
    });
    function callTime(){
        var params = $('#mainForm').serialize();
    $("#wait_loading").removeClass("hide");
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
    $.ajax({
        url: "ajax/timestamp",
        type: "POST",
        data:params,
        success: function(data){
            console.log(data);
            if(data.status=="01"){
                $.confirm({
                    theme:'dark',
                    type:'green',
                    title:'เรียบร้อย',
                    content: data.message,
                    buttons: {
                        OK: function () {
                            $("#card_rfid").val('');
                            $("#card_rfid").focus();
                        }
                    }
                });
            }
            else{
                $.confirm({
                    theme:'dark',
                    type:'red',
                    title:'ผิดพลาด',
                    content: data.message,
                    buttons: {
                        OK: function () {
                            $("#card_rfid").val('');
                            $("#card_rfid").focus();
                        }
                    }
                });
            }
            $("#wait_loading").addClass("hide");
        }
    });
    }
</script>
@stop
