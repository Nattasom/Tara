@extends('layouts.default')
@section('title')
เพิ่มข้อมูลพนักงานบริการ
@stop
@section('content')
    <div id="page-menu-key" data-menu="m_employee" data-sub="employee_ddl" data-url="pretty" ></div>
    <!-- Page Header-->
    <div class="page-header no-margin-bottom">
        <div class="container-fluid">
        <h2 class="h5 no-margin-bottom">เพิ่มข้อมูลพนักงานบริการ</h2>
        </div>
    </div>
    <!-- Breadcrumb-->
    <div class="container-fluid">
        <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">พนักงาน</a></li>
        <li class="breadcrumb-item"><a href="pretty">จัดการข้อมูลพนักงานบริการ</a></li>
        <li class="breadcrumb-item active">เพิ่มข้อมูลพนักงานบริการ</li>
        </ul>
    </div>
     <section class="no-padding-top">
        <div class="container-fluid">
            <div class="block">
                <div class="block-body">
                    <div class="title"><strong>ข้อมูลพนักงานบริการ</strong></div>
                    @if($status == "02")
                        <div class="alert alert-danger alert-dismissible in" role="alert"> 
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button> 
                            <strong>ผิดพลาด !</strong> {{$message}}
                        </div>
                    @endif
                    <form id="mainForm" method="post" class="form-horizontal">
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">รหัสพนักงานบริการ <sup class="text-danger">*</sup></label>
                            <div class="col-sm-6">
                            <input type="text" class="form-control" name="emp_id" maxlength="8" value="{{$params['Angel_ID']}}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">ชื่อเล่น <sup class="text-danger">*</sup></label>
                            <div class="col-sm-6">
                            <input type="text" class="form-control" name="nickname" value="{{$params['Nick_Name']}}" required>
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">ชื่อ <sup class="text-danger">*</sup></label>
                            <div class="col-sm-6">
                            <input type="text" class="form-control" name="fname" value="{{$params['First_Name']}}" required>
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">นามสกุล </label>
                            <div class="col-sm-6">
                            <input type="text" class="form-control" name="lname" value="{{$params['Last_Name']}}" >
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">เลขประจำตัวประชาชน </label>
                            <div class="col-sm-6">
                            <input type="text" class="form-control" name="citizen" value="{{$params['Citizen_ID']}}" >
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">โทรศัพท์</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="phone" value="{{$params['Tel_No']}}" />
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
                                        <option value="{{$value->SysAngelType}}" {{($params['SysAngelType']==$value->SysAngelType) ? "selected":""}}>{{$value->Angel_Type_Code}}</option>
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
                                    <input id="" type="radio" name="parttime" value="N" {{($params['Part_Time_Flag']=='N' || $params['Part_Time_Flag']=='') ? "checked":""}}> ไม่เป็น
                                </label>
                                <label class="checkbox-inline">
                                    <input id="" type="radio" name="parttime" value="Y" {{($params['Part_Time_Flag']=='Y') ? "checked":""}}> เป็น
                                </label>
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">หมายเลขการ์ด</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control input-select" value="{{$rfid_no}}" name="card_no" />
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
                            <input type="text" class="form-control" name="debt1" value="{{$params['Debt1']}}">
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">ค่าตะกร้า </label>
                            <div class="col-sm-6">
                            <input type="text" class="form-control" name="debt2" value="{{$params['Debt2']}}">
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">ค่าตะกร้าใหม่ </label>
                            <div class="col-sm-6">
                            <input type="text" class="form-control" name="debt3" value="{{$params['Debt3']}}">
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">ค่าตะกร้าใหม่ D3 </label>
                            <div class="col-sm-6">
                            <input type="text" class="form-control" name="debt4" value="{{$params['Debt4']}}">
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">ค่าหมอ </label>
                            <div class="col-sm-6">
                            <input type="text" class="form-control" name="debt5" value="{{$params['Debt5']}}">
                            </div>
                        </div>
                        {{csrf_field()}}
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-8 ml-auto">
                                <button type="submit" class="btn btn-primary">บันทึก</button>
                                <a href="pretty#employee" class="btn btn-secondary">กลับ</a>
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