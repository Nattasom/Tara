@extends('layouts.default')
@section('title')
ตั้งค่าระบบ
@stop
@section('content')
    <div id="page-menu-key" data-menu="m_usersetting" data-sub="setting_ddl" data-url="systemsetting" ></div>
    <!-- Page Header-->
    <div class="page-header no-margin-bottom">
        <div class="container-fluid">
        <h2 class="h5 no-margin-bottom">ตั้งค่าระบบ</h2>
        </div>
    </div>
    <!-- Breadcrumb-->
    <div class="container-fluid">
        <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">จัดการข้อมูลระบบ</a></li>
        <li class="breadcrumb-item active">ตั้งค่าระบบ</li>
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
                    <form id="mainForm" class="form-horizontal" method="post" action="" novalidate>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">ห้องสูท นาทีแรก </label>
                            <div class="col-sm-6">
                            <input type="text" class="form-control" name="SU_F_RND" value="{{$first_time_suite}}" >
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">ห้องสูท นาทีถัดไป </label>
                            <div class="col-sm-6">
                            <input type="text" class="form-control" name="SU_RND" value="{{$time_suite}}" >
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">เวลาเปิด </label>
                            <div class="col-sm-6">
                            <input type="text" class="form-control" name="OPEN" value="{{$time_open}}" >
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">ค่าใช้จ่ายประจำวันพนักงาน </label>
                            <div class="col-sm-6">
                            <input type="text" class="form-control" name="MAKEUP" value="{{$makeup}}" >
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">ค่าพิเศษรอบดึก (หลังเที่ยงคืน)</label>
                            <div class="col-sm-6">
                            <input type="text" class="form-control" name="OT_FEE" value="{{$ot_fee}}" >
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">ค่าตอบแทนพิเศษรอบดึก (หลังเที่ยงคืน)</label>
                            <div class="col-sm-6">
                            <input type="text" class="form-control" name="OT_WAGE" value="{{$ot_wage}}" >
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-8 ml-auto">
                                <button type="submit" class="btn btn-primary">บันทึก</button>
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
</script>
@stop
