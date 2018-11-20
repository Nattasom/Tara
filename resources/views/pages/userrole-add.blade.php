@extends('layouts.default')
@section('title')
เพิ่มกลุ่มผู้ใช้งาน
@stop
@section('content')
    <div id="page-menu-key" data-menu="m_usersetting" data-sub="setting_ddl" data-url="userrole" ></div>
    <!-- Page Header-->
    <div class="page-header no-margin-bottom">
        <div class="container-fluid">
        <h2 class="h5 no-margin-bottom">เพิ่มข้อมูลกลุ่มผู้ใช้งาน</h2>
        </div>
    </div>
    <!-- Breadcrumb-->
    <div class="container-fluid">
        <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">จัดการข้อมูลระบบ</a></li>
        <li class="breadcrumb-item"><a href="userrole">จัดการข้อมูลกลุ่มผู้ใช้งาน</a></li>
        <li class="breadcrumb-item active">เพิ่มข้อมูลกลุ่มผู้ใช้งาน</li>
        </ul>
    </div>

    <section class="no-padding-top">
        <div class="container-fluid">
            <div class="block">
                <div class="block-body">
                    @if($status == "00")
                        <div class="alert alert-warning alert-dismissible in" role="alert"> 
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button> 
                            <strong>ผิดพลาด !</strong> {{$message}}
                        </div>
                    @endif
                    <form id="mainForm" class="form-horizontal" method="post" action="" novalidate>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">รหัสกลุ่มผู้ใช้ <sup class="text-danger">*</sup></label>
                            <div class="col-sm-6">
                            <input type="text" class="form-control" name="rolecode" required>
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">ชื่อกลุ่มผู้ใช้ <sup class="text-danger">*</sup></label>
                            <div class="col-sm-6">
                            <input type="text" class="form-control" name="rolename" required>
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">รายละเอียด</label>
                            <div class="col-sm-6">
                            <input type="text" class="form-control" name="roledesc" >
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-8 ml-auto">
                                <button type="submit" class="btn btn-primary">บันทึก</button>
                                <a href="userrole" class="btn btn-secondary">กลับ</a>
                            </div>
                        </div>
                        {{csrf_field()}}
                    </form>
                </div>
            </div>
            
        </div>
    </section>
@stop

@section('script')
<script>
$("#mainForm").validate({
    rules: {
        rolecode: {
            required:true
        },
        rolename:{
            required:true
        }
    },
        messages: {
            rolecode: {
                required:"กรุณากรอกข้อมูล"
            },
            rolename:{
                required:"กรุณากรอกข้อมูล"
            }
        }
    });
</script>
@stop
