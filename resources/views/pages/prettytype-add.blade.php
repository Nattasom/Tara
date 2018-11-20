@extends('layouts.default')
@section('title')
เพิ่มข้อมูลประเภทพนักงานบริการ
@stop
@section('content')
    <div id="page-menu-key" data-menu="m_employee" data-sub="employee_ddl" data-url="pretty" ></div>
    <!-- Page Header-->
    <div class="page-header no-margin-bottom">
        <div class="container-fluid">
        <h2 class="h5 no-margin-bottom">เพิ่มข้อมูลประเภทพนักงานบริการ</h2>
        </div>
    </div>
    <!-- Breadcrumb-->
    <div class="container-fluid">
        <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">พนักงาน</a></li>
        <li class="breadcrumb-item"><a href="pretty#emptype">จัดการข้อมูลพนักงานบริการ</a></li>
        <li class="breadcrumb-item active">เพิ่มข้อมูลประเภทพนักงานบริการ</li>
        </ul>
    </div>
     <section class="no-padding-top">
        <div class="container-fluid">
            <div class="block">
                <div class="block-body">
                    <div class="title"><strong>ข้อมูลประเภทพนักงานบริการ</strong></div>
                    <form id="mainForm" method="post" class="form-horizontal">
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">รหัสประเภท <sup class="text-danger">*</sup></label>
                            <div class="col-sm-6">
                            <input type="text" class="form-control" name="type_code" maxlength="8" >
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">ค่าบริการ <sup class="text-danger">*</sup></label>
                            <div class="col-sm-6">
                            <input type="number" class="form-control" name="type_fee" required>
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">ค่าตอบแทน <sup class="text-danger">*</sup></label>
                            <div class="col-sm-6">
                            <input type="number" class="form-control" name="type_wage" >
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">ค่าการ์ด / รอบ </label>
                            <div class="col-sm-6">
                            <input type="number" class="form-control" name="type_com" >
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">รอบ (นาที) <sup class="text-danger">*</sup></label>
                            <div class="col-sm-6">
                            <input type="number" class="form-control" step="30" name="type_round" >
                            </div>
                        </div>
                        {{csrf_field()}}
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-8 ml-auto">
                                <button type="submit" class="btn btn-primary">บันทึก</button>
                                <a href="pretty#emptype" class="btn btn-secondary">กลับ</a>
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
    $("#mainForm").validate({
        rules: {
            type_code: {
                required:true
            },
            type_fee:{
                required:true
            },
            type_wage:{
                required:true
            },
            type_round:{
                required:true
            }
        },messages: {
                type_code: {
                    required:"กรุณากรอกข้อมูล"
                },
                type_fee:{
                    required:"กรุณากรอกข้อมูล"
                },
                type_wage:{
                    required:"กรุณากรอกข้อมูล"
                },
                type_round:{
                    required:"กรุณากรอกข้อมูล"
                }
            }
        });
</script>
@stop