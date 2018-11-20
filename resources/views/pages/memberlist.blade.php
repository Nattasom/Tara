@extends('layouts.default')
@section('title')
ค้นหาสมาชิก
@stop
@section('content')
    <div id="page-menu-key" data-menu="m_member" data-sub="member_ddl" data-url="member" ></div>
    <!-- Page Header-->
    <div class="page-header no-margin-bottom">
        <div class="container-fluid">
        <h2 class="h5 no-margin-bottom">ค้นหาสมาชิก</h2>
        </div>
    </div>
    <!-- Breadcrumb-->
    <div class="container-fluid">
        <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">สมาชิก</a></li>
        <li class="breadcrumb-item active">ค้นหาสมาชิก</li>
        </ul>
    </div>
    <section class="text-right gap-bottom">
        <div class="container-fluid">
        @if($flag_add)
            <a href="memberadd" class="btn btn-success" ><i class="fa fa-plus fa-fw"></i> เพิ่มสมาชิก</a>
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
                                <label class="form-control-label">หมายเลขสมาชิก</label>
                                <input type="text" name="member_no" placeholder="" class="form-control">
                                </div>
                            </div>
                        </div>  
                        <div class="col-md-4">
                            <div class="block-body">
                                <div class="form-group">
                                <label class="form-control-label">ชื่อสมาชิก</label>
                                <input type="text" name="member_name" placeholder="" class="form-control">
                                </div>
                            </div>
                        </div> 
                        <div class="col-md-4">
                            <div class="block-body">
                                <div class="form-group">
                                <label class="form-control-label">นามสกุลสมาชิก</label>
                                <input type="text" name="member_lastname" placeholder="" class="form-control">
                                </div>
                            </div>
                        </div> 
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="block-body">
                                <div class="form-group">
                                <label class="form-control-label">พนักงานเชียร์แขก</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control readonly" name="recept_code">
                                        <input type="hidden"  name="recept_id">
                                        <div class="input-group-append">
                                            <button type="button" data-toggle="modal" data-target="#receiptModal" class="btn btn-info"><i class="fa fa-search"></i></button>
                                            <button type="button" class="btn btn-danger hide" id="clearParent"  onclick="clearInputGroupText(this)" ><i class="fa fa-trash"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> 
                        <div class="col-md-4">
                            <div class="block-body">
                                <div class="form-group">
                                <label class="form-control-label">สถานะ</label>
                                <select name="member_status" id="" class="form-control">
                                    <option value="">ทั้งหมด</option>
                                    @foreach($member_status as $key=>$value)
                                        <option value="{{$value->Ref_Code}}">{{$value->Ref_Desc}}</option>
                                    @endforeach
                                </select>
                                </div>
                            </div>
                        </div> 
                        <div class="col-md-4">
                            <div class="block-body">
                                <div class="form-group">
                                <label class="form-control-label">เดือนที่หมดอายุ</label>
                                <select name="member_month" id="" class="form-control">
                                    <option value="">ทั้งหมด</option>
                                    @foreach($month as $key=>$value)
                                        <option value="{{$key}}" >{{$value}}</option>
                                    @endforeach
                                </select>
                                </div>
                            </div>
                        </div> 
                        <div class="col-md-4">
                            <div class="block-body">
                                <div class="form-group">
                                <label class="form-control-label">ปีที่หมดอายุ</label>
                                <select name="member_year" id="" class="form-control">
                                    <option value="">ทั้งหมด</option>
                                    @foreach($yearlist as $key=>$value)
                                        <option value="{{$key}}" >{{$value}}</option>
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
                    @if($status == "success")
                        <div class="alert alert-success alert-dismissible in" role="alert"> 
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button> 
                            <strong>เรียบร้อย !</strong> เพิ่มสมาชิกเรียบร้อย
                        </div>
                    @elseif($status=="success_del")
                        <div class="alert alert-success alert-dismissible in" role="alert"> 
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button> 
                            <strong>เรียบร้อย !</strong> ลบรายการเรียบร้อย
                        </div>
                    @endif
                    <div id="member-zone" class="block paging-block">
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
                        <div data-type="reg" class="ajax-paging-block">
                            <div class="loading-box hide"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i><br/><br/>Loading...</div>
                            <div class="table-responsive"> 
                                <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                    <th>หมายเลขสมาชิก</th>
                                    <th>ชื่อสมาชิก</th>
                                    <th>ยอดคงเหลือ</th>
                                    <th>วันหมดอายุ</th>
                                    <th>พนักงานต้อนรับ</th>
                                    <th>แก้ไข</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($memberlist as $key=>$value)
                                        <tr>
                                            <th scope="row">{{$value->Member_ID}}</th>
                                            <td>{{$value->First_Name}} {{$value->Last_Name}}</td>
                                            <td>{{$util->numberFormat($value->Credit_Amt)}}</td>
                                            <td>{{$util->dateToThaiFormat($value->Expired_Date)}}</td>
                                            <td>{{$value->Recept_Name}} {{$value->Recept_Lastname}}</td>
                                            <td>
                                            @if($flag_edit)
                                                <a class="btn btn-primary btn-sm" href="{{Config::get('app.context')}}memberedit/{{$value->SysMember_ID}}"><i class="fa fa-edit"></i> แก้ไข</a>
                                                <a class="btn btn-success btn-sm" href="{{Config::get('app.context')}}membertopup/{{$value->SysMember_ID}}"><i class="fa fa-usd"></i> เติมเงิน</a>
                                                <a class="btn btn-info btn-sm" href="{{Config::get('app.context')}}memberdeduct/{{$value->SysMember_ID}}"><i class="fa fa-minus"></i> ตัดยอด</a>
                                                <button type="button" class="btn btn-danger btn-sm btn-del" data-id="{{$value->SysMember_ID}}"><i class="fa fa-trash"></i> ลบ</button>
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
                        <form id="formDel" method="post" action="{{Config::get('app.context')}}memberdel">
                            <input name="del_id" type="hidden" value=""/>
                            {{ csrf_field() }}
                        </form>
                        
                    </div>
                    
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="receiptModal" tabindex="-1" role="dialog" >
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" i>เลือกเชียร์แขก</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div data-type="receipt" class="ajax-paging-block">
                    <div class="loading-box hide"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i><br/><br/>Loading...</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
        </div>
@stop

@section('script')
<script>
$(document).on("click","#receiptModal .selected-item",function(){
    var data_id = $(this).attr("data-id");
    var data_code = $(this).attr("data-code");
    var name = $(this).find(".text-name").text();
    $("#clearParent").removeClass("hide");
    $("#formFilter input[name='recept_code']").val(data_code+":"+name);
    $("#formFilter input[name='recept_id']").val(data_id);
    //$("#formFilter input[name='recept_name']").val(name);
    
    $("#receiptModal").modal("hide");

});
$("#receiptModal").on("show.bs.modal",function(){
    loadDataReceipt(1,10);
});
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
function loadDataReceipt(page,perpage){
    var params = "page="+page+"&perpage="+perpage;
    //console.log(params);
    var $loading = $("#receiptModal .ajax-paging-block .loading-box");
    $loading.removeClass("hide");
    var $box = $("#receiptModal .ajax-paging-block");
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
    $.ajax({
        url: "ajax/loadreceiptpopup",
        type: "POST",
        data:params,
        success: function(data){
            $box.html(data);
            $loading.addClass("hide");
        }
    });
}
function formSearch(){
    loadData(1,$("#member-zone .paging-perpage").val());
}
function gotopage(element){
    var $parent = $(element).parents(".ajax-paging-block");
    var type = $parent.attr("data-type");
    if(type=="receipt"){
        loadDataReceipt($(element).attr("data-page"),10);
    }
    else{
        loadData($(element).attr("data-page"),$("#member-zone .paging-perpage").val());
    }
    
}
function loadData(page,perpage){
    var params = $('#formFilter').serialize()+"&page="+page+"&perpage="+perpage;
    //console.log(params);
    var $loading = $("#member-zone .ajax-paging-block .loading-box");
    $loading.removeClass("hide");
    var $box = $("#member-zone .ajax-paging-block");
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
    $.ajax({
        url: "ajax/loadmember",
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
