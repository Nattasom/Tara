@extends('layouts.default')
@section('title')
แก้ไขข้อมูลพนักงานบริการ
@stop
@section('content')
    <div id="page-menu-key" data-menu="m_employee" data-sub="employee_ddl" data-url="pretty" ></div>
    <!-- Page Header-->
    <div class="page-header no-margin-bottom">
        <div class="container-fluid">
        <h2 class="h5 no-margin-bottom">แก้ไขข้อมูลพนักงานบริการ</h2>
        </div>
    </div>
    <!-- Breadcrumb-->
    <div class="container-fluid">
        <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">พนักงาน</a></li>
        <li class="breadcrumb-item"><a href="{{Config::get('app.context')}}pretty">จัดการข้อมูลพนักงานบริการ</a></li>
        <li class="breadcrumb-item active">แก้ไขข้อมูลพนักงานบริการ</li>
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
                    @elseif($status == "02")
                        <div class="alert alert-danger alert-dismissible in" role="alert"> 
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button> 
                            <strong>ผิดพลาด !</strong> {{$message}}
                        </div>
                    @endif
                    <div class="title"><strong>ข้อมูลพนักงานบริการ</strong></div>
                    <form id="mainForm" method="post" class="form-horizontal">
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">รหัสพนักงานบริการ <sup class="text-danger">*</sup></label>
                            <div class="col-sm-6">
                            <input type="text" class="form-control" name="emp_id" value="{{$pretty->Angel_ID}}" maxlength="8" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">ชื่อเล่น <sup class="text-danger">*</sup></label>
                            <div class="col-sm-6">
                            <input type="text" class="form-control" name="nickname" value="{{$pretty->Nick_Name}}" required>
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">ชื่อ <sup class="text-danger">*</sup></label>
                            <div class="col-sm-6">
                            <input type="text" class="form-control" name="fname" value="{{$pretty->First_Name}}" required>
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">นามสกุล </label>
                            <div class="col-sm-6">
                            <input type="text" class="form-control" name="lname" value="{{$pretty->Last_Name}}" >
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">เลขประจำตัวประชาชน </label>
                            <div class="col-sm-6">
                            <input type="text" class="form-control" name="citizen" value="{{$pretty->Citizen_ID}}" >
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">โทรศัพท์</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="phone" value="{{$pretty->Tel_No}}" />
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">ประเภท <sup class="text-danger">*</sup></label>
                            <div class="col-sm-6">
                                <select class="form-control" name="type">
                                    <option value="">เลือกประเภท</option>
                                    @foreach($pretty_type as $key=>$value)
                                        <option value="{{$value->SysAngelType}}" {{$pretty->SysAngelType==$value->SysAngelType ? "selected":""}}>{{$value->Angel_Type_Code}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">พนักงาน Part time</label>
                            <div class="col-sm-6">
                                <label class="checkbox-inline">
                                    <input id="" type="radio" name="parttime" value="N" {{$pretty->Part_Time_Flag=="N" ? "checked":""}}> ไม่เป็น
                                </label>
                                <label class="checkbox-inline">
                                    <input id="" type="radio" name="parttime" value="Y" {{$pretty->Part_Time_Flag=="Y" ? "checked":""}}> เป็น
                                </label>
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">หมายเลขการ์ด</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control input-select" name="card_no"   value="{{$card_no}}"/>
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">สถานะพนักงาน</label>
                            <div class="col-sm-6">
                                <select class="form-control" name="ang_stat">
                                    <option value="AB" {{$pretty->Angel_Status=="AB" ? "selected":""}}>ไม่มาทำงาน</option>
                                    <option value="NW" {{$pretty->Angel_Status=="NW" ? "selected":""}}>พร้อมทำงาน</option>
                                    <option value="WK" {{$pretty->Angel_Status=="WK" ? "selected":""}}>กำลังทำงาน</option>
                                </select>
                            </div>
                        </div>
                        <!-- <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">รูปพนักงาน</label>
                            <div class="col-sm-6">
                                <input type="file" name="picfile"/>
                            </div>
                        </div> -->
                        <div class="title"><strong>ยอดหักชำระจากพนักงาน</strong></div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">ค่าประชุม </label>
                            <div class="col-sm-6">
                            <input type="text" class="form-control" name="debt1" value="{{$pretty->Debt1}}">
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">ค่าตะกร้า </label>
                            <div class="col-sm-6">
                            <input type="text" class="form-control" name="debt2" value="{{$pretty->Debt2}}">
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">ค่าตะกร้าใหม่ </label>
                            <div class="col-sm-6">
                            <input type="text" class="form-control" name="debt3" value="{{$pretty->Debt3}}">
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">ค่าตะกร้าใหม่ D3 </label>
                            <div class="col-sm-6">
                            <input type="text" class="form-control" name="debt4" value="{{$pretty->Debt4}}">
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">ค่าหมอ </label>
                            <div class="col-sm-6">
                            <input type="text" class="form-control" name="debt5" value="{{$pretty->Debt5}}">
                            </div>
                        </div>
                        {{csrf_field()}}
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-8 ml-auto">
                                <button type="submit" class="btn btn-primary">บันทึก</button>
                                <a href="{{Config::get('app.context')}}pretty#employee" class="btn btn-secondary">กลับ</a>
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
            emp_id: {
                required:true
            },
            nickname:{
                required:true
            },
            fname:{
                required:true
            },
            type:{
                required:true
            }
        },messages: {
                emp_id: {
                required:"กรุณากรอกข้อมูล"
            },
            nickname:{
                required:"กรุณากรอกข้อมูล"
            },
            fname:{
                required:"กรุณากรอกข้อมูล"
            },
            type:{
                required:"กรุณากรอกข้อมูล"
            }
            }
        });
</script>
@stop
