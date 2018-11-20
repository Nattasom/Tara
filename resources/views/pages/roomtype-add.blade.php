@extends('layouts.default')
@section('title')
เพิ่มประเภทห้องพัก
@stop
@section('content')
    <div id="page-menu-key" data-menu="m_roomsetting" data-sub="room_ddl" data-url="roomsetting" ></div>
    <!-- Page Header-->
    <div class="page-header no-margin-bottom">
        <div class="container-fluid">
        <h2 class="h5 no-margin-bottom">เพิ่มประเภทห้องพัก</h2>
        </div>
    </div>
    <!-- Breadcrumb-->
    <div class="container-fluid">
        <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">ดูแลห้องพัก</a></li>
        <li class="breadcrumb-item"><a href="roomtypesetting">จัดการประเภทห้องพัก</a></li>
        <li class="breadcrumb-item active">เพิ่มประเภทห้องพัก</li>
        </ul>
    </div>

    <section class="no-padding-top">
        <div class="container-fluid">
            <div class="block">
                <div class="block-body">
                    <form id="mainForm" class="form-horizontal" method="post" action="" novalidate>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">รหัสประเภท <sup class="text-danger">*</sup></label>
                            <div class="col-sm-6">
                            <input type="text" class="form-control" name="type_code" required>
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">ชื่อประเภทห้องพัก <sup class="text-danger">*</sup></label>
                            <div class="col-sm-6">
                            <input type="text" class="form-control" name="type_name"  required>
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">ราคาเริ่มต้นครั้งแรก </label>
                            <div class="col-sm-6">
                            <input type="number" class="form-control" name="type_price_start" >
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">ราคา <sup class="text-danger">*</sup></label>
                            <div class="col-sm-6">
                            <input type="number" class="form-control" name="type_price"  required>
                            </div>
                        </div>
                        {{ csrf_field() }}
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-8 ml-auto">
                                <button type="submit" class="btn btn-primary">บันทึก</button>
                                <a href="roomtypesetting" class="btn btn-secondary">กลับ</a>
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
            type_name: {
                required:true
            },
            type_price:{
                required:true
            }
        },
            messages: {
                type_code: {
                    required:"กรุณากรอกข้อมูล"
                },
                type_name: {
                    required:"กรุณากรอกข้อมูล"
                },
                type_price:{
                    required:"กรุณากรอกข้อมูล"
                }
            }
        });
</script>
@stop
