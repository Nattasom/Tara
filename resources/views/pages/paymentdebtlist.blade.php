@extends('layouts.default')
@section('title')
ค้นหาข้อมูลค้างชำระ
@stop
@section('content')
    <div id="page-menu-key" data-menu="m_payment" data-sub="checkout_ddl" data-url="paymentdebtlist" ></div>
    <!-- Page Header-->
    <div class="page-header no-margin-bottom">
        <div class="container-fluid">
        <h2 class="h5 no-margin-bottom">ค้นหาข้อมูลค้างชำระ</h2>
        </div>
    </div>
    <!-- Breadcrumb-->
    <div class="container-fluid">
        <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">ข้อมูลชำระเงิน</a></li>
        <li class="breadcrumb-item active">ค้นหาข้อมูลค้างชำระ</li>
        </ul>
    </div>
    <section class="no-padding-top">
        <div class="container-fluid">
            <div class="block">
                <form id="formFilter">
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
                                    <label class="form-control-label">วันที่ทำรายการ</label>
                                    <div class="input-group"> 
                                        <input type="text" class="form-control readonly "  name="datetrans"   >
                                        <span class="input-group-btn"> 
                                            <button class="btn btn-info btn-datepicker" id="" type="button"><i class="fa fa-calendar"></i></button> 
                                        </span> 
                                    </div>
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
                            <strong>เรียบร้อย !</strong> ชำระเงินเรียบร้อย
                        </div>
                    @elseif($status=="success_del")
                        <div class="alert alert-success alert-dismissible in" role="alert"> 
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button> 
                            <strong>เรียบร้อย !</strong> ลบรายการเรียบร้อย
                        </div>
                    @endif
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
                        <div data-type="debt" class="ajax-paging-block">
                            <div class="loading-box hide"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i><br/><br/>Loading...</div>
                            <div class="table-responsive"> 
                                <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                    <th>วันที่ทำรายการ</th>
                                    <th>พนักงานต้อนรับ</th>
                                    <th>หมายเหตุ</th>
                                    <th>จำนวนเงินที่ค้างจ่าย</th>
                                    <th>ชำระเงิน</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($list as $key=>$value)
                                        <tr>
                                            <td>{{$util->dateToThaiFormat($value->Txn_Date)}}</td>
                                            <td>{{$value->Recept_Name}}</td>
                                            <td>{{$value->Remark}}</td>
                                            <td>{{$util->numberFormat($value->Total_Unpaid)}}</td>
                                            <td>
                                                @if($flag_edit)
                                <a class="btn btn-primary btn-xs" href="{{Config::get('app.context')}}debtdetail?txn={{$value->Txn_No}}&txndate={{$value->Txn_Date}}"><i class="fa fa-usd"></i> ชำระเงิน</a>
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
   //$("#employee .paging-block").removeClass("hide");
    loadData(1,$(".paging-perpage").val());
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
        url: "ajax/paymentdebtlist",
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
