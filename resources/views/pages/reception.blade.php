@extends('layouts.default')
@section('title')
จัดการข้อมูลเชียร์แขก
@stop
@section('content')
    <div id="page-menu-key" data-menu="m_employee" data-sub="employee_ddl" data-url="reception" ></div>
    <!-- Page Header-->
    <div class="page-header no-margin-bottom">
        <div class="container-fluid">
        <h2 class="h5 no-margin-bottom">จัดการข้อมูลเชียร์แขก</h2>
        </div>
    </div>
    <!-- Breadcrumb-->
    <div class="container-fluid">
        <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">พนักงาน</a></li>
        <li class="breadcrumb-item active">จัดการข้อมูลเชียร์แขก</li>
        </ul>
    </div>
    <section class="no-padding-top">
        <div class="container-fluid">
            
            @if($flag_edit)
                <div class="text-right gap-bottom">
                <a href="receptionadd" class="btn btn-success" ><i class="fa fa-plus fa-fw"></i> เพิ่มพนักงานเชียร์แขก</a>
                </div>
            @endif
            
                    <div class="block">
                        <form id="formFilter">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="block-body">
                                        <div class="form-group">
                                            <label class="form-control-label">รหัสพนักงาน</label>
                                            <input type="text" name="receptcode" placeholder="" class="form-control">
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
                                            <input type="text" placeholder="" name="receptname" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="block-body">
                                        <div class="form-group">
                                            <label class="form-control-label">นามสกุล</label>
                                            <input type="text" placeholder="" name="receptlastname" class="form-control">
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
                                <strong>เรียบร้อย !</strong> เพิ่มพนักงานเรียบร้อย
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
                                            <th>หมายเลขพนักงานต้อนรับ</th>
                                            <th>ชื่อพนักงาน</th>
                                            <th>ชื่อเล่น</th>
                                            <th>วันที่ปฏิบัติงาน</th>
                                            <th>จัดการ</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($list as $key=>$value)
                                                <tr>
                                                    <th scope="row">{{++$paging->start}}</th>
                                                    <td>{{$value->Reception_ID}}</td>
                                                    <td>{{$value->First_Name}} {{$value->Last_Name}}</td>
                                                    <td>{{$value->Nick_Name}}</td>
                                                    <td>{{$value->Work_From_Date}}</td>
                                                    <td>
                                                    @if($flag_edit)
                                                        <a href="{{Config::get('app.context')}}receptionedit/{{$value->SysReception_ID}}" class="btn btn-primary btn-sm" ><i class="fa fa-edit"></i> แก้ไข</a>
                                                        <button class="btn btn-danger btn-sm btn-del" data-id="{{$value->SysReception_ID}}"><i class="fa fa-trash"></i> ลบ</button>
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
                <form id="formDel" method="post" action="{{Config::get('app.context')}}receptdel">
                    <input name="del_id" type="hidden" value=""/>
                    {{ csrf_field() }}
                </form>
            
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
        url: "ajax/loadreceipt",
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
