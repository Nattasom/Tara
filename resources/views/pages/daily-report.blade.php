@extends('layouts.default')
@section('title')
รายงานส่งเงินประจำวัน
@stop
@section('content')
    <div id="page-menu-key" data-menu="m_report" data-sub="report_ddl" data-url="daily" ></div>
    <!-- Page Header-->
    <div class="page-header no-margin-bottom">
        <div class="container-fluid">
        <h2 class="h5 no-margin-bottom">รายงานส่งเงินประจำวัน</h2>
        </div>
    </div>
    <!-- Breadcrumb-->
    <div class="container-fluid">
        <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">รายงาน</a></li>
        <li class="breadcrumb-item active">รายงานส่งเงินประจำวัน</li>
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
                                        <input type="text" class="form-control readonly " id="datetrans" onchange="chooseDate()"   name="datetrans" value="{{$date}}"  >
                                        <span class="input-group-btn"> 
                                            <button class="btn btn-info btn-datepicker"  type="button"><i class="fa fa-calendar"></i></button> 
                                        </span> 
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="block-body">
                                <div class="form-group">
                                    <label class="form-control-label">ตึก</label>
                                    <select class="form-control" id="sl-building" onchange="chooseDate()">
                                        @if($flag_building["1"] && $flag_building["2"])
                                            <option value="">ทั้งหมด</option>
                                        @endif
                                        @foreach($buildinglist as $key=>$value)
                                            @if($flag_building[$value->building_id])
                                                <option value="{{$value->building_id}}">{{$value->building_name}}</option>
                                            @endif
                                            
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
                                        <a id="link_print" href="#" target="_blank" class="btn btn-info"><i class="fa fa-print"></i> พิมพ์เอกสาร</a>
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
chooseDate();
function chooseDate(){
    console.log("trigger !");
    $("#link_print").attr("href","{{Config::get('app.context')}}report/daily-print?date="+$("#datetrans").val()+"&building="+$("#sl-building").val());
}
</script>
@stop
