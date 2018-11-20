@extends('layouts.default')
@section('title')
รายงานสรุปการทำงานพนักงานเชียร์แขก
@stop
@section('content')
    <div id="page-menu-key" data-menu="m_report" data-sub="report_ddl" data-url="receptionwork" ></div>
    <!-- Page Header-->
    <div class="page-header no-margin-bottom">
        <div class="container-fluid">
        <h2 class="h5 no-margin-bottom">รายงานสรุปการทำงานพนักงานเชียร์แขก</h2>
        </div>
    </div>
    <!-- Breadcrumb-->
    <div class="container-fluid">
        <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">รายงาน</a></li>
        <li class="breadcrumb-item active">รายงานสรุปการทำงานพนักงานเชียร์แขก</li>
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
                                    <label class="form-control-label">วันที่</label>
                                    <div class="input-group"> 
                                        <input type="text" class="form-control readonly " onchange="chooseDate()"  name="datestart" value=""  >
                                        <span class="input-group-btn"> 
                                            <button class="btn btn-info" id="dpd1" type="button"><i class="fa fa-calendar"></i></button> 
                                        </span> 
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="block-body">
                                <div class="form-group">
                                    <label class="form-control-label">ถึง</label>
                                    <div class="input-group date"> 
                                        <input type="text" class="form-control readonly" onchange="chooseDate()"  name="dateend" value="" required>
                                        <span class="input-group-btn"> 
                                            <button class="btn btn-info" id="dpd2"  type="button"><i class="fa fa-calendar"></i></button> 
                                        </span> 
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="block-body">
                                <div class="form-group">
                                <label class="form-control-label">พนักงานเชียร์แขก</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control readonly" name="recept_code">
                                        <input type="hidden" id="recept_id" onchange="chooseDate()"  name="recept_id">
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
                                    <label class="form-control-label">&nbsp; </label>
                                    <div>
                                        <a id="link_print" href="javascript:;" target="_blank" class="btn btn-info"><i class="fa fa-print"></i> พิมพ์เอกสาร</a>
                                        <input type="reset" value="ยกเลิก" class="btn btn-danger">
                                    </div>
                                    
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </form>
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
    chooseDate();

    $("#receiptModal").modal("hide");

});
$("#receiptModal").on("show.bs.modal",function(){
    loadDataReceipt(1,10);
});
function gotopage(element){
    var $parent = $(element).parents(".ajax-paging-block");
    var type = $parent.attr("data-type");
    if(type=="receipt"){
        loadDataReceipt($(element).attr("data-page"),10);
    }
}
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
        url: "{{Config::get('app.context')}}ajax/loadreceiptpopup",
        type: "POST",
        data:params,
        success: function(data){
            $box.html(data);
            $loading.addClass("hide");
        }
    });
}
var checkin = $('#dpd1').datepicker({
  language:"th-th",
  onRender: function(date) {
    return '';
  }
}).on('changeDate', function(ev) {
    var $input = $(ev.target).parents(".input-group").find("input");
    $input.val(ev.formatdate);
  if (ev.date.valueOf() > checkout.date.valueOf()) {
    var newDate = new Date(ev.date)
    // newDate.setDate(newDate.getDate() + 365);
    
    checkout._setDate(newDate,'date');
  }
  checkout.fill();
  checkin.hide();
  $input.trigger("change");
  $('#dpd2')[0].focus();
}).data('datepicker');
var checkout = $('#dpd2').datepicker({
language:"th-th",
  onRender: function(date) {
      var res= '';
      if(date.valueOf() < checkin.date.valueOf()){
        res = 'disabled';
      }
    return  res;
  }
}).on('changeDate', function(ev) {
    var $input = $(ev.target).parents(".input-group").find("input");
    $input.val(ev.formatdate);
    $input.trigger("change");
  checkout.hide();
}).data('datepicker');

function chooseDate(){
    console.log("trigger !");
    if($("#recept_id").val()==""){
        $("#link_print").attr("href","javascript:;");
        return ;
    }
    $("#link_print").attr("href","{{Config::get('app.context')}}report/receptwork-print?start="+$("#dpd1").parents(".input-group").find("input").val()+"&end="+$("#dpd2").parents(".input-group").find("input").val()+"&recept="+$("#recept_id").val());
}
</script>
@stop
