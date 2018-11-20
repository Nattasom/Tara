@extends('layouts.default')
@section('title')
จัดการกลุ่มผู้ใช้งาน
@stop
@section('content')
    <div id="page-menu-key" data-menu="m_usersetting" data-sub="setting_ddl" data-url="userrole" ></div>
    <!-- Page Header-->
    <div class="page-header no-margin-bottom">
        <div class="container-fluid">
        <h2 class="h5 no-margin-bottom">จัดการข้อมูลกลุ่มผู้ใช้งาน</h2>
        </div>
    </div>
    <!-- Breadcrumb-->
    <div class="container-fluid">
        <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">จัดการข้อมูลระบบ</a></li>
        <li class="breadcrumb-item active">จัดการข้อมูลกลุ่มผู้ใช้งาน</li>
        </ul>
    </div>
    <section class="text-right gap-bottom">
        <div class="container-fluid">
        @if($flag_edit)
            <a href="userroleadd" class="btn btn-success" ><i class="fa fa-plus fa-fw"></i> เพิ่มกลุ่มผู้ใช้งาน</a>
        @endif
            
        </div>
    </section>
    <section class="no-padding-top">
        <div class="container-fluid">
            <div class="block">
                <form id="formFilter">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="block-body">
                                <div class="form-group">
                                    <label class="form-control-label">รหัสกลุ่มผู้ใช้</label>
                                    <input type="text" name="rolecode" placeholder="" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="block-body">
                                <div class="form-group">
                                    <label class="form-control-label">ชื่อกลุ่มผู้ใช้</label>
                                    <input type="text" placeholder="" name="rolename" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="block-body">
                                <div class="form-group">
                                    <label class="form-control-label">รายละเอียด</label>
                                    <input type="text" placeholder="" name="roledesc" class="form-control">
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
                    <div class="block paging-block">
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
                        <div class="ajax-paging-block">
                            <div class="loading-box hide"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i><br/><br/>Loading...</div>
                            <div class="table-responsive"> 
                                <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                    <th>#</th>
                                    <th>รหัสกลุ่มผู้ใช้</th>
                                    <th>ชื่อกลุ่มผู้ใช้</th>
                                    <th>รายละเอียด</th>
                                    <th>จัดการ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($rolelist as $key=>$value)
                                        <tr>
                                            <th scope="row">{{++$paging->start}}</th>
                                            <td>{{$value->Role_Code}}</td>
                                            <td>{{$value->Role_Name}}</td>
                                            <td>{{$value->Role_Desc}}</td>
                                            <td>
                                            @if($flag_edit)
                                                <a href="{{Config::get('app.context')}}userroleedit/{{$value->SysRole_ID}}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> แก้ไข</a >
                                                <a href="{{Config::get('app.context')}}userrolepermission/{{$value->SysRole_ID}}" class="btn btn-info btn-sm"><i class="fa fa-lock"></i> กำหนดสิทธิ์</a >
                                                <button class="btn btn-danger btn-sm btn-del" data-id = "{{$value->SysRole_ID}}" type="button"><i class="fa fa-trash"></i> ลบ</button>
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
                        <form id="formDel" method="post" action="{{Config::get('app.context')}}userroledel">
                            <input name="del_id" type="hidden" value=""/>
                            {{ csrf_field() }}
                        </form>
                        
                    </div>
                    
                </div>
            </div>
        </div>
    </section>
@stop

@section('script')
<script>
$(document).on("click",".btn-del",function(){
    var data_id = $(this).attr("data-id");
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
                var $form = $("#formDel");
                $form.find("input[name=del_id]").val(data_id);
                $form.submit();
            }
        },
        ยกเลิก: {
            btnClass: ''
        }
    }
    });
});
function formSearch(){
    loadData(1,$(".paging-perpage").val());
}
function gotopage(element){
    loadData($(element).attr("data-page"),$(".paging-perpage").val());
}
function loadData(page,perpage){
    var params = $('#formFilter').serialize()+"&page="+page+"&perpage="+perpage;
    //console.log(params);
    var $loading = $(".ajax-paging-block .loading-box");
    $loading.removeClass("hide");
    var $box = $(".ajax-paging-block");
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
    $.ajax({
        url: "ajax/loaduserrole",
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
