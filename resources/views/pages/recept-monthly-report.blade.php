@extends('layouts.default')
@section('title')
รายงานสรุปยอดพนักงานเชียร์แขก (รายเดือน)
@stop
@section('content')
    <div id="page-menu-key" data-menu="m_report" data-sub="report_ddl" data-url="recept-monthly" ></div>
    <!-- Page Header-->
    <div class="page-header no-margin-bottom">
        <div class="container-fluid">
        <h2 class="h5 no-margin-bottom">รายงานสรุปยอดพนักงานเชียร์แขก (รายเดือน)</h2>
        </div>
    </div>
    <!-- Breadcrumb-->
    <div class="container-fluid">
        <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">รายงาน</a></li>
        <li class="breadcrumb-item active">รายงานสรุปยอดพนักงานเชียร์แขก (รายเดือน)</li>
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
                                    <label class="form-control-label">เดือน</label>
                                    <select class="form-control" name="month" id="sl_month" onchange = "chooseDate()">
                                        @foreach($month as $key=>$value)
                                            <option value="{{$key+1}}" {{($key+1)==$cur_month ? "selected":""}}>{{$value}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="block-body">
                                <div class="form-group">
                                    <label class="form-control-label">ปี</label>
                                    <select class="form-control" name="year" id="sl_year" onchange = "chooseDate()">
                                        @foreach($year as $key=>$value)
                                            <option value="{{$value}}" {{($value)==$cur_year ? "selected":""}}>{{$value}}</option>
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
                                        <a id="link_print" href="{{Config::get('app.context')}}report/recept-monthly-print?month={{$cur_month}}&year={{$cur_year}}" target="_blank" class="btn btn-info"><i class="fa fa-print"></i> พิมพ์เอกสาร</a>
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
function chooseDate(){
    console.log("trigger !");
    $("#link_print").attr("href","{{Config::get('app.context')}}report/recept-monthly-print?month="+$("#sl_month").val()+"&year="+$("#sl_year").val());
}
</script>
@stop
