@extends('layouts.default')
@section('title')
แก้ไขข้อมูลประเภทพนักงานบริการ
@stop
@section('content')
    <div id="page-menu-key" data-menu="m_employee" data-sub="employee_ddl" data-url="pretty" ></div>
    <!-- Page Header-->
    <div class="page-header no-margin-bottom">
        <div class="container-fluid">
        <h2 class="h5 no-margin-bottom">แก้ไขข้อมูลประเภทพนักงานบริการ</h2>
        </div>
    </div>
    <!-- Breadcrumb-->
    <div class="container-fluid">
        <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">พนักงาน</a></li>
        <li class="breadcrumb-item"><a href="{{Config::get('app.context')}}pretty#emptype">จัดการข้อมูลพนักงานบริการ</a></li>
        <li class="breadcrumb-item active">แก้ไขข้อมูลประเภทพนักงานบริการ</li>
        </ul>
    </div>
     <section class="no-padding-top">
        <div class="container-fluid">
            <div class="block">
                <div class="block-body">
                    @if($status == "01")
                        <div class="alert alert-success alert-dismissible in" role="alert"> 
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button> 
                            <strong>เรียบร้อย !</strong> บันทึกข้อมูลเรียบร้อย
                        </div>
                    @endif
                    <div class="title"><strong>ข้อมูลประเภทพนักงานบริการ</strong></div>
                    <form id="mainForm" method="post" class="form-horizontal">
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">รหัสประเภท <sup class="text-danger">*</sup></label>
                            <div class="col-sm-6">
                            <input type="text" class="form-control" name="type_code" maxlength="8" value="{{$pretty_type->Angel_Type_Code}}">
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">ค่าบริการ <sup class="text-danger">*</sup></label>
                            <div class="col-sm-6">
                            <input type="number" class="form-control" name="type_fee" value="{{$pretty_type->Angel_Fee}}">
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">ค่าตอบแทน <sup class="text-danger">*</sup></label>
                            <div class="col-sm-6">
                            <input type="number" class="form-control" name="type_wage" value="{{$pretty_type->Angel_Wage}}" >
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">ค่าการ์ด / รอบ </label>
                            <div class="col-sm-6">
                            <input type="number" class="form-control" name="type_com" value="{{$pretty_type->Credit_Comm}}">
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">รอบ (นาที) <sup class="text-danger">*</sup></label>
                            <div class="col-sm-6">
                            <input type="number" class="form-control" step="30" name="type_round" value="{{$pretty_type->round_time}}" >
                            </div>
                        </div>
                        {{csrf_field()}}
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-8 ml-auto">
                                <button type="submit" class="btn btn-primary">บันทึก</button>
                                <a href="{{Config::get('app.context')}}pretty#emptype" class="btn btn-secondary">กลับ</a>
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