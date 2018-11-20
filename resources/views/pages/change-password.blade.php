@extends('layouts.default')
@section('title')
เปลี่ยนรหัสผ่าน
@stop
@section('content')
    <div id="page-menu-key" data-menu="m_usersetting" data-sub="setting_ddl" data-url="usersetting" ></div>
    <!-- Page Header-->
    <div class="page-header no-margin-bottom">
        <div class="container-fluid">
        <h2 class="h5 no-margin-bottom">เปลี่ยนรหัสผ่าน</h2>
        </div>
    </div>
    <!-- Breadcrumb-->
    <div class="container-fluid">
        <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">ข้อมูลผู้ใช้งาน</a></li>
        <li class="breadcrumb-item active">เปลี่ยนรหัสผ่าน</li>
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
                        {{csrf_field()}}
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-8 ml-auto">
                                <button type="submit" class="btn btn-primary">บันทึก</button>
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
        password:{
            required:true,
            minlength: 4
        },
        confirmpassword:{
            required:true,
            minlength: 4,
            equalTo:"#password"
        }
    },messages: {
            password:{
                required:"กรุณากรอกข้อมูล",
                minlength:"ความยาวอย่างน้อย 4 ตัว"
            },
            confirmpassword:{
                required:"กรุณากรอกข้อมูล",
                equalTo:"ยืนยันรหัสไม่ถูกต้อง",
                minlength:"ความยาวอย่างน้อย 4 ตัว"
            }
        }
    });

</script>
@stop
