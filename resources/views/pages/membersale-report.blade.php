@extends('layouts.default')
@section('title')
รายงานยอดขายเมมเบอร์
@stop
@section('content')
    <div id="page-menu-key" data-menu="m_report" data-sub="report_ddl" data-url="membersale" ></div>
    <!-- Page Header-->
    <div class="page-header no-margin-bottom">
        <div class="container-fluid">
        <h2 class="h5 no-margin-bottom">รายงานยอดขายเมมเบอร์</h2>
        </div>
    </div>
    <!-- Breadcrumb-->
    <div class="container-fluid">
        <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">รายงาน</a></li>
        <li class="breadcrumb-item active">รายงานยอดขายเมมเบอร์</li>
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
                                    <label class="form-control-label">&nbsp; </label>
                                    <div>
                                        <a id="link_print" href="{{Config::get('app.context')}}report/membersale-print?date=" target="_blank" class="btn btn-info"><i class="fa fa-print"></i> พิมพ์เอกสาร</a>
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
@stop

@section('script')
<script>
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
    $("#link_print").attr("href","{{Config::get('app.context')}}report/membersale-print?start="+$("#dpd1").parents(".input-group").find("input").val()+"&end="+$("#dpd2").parents(".input-group").find("input").val());
}
</script>
@stop
