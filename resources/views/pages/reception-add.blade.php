@extends('layouts.default')
@section('title')
เพิ่มข้อมูลพนักงานต้อนรับ
@stop
@section('content')
    <div id="page-menu-key" data-menu="m_employee" data-sub="employee_ddl" data-url="reception" ></div>
    <!-- Page Header-->
    <div class="page-header no-margin-bottom">
        <div class="container-fluid">
        <h2 class="h5 no-margin-bottom">เพิ่มข้อมูลพนักงานต้อนรับ</h2>
        </div>
    </div>
    <!-- Breadcrumb-->
    <div class="container-fluid">
        <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">พนักงาน</a></li>
        <li class="breadcrumb-item"><a href="reception">จัดการข้อมูลเชียร์แขก</a></li>
        <li class="breadcrumb-item active">เพิ่มข้อมูลพนักงานต้อนรับ</li>
        </ul>
    </div>
     <section class="no-padding-top">
        <div class="container-fluid">
            <div class="block">
                <div class="block-body">
                    <form class="form-horizontal" id="mainForm" method="post" novalidate>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">หมายเลขพนักงานต้อนรับ <sup class="text-danger">*</sup></label>
                            <div class="col-sm-6">
                            <input type="text" class="form-control" name="recept_id" maxlength="4">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">เลขประจำตัวพนักงาน <sup class="text-danger">*</sup></label>
                            <div class="col-sm-6">
                            <input type="text" class="form-control" name="recept_code" >
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">ชื่อ <sup class="text-danger">*</sup></label>
                            <div class="col-sm-6">
                            <input type="text" class="form-control" name="fname" >
                            </div
                            <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">นามสกุล </label>
                            <div class="col-sm-6">
                            <input type="text" class="form-control" name="lname" >
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">ชื่อเล่น <sup class="text-danger">*</sup></label>
                            <div class="col-sm-6">
                            <input type="text" class="form-control" name="recept_nickname" >
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">ประเภท <sup class="text-danger">*</sup></label>
                            <div class="col-sm-6">
                                <select class="form-control" name="recept_type">
                                    <option value="">เลือกประเภท</option>
                                    @foreach($recept_type as $k=>$v)
                                        <option value="{{$v->SysReception_Type_ID}}">{{$v->Reception_Type_Desc}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">ที่อยู่</label>
                            <div class="col-sm-6">
                                <textarea class="form-control" rows="5" name="address"></textarea>
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">โทรศัพท์</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="phone" />
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">เลขประจำตัวประชาชน <sup class="text-danger">*</sup></label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="citizen_id" maxlength="13" />
                            </div>
                        </div>
                        {{ csrf_field() }}
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-8 ml-auto">
                                <button type="submit" class="btn btn-primary">บันทึก</button>
                                <a href="reception" class="btn btn-secondary">กลับ</a>
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
            recept_id: {
                required:true
            },
            recept_code:{
                required:true
            },
            fname:{
                required:true
            },
            recept_nickname:{
                required:true
            },recept_type:{
                required:true
            },citizen_id:{
                required:true
            }
        },messages: {
                recept_id: {
                    required:"กรุณากรอกข้อมูล"
                },
                recept_code:{
                    required:"กรุณากรอกข้อมูล"
                },
                fname:{
                    required:"กรุณากรอกข้อมูล"
                },
                recept_nickname:{
                    required:"กรุณากรอกข้อมูล"
                },recept_type:{
                    required:"กรุณากรอกข้อมูล"
                },citizen_id:{
                    required:"กรุณากรอกข้อมูล"
                }
            }
        });
</script>
@stop
