@extends('layouts.default')
@section('title')
เพิ่มผู้ใช้งาน
@stop
@section('content')
    <div id="page-menu-key" data-menu="m_usersetting" data-sub="setting_ddl" data-url="usersetting" ></div>
    <!-- Page Header-->
    <div class="page-header no-margin-bottom">
        <div class="container-fluid">
        <h2 class="h5 no-margin-bottom">แก้ไขข้อมูลผู้ใช้งาน</h2>
        </div>
    </div>
    <!-- Breadcrumb-->
    <div class="container-fluid">
        <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">จัดการข้อมูลระบบ</a></li>
        <li class="breadcrumb-item"><a href="{{Config::get('app.context')}}usersetting">จัดการข้อมูลผู้ใช้งาน</a></li>
        <li class="breadcrumb-item active">แก้ไขข้อมูลผู้ใช้งาน</li>
        </ul>
    </div>

    <section class="no-padding-top">
        <div class="container-fluid">
            <div class="block">
                <div class="block-body">
                 @if($status == "01")
                        <div class="alert alert-success alert-dismissible in" role="alert"> 
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button> 
                            <strong>เรียบร้อย !</strong> {{$message}}
                        </div>
                    @elseif($status == "00")
                        <div class="alert alert-warning alert-dismissible in" role="alert"> 
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button> 
                            <strong>ผิดพลาด !</strong> {{$message}}
                        </div>
                    @endif
                    <form class="form-horizontal" id="mainForm" method="post" action = "" novalidate>
                         <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" ><a class="active" href="#profile" id="tab1" role="tab" data-toggle="tab">ข้อมูลผู้ใช้</a></li>
                        <li role="presentation"><a href="#role"  role="tab" id="tab2" data-toggle="tab">กำหนดสิทธิ์การใช้งาน</a></li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="profile">
                            <div class="form-group row">
                                                <div class="col-sm-1"></div>
                                                <label class="col-sm-3 form-control-label">ชื่อผู้ใช้ <sup class="text-danger">*</sup></label>
                                                <div class="col-sm-6">
                                                <input type="text" class="form-control" name="loginname" value="{{$user->Login_Name}}" readonly>
                                                </div>
                                            </div>
                                            <div class="line"></div>
                                            <div class="form-group row">
                                                <div class="col-sm-1"></div>
                                                <label class="col-sm-3 form-control-label">ชื่อ-นามสกุล <sup class="text-danger">*</sup></label>
                                                <div class="col-sm-6">
                                                <input type="text" class="form-control" name="fullname" value="{{$user->User_Fullname}}" required>
                                                </div>
                                            </div>
                                             <div class="line"></div>
                                            <div class="form-group row">
                                                <div class="col-sm-1"></div>
                                                <label class="col-sm-3 form-control-label">เปลี่ยนรหัสผ่าน</label>
                                                <div class="col-sm-6">
                                                <input type="checkbox" class="" name="changepass" value="T" id="changepass" >
                                                </div>
                                            </div>
                                            <div id="pass-hidden" class="hide">
                                                    <div class="line"></div>
                                                        <div class="form-group row">
                                                            <div class="col-sm-1"></div>
                                                            <label class="col-sm-3 form-control-label">รหัสผ่าน <sup class="text-danger">*</sup></label>
                                                            <div class="col-sm-6">
                                                            <input type="password" class="form-control" name="password" id="password" required>
                                                            </div>
                                                        </div>
                                                        <div class="line"></div>
                                                        <div class="form-group row">
                                                            <div class="col-sm-1"></div>
                                                            <label class="col-sm-3 form-control-label">ยืนยันรหัสผ่าน <sup class="text-danger">*</sup></label>
                                                            <div class="col-sm-6">
                                                            <input type="password" class="form-control" name="confirmpassword" required>
                                                            </div>
                                                        </div>
                                            </div>
                                            <div class="line"></div>
                                            <div class="form-group row">
                                                <div class="col-sm-1"></div>
                                                <label class="col-sm-3 form-control-label">หมายเลขบัตรประชาชน <sup class="text-danger">*</sup></label>
                                                <div class="col-sm-6">
                                                <input type="text" class="form-control" name="citizen" value="{{$user->User_Citizen_ID}}" required>
                                                </div>
                                            </div>
                                            <div class="line"></div>
                                            <div class="form-group row">
                                                <div class="col-sm-1"></div>
                                                <label class="col-sm-3 form-control-label">ที่อยู่</label>
                                                <div class="col-sm-6">
                                                <textarea rows="5" class="form-control" name="address" value="{{$user->User_Address}}" ></textarea>
                                                </div>
                                            </div>
                                            <div class="line"></div>
                                            <div class="form-group row">
                                                <div class="col-sm-1"></div>
                                                <label class="col-sm-3 form-control-label">โทรศัพท์</label>
                                                <div class="col-sm-6">
                                                <input type="text" class="form-control" name="phone" value="{{$user->User_Tel_No}}" />
                                                </div>
                                            </div>
                                            <div class="line"></div>
                                            <div class="form-group row">
                                                <div class="col-sm-1"></div>
                                                <label class="col-sm-3 form-control-label">หมายเลขบัตร</label>
                                                <div class="col-sm-6">
                                                <input type="text" class="form-control input-select" name="rfid" value="{{$user->RF_Card_No}}">
                                                </div>
                                            </div>
                                            <!-- <div class="line"></div>
                                            <div class="form-group row">
                                                <div class="col-sm-1"></div>
                                                <label class="col-sm-3 form-control-label">วันเริ่มต้น <sup class="text-danger">*</sup></label>
                                                <div class="col-sm-6">
                                                    <div class="input-group"> 
                                                        <input type="text" class="form-control readonly"  name="datestart"   required>
                                                        <span class="input-group-btn"> 
                                                            <button class="btn btn-info" id="dpd1" type="button"><i class="fa fa-calendar"></i></button> 
                                                        </span> 
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="line"></div>
                                            <div class="form-group row">
                                                <div class="col-sm-1"></div>
                                                <label class="col-sm-3 form-control-label">วันหมดอายุ <sup class="text-danger">*</sup></label>
                                                <div class="col-sm-6">
                                                    <div class="input-group date"> 
                                                        <input type="text" class="form-control readonly"  name="dateend"  required>
                                                        <span class="input-group-btn"> 
                                                            <button class="btn btn-info" id="dpd2"  type="button"><i class="fa fa-calendar"></i></button> 
                                                        </span> 
                                                    </div>
                                                </div>
                                            </div> -->
                        </div>
                        <div role="tabpanel" class="tab-pane" id="role">
                            <div class="table-responsive"> 
                                <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                    <th>เลือก</th>
                                    <th>ชื่อกลุ่มผู้ใช้งาน</th>
                                    <th>รายละเอียด</th>
                                    </tr>
                                </thead>
                                <tbody>
                                        @foreach($rolelist as $key=>$value)
                                        <tr>
                                            <td><input type="checkbox" data-id="{{$value->SysRole_ID}}" class="chk-role" {{in_array($value->SysRole_ID, $userrole) ? 'checked':''}} /></td>
                                            <td>{{$value->Role_Name}}</td>
                                            <td>{{$value->Role_Desc}}</td>
                                        </tr>
                                        @endforeach
                                </tbody>
                                </table>
                            </div>
                            <input type="hidden" name="role_save" value="" />
                        </div>
                    </div>
                        {{csrf_field()}}
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-8 ml-auto">
                                <button type="submit" class="btn btn-primary">บันทึก</button>
                                <a href="{{Config::get('app.context')}}usersetting" class="btn btn-secondary">กลับ</a>
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
    $.validator.setDefaults({
    ignore: ""
});
$("form#mainForm").validate({
    rules: {
        loginname: {
            required:true
        },
        fullname:{
            required:true
        }
        ,
        password:{
            required:function(){
                if($("#changepass").is(":checked")){
                    return true;
                }else{
                    return false;
                }
            },
            minlength: 4
        },
        confirmpassword:{
            required:function(){
                if($("#changepass").is(":checked")){
                    return true;
                }else{
                    return false;
                }
            },
            minlength: 4,
            equalTo: {
                depends: function(){
                    if($("#changepass").is(":checked")){
                        return true;
                    }else{
                        return false;
                    }
                },
                param: "#password"
            }
        },
        citizen:{
            required:true
        },
        datestart:{
            required:true
        },
        dateend:{
            required:true
        }
    },messages: {
            loginname: {
            required:"กรุณากรอกข้อมูล"
            },
            fullname:{
                required:"กรุณากรอกข้อมูล"
            }
            ,
            password:{
                required:"กรุณากรอกข้อมูล",
                minlength:"ความยาวอย่างน้อย 4 ตัว"
            },
            confirmpassword:{
                required:"กรุณากรอกข้อมูล",
                equalTo:"ยืนยันรหัสไม่ถูกต้อง",
                minlength:"ความยาวอย่างน้อย 4 ตัว"
            },
            citizen:{
                required:"กรุณากรอกข้อมูล"
            },
            datestart:{
                required:"กรุณากรอกข้อมูล"
            },
            dateend:{
                required:"กรุณากรอกข้อมูล"
            }
        },errorPlacement: function(error, element) {
            if (element.attr("name") == "datestart" || element.attr("name") == "dateend") {
                var $ipt_group = element.parents(".input-group");
                error.insertAfter($ipt_group);

            } else {
                error.insertAfter(element);
            }
            $("#tab1").trigger("click");
        },
        submitHandler: function(form) { //submit
            //check role checkbox
            var $chkrole = $(".chk-role:checked");
            if($chkrole.length==0){
                $.alert({
                    theme:'dark',
                    type:'red',
                    title:'ผิดพลาด',
                    content:'กรุณาเลือกสิทธิ์อย่างน้อย 1 สิทธิ์'
                });
                return false;
            }
            var i =0;
            var strsave = "";
            $chkrole.each(function(){
                var id = $(this).attr("data-id");
                if(i!=0){
                    strsave +=",";
                }
                strsave +=id;
                i++;
            });
            $("input[name=role_save]").val(strsave);
            form.submit();
         }
    });
// var nowTemp = new Date();
// var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
 
// var checkin = $('#dpd1').datepicker({
//   language:"th-th",
//   onRender: function(date) {
//     return date.valueOf() < now.valueOf() ? 'disabled' : '';
//   }
// }).on('changeDate', function(ev) {
//     var $input = $(ev.target).parents(".input-group").find("input");
//     $input.val(ev.formatdate);
//   if (ev.date.valueOf() > checkout.date.valueOf()) {
//     // var newDate = new Date(ev.date)
//     // newDate.setDate(newDate.getDate() + 365);
//     checkout.fill();
//     //checkout._setDate(newDate,'date');
//   }
//   checkin.hide();
//   $('#dpd2')[0].focus();
// }).data('datepicker');
// var checkout = $('#dpd2').datepicker({
// language:"th-th",
// viewMode:2,
//   onRender: function(date) {
//       var res= '';
//       if(date.valueOf() <= checkin.date.valueOf()){
//         res = 'disabled';
//       }
//     return  res;
//   }
// }).on('changeDate', function(ev) {
//     var $input = $(ev.target).parents(".input-group").find("input");
//     $input.val(ev.formatdate);
//   checkout.hide();
// }).data('datepicker');

$(document).on("click","#changepass",function(){
    $("#pass-hidden").toggleClass("hide");
});

</script>
@stop
