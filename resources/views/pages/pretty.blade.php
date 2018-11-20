@extends('layouts.default')
@section('title')
จัดการข้อมูลพนักงานบริการ
@stop
@section('content')
    <div id="page-menu-key" data-menu="m_employee" data-sub="employee_ddl" data-url="pretty" ></div>
    <!-- Page Header-->
    <div class="page-header no-margin-bottom">
        <div class="container-fluid">
        <h2 class="h5 no-margin-bottom">จัดการข้อมูลพนักงานบริการ</h2>
        </div>
    </div>
    <!-- Breadcrumb-->
    <div class="container-fluid">
        <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">พนักงาน</a></li>
        <li class="breadcrumb-item active">จัดการข้อมูลพนักงานบริการ</li>
        </ul>
    </div>
    <section class="no-padding-top">
        <div class="container-fluid">
            @if($status == "success")
                <div class="alert alert-success alert-dismissible in" role="alert"> 
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button> 
                    <strong>เรียบร้อย !</strong> เพิ่มข้อมูลเรียบร้อย
                </div>
            @elseif($status=="success_del")
                <div class="alert alert-success alert-dismissible in" role="alert"> 
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button> 
                    <strong>เรียบร้อย !</strong> ลบรายการเรียบร้อย
                </div>
            @endif
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" ><a class="active" href="#employee"  role="tab" data-toggle="tab">ข้อมูลพนักงาน</a></li>
                <li role="presentation" ><a  href="#emptype"  role="tab" data-toggle="tab">ข้อมูลประเภทพนักงาน</a></li>
                <!-- <li role="presentation"><a href="#health"  role="tab" data-toggle="tab">ประวัติการตรวจ</a></li> -->
            </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="employee">
                    @if($flag_edit)
<div class="text-right gap-bottom"><a href="prettyadd" class="btn btn-success" ><i class="fa fa-plus fa-fw"></i> เพิ่มพนักงานบริการ</a></div>
                    @endif
                    
                    <div class="block">
                        <form id="formFilter">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="block-body">
                                        <div class="form-group">
                                            <label class="form-control-label">รหัสพนักงาน</label>
                                            <input type="text" name="prcode" placeholder="" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="block-body">
                                        <div class="form-group">
                                            <label class="form-control-label">ชื่อเล่น</label>
                                            <input type="text" placeholder="" name="nickname" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="block-body">
                                        <div class="form-group">
                                            <label class="form-control-label">ชื่อ</label>
                                            <input type="text" placeholder="" name="name" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="block-body">
                                        <div class="form-group">
                                            <label class="form-control-label">นามสกุล</label>
                                            <input type="text" placeholder="" name="lastname" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="block-body">
                                        <div class="form-group">
                                            <label class="form-control-label">ประเภท</label>
                                            <select class="form-control" name="type">
                                                <option value="">ทั้งหมด</option>
                                                @foreach($pretty_type as $key=>$value)
                                                    <option value="{{$value->SysAngelType}}">{{$value->Angel_Type_Code}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="block-body">
                                        <div class="form-group">
                                            <label class="form-control-label">สถานะ</label>
                                            <select class="form-control" name="status">
                                                <option value="">ทั้งหมด</option>
                                                @foreach($pretty_status as $key=>$value)
                                                    <option value="{{$value->Ref_Code}}">{{$value->Ref_Desc}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="block-body">
                                        <div class="form-group">
                                            <label class="form-control-label">&nbsp; </label>
                                            <div>
                                                <input type="button" value="ค้นหา" onclick="formSearch()" id="btn-search" class="btn btn-info">
                                                <input type="reset" value="ยกเลิก" class="btn btn-danger">
                                            </div>
                                            
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="pretty_list" class="block paging-block">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="paging-header" >
                                            <label>แสดง 
                                                <select  class="form-control form-control-sm paging-perpage" onchange="formSearch()">
                                                    <option value="10">10</option>
                                                    <option value="30">30</option>
                                                    <option value="50">50</option>
                                                </select> รายการ
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div  class="ajax-paging-block">
                                    <div class="loading-box hide"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i><br/><br/>Loading...</div>
                                    <div class="table-responsive"> 
                                        <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                            <th>รหัสพนักงาน</th>
                                            <th>ชื่อพนักงาน</th>
                                            <th>ชื่อเล่น</th>
                                            <th>ประเภท</th>
                                            <th>สถานะ</th>
                                            <th>วันที่ปฏิบัติงาน</th>
                                            <th>จัดการ</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($pretty_list as $key=>$value)
                                                <tr>
                                                    <td>{{$value->Angel_ID}}</td>
                                                    <td>{{$value->First_Name}} {{$value->Last_Name}}</td>
                                                    <td>{{$value->Nick_Name}}</td>
                                                    <td>
                                                        {{$value->Angel_Type_Code}}
                                                    </td>
                                                    <td>
                                                        @if($value->Angel_Status=="AB")
                                                        <span class="text-warning">ไม่มาทำงาน</span>
                                                        @elseif($value->Angel_Status=="NW")
                                                        <span class="text-success">พร้อมทำงาน</span>
                                                        @elseif($value->Angel_Status=="WK")
                                                        <span class="text-danger">กำลังทำงาน</span>
                                                        @endif
                                                    </td>
                                                    <td>{{$util->dateToThaiFormat($value->Work_From_Date)}}</td>
                                                    <td>
                                                    @if($flag_edit)
<a href="{{Config::get('app.context')}}prettyedit/{{$value->SysAngel_ID}}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> แก้ไข</a>
                                                            <button type="button" onclick="delPretty(this)" data-id="{{$value->SysAngel_ID}}" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> ลบ</button>
                                                    @else
                                                    -
                                                    @endif
                                                    
                                                            </td>
                                                </tr>
                                            @endforeach
                                            
                                            
                                        </tbody>
                                        </table>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="paging-detail-text">{{$paging->paging_detail}}</div>
                                        </div>
                                        <div class="col-md-7 text-md-right">
                                            <nav >
                                                <ul class="pagination">
                                                    {!!$paging->renderHtml()!!}
                                                </ul>
                                            </nav>
                                        </div>
                                    </div> 
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div role="tabpanel" class="tab-pane" id="emptype">
                @if($flag_edit)
                    <div class="text-right gap-bottom"><a href="prettytypeadd" class="btn btn-success" ><i class="fa fa-plus fa-fw"></i> เพิ่มประเภทพนักงานบริการ</a></div>
                @endif
                    
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="block paging-block">
                                <div id="pretty_type_list"  class="ajax-paging-block">
                                    <div class="loading-box hide"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i><br/><br/>Loading...</div>
                                    <div class="table-responsive"> 
                                        <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                            <th>รหัสประเภท</th>
                                            <th>รายละเอียด</th>
                                            <th>ค่าบริการ</th>
                                            <th>ค่าจ้าง</th>
                                            <th>ค่าการ์ด/รอบ</th>
                                            <th>รอบ (นาที)</th>
                                            <th>จัดการ</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($pretty_type as $key=>$value)
                                                <tr>
                                                    <td>{{$value->Angel_Type_Code}}</td>
                                                    <td>{{$value->Angel_Type_Desc}}</td>
                                                    <td>{{$value->Angel_Fee}}</td>
                                                    <td>{{$value->Angel_Wage}}</td>
                                                    <td>{{$value->Credit_Comm}}</td>
                                                    <td>{{$value->round_time}}</td>
                                                    <td>
                                                    @if($flag_edit)
                                                    <a href="{{Config::get('app.context')}}prettytypeedit/{{$value->SysAngelType}}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> แก้ไข</a>
                                                            <button type="button" class="btn btn-danger btn-sm" onclick="delType(this)" data-id="{{$value->SysAngelType}}"><i class="fa fa-trash"></i> ลบ</button>
                                                    @else
                                                    -
                                                    @endif
                                                    
                                                            
                                                    </td>
                                                </tr>
                                            @endforeach
                                            
                                            
                                        </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div role="tabpanel" class="tab-pane" id="health">
                    <div class="block">
                        <form id="formFilter3">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="block-body">
                                        <div class="form-group">
                                            <label class="form-control-label">รหัสพนักงาน</label>
                                            <div class="input-group">
                                            <input type="text" class="form-control" name="pretty_code">
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-primary">ค้นหา</button>
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="block-body">
                                        <div class="form-group">
                                            <label class="form-control-label">เดือน</label>
                                            <select class="form-control" name="month">
                                                <option>มกราคม</option>
                                                <option>กุมภาพันธ์</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="block-body">
                                        <div class="form-group">
                                            <label class="form-control-label">ปี</label>
                                            <select class="form-control" name="year">
                                                <option>2561</option>
                                                <option>2560</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="block-body">
                                        <div class="form-group">
                                            <label class="form-control-label">&nbsp; </label>
                                            <div>
                                                <input type="button" value="ค้นหา" onclick="" id="btn-search" class="btn btn-info">
                                                <input type="reset" value="ยกเลิก" class="btn btn-danger">
                                            </div>
                                            
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="block ">
                                <div class="ajax-paging-block">
                                    <div class="loading-box hide"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i><br/><br/>Loading...</div>
                                    <div class="table-responsive"> 
                                        <table class="table table-bordered table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th class="text-center">วันที่</th>
                                                <th class="text-center">ชื่อแพทย์</th>
                                                <th class="text-center">วันที่บันทึกรายการ</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>xxx</td>
                                                <td>xxx</td>
                                                <td>xxx</td>
                                            </tr>
                                        </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="block">
                                <div class="title"><strong>บันทึกข้อมูล</strong></div>
                                <form id="formSave3">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="block-body">
                                                <div class="form-group">
                                                    <label class="form-control-label">วันที่ตรวจ</label>
                                                    <input type="text" name="date_check" placeholder="" class="form-control" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="block-body">
                                                <div class="form-group">
                                                    <label class="form-control-label">ชื่อแพทย์</label>
                                                    <input type="text" name="doc_name" placeholder="" class="form-control" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="block-body">
                                                <div class="form-group">
                                                    <label class="form-control-label">&nbsp; </label>
                                                    <div>
                                                        <input type="button" value="บันทึก" onclick="" id="btn-search" class="btn btn-info">
                                                        <input type="reset" value="ยกเลิก" class="btn btn-danger">
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div> -->
            </div>

            
        </div>
    </section>
    <form id="formPrettyDel" method="post" action="{{Config::get('app.context')}}prettydel">
        <input name="del_id" type="hidden" value=""/>
        {{ csrf_field() }}
    </form>
    <form id="formPrettyTypeDel" method="post" action="{{Config::get('app.context')}}prettytypedel">
        <input name="del_id" type="hidden" value=""/>
        {{ csrf_field() }}
    </form>
@stop

@section('script')
<script>
function delPretty(element){
    var data_id = $(element).attr("data-id");
    $.confirm({
    theme: 'dark',
    type:'red',
    title: 'ยืนยันการลบ!',
    content: 'ต้องการลบรายการนี้หรือไม่?',
    buttons: {
        ยืนยัน: 
        {
            btnClass: 'btn-primary',
            action:function () {
                var $form = $("#formPrettyDel");
                $form.find("input[name=del_id]").val(data_id);
                $form.submit();
            }
        },
        ยกเลิก: {
            btnClass: ''
        }
    }
    });
}
function delType(element){
    var data_id = $(element).attr("data-id");
    $.confirm({
    theme: 'dark',
    type:'red',
    title: 'ยืนยันการลบ!',
    content: 'ต้องการลบรายการนี้หรือไม่?',
    buttons: {
        ยืนยัน: 
        {
            btnClass: 'btn-primary',
            action:function () {
                var $form = $("#formPrettyTypeDel");
                $form.find("input[name=del_id]").val(data_id);
                $form.submit();
            }
        },
        ยกเลิก: {
            btnClass: ''
        }
    }
    });
}
function formSearch(){
   //$("#employee .paging-block").removeClass("hide");
    loadData(1,$(".paging-perpage").val());
}
function gotopage(element){
    loadData($(element).attr("data-page"),$(".paging-perpage").val());
}
function loadData(page,perpage){
    var params = $('#formFilter').serialize()+"&page="+page+"&perpage="+perpage;
    console.log(params);
    var $loading = $("#pretty_list .ajax-paging-block .loading-box");
    $loading.removeClass("hide");
    var $box = $("#pretty_list .ajax-paging-block");
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
    $.ajax({
        url: "ajax/loadpretty",
        type: "POST",
        data:params,
        success: function(data){
            $box.html(data);
            $loading.addClass("hide");
        }
    });
}
</script>
@stop
