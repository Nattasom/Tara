@extends('layouts.default')
@section('title')
ค้นหาข้อมูลชำระเงิน
@stop
@section('content')
    <div id="page-menu-key" data-menu="m_payment" data-sub="checkout_ddl" data-url="paymentlist" ></div>
    <!-- Page Header-->
    <div class="page-header no-margin-bottom">
        <div class="container-fluid">
        <h2 class="h5 no-margin-bottom">ค้นหาข้อมูลชำระเงิน</h2>
        </div>
    </div>
    <!-- Breadcrumb-->
    <div class="container-fluid">
        <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">ข้อมูลชำระเงิน</a></li>
        <li class="breadcrumb-item active">ค้นหาข้อมูลชำระเงิน</li>
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
                                    <label class="form-control-label">วันที่ทำรายการ</label>
                                    <div class="input-group"> 
                                        <input type="text" class="form-control readonly "  name="datetrans" value="{{$date}}"  >
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
                                    <th>วันที่ทำรายการ</th>
                                    <th>เลขที่รายการ</th>
                                    <th>วันที่ชำระเงิน</th>
                                    <th>เชียร์แขก</th>
                                    <th>ชื่อลูกค้า</th>
                                    <th>ห้อง</th>
                                    <th>พนักงาน</th>
                                    <th>ดู</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($list as $key=>$value)
                                        <tr>
                                            <td>{{$util->dateToThaiFormat($value->Txn_Date)}}</td>
                                            <td>{{str_replace('/','',$util->dateToThaiFormat($value->Txn_Date)).str_pad($value->Txn_No,5,'0',STR_PAD_LEFT)}}</td>
                                            <td>{{empty($value->Paid_Date) ? $util->dateToThaiFormat(date("Y-m-d",strtotime($value->LastUpd_Dtm))).' '.date("H:i",strtotime($value->LastUpd_Dtm)) : $util->dateToThaiFormat(date("Y-m-d",strtotime($value->Paid_Date))).' '.date("H:i",strtotime($value->Paid_Date))}}</td>
                                            <td>{{$value->Recept_Fullname}}</td>
                                            <td>{{$value->Member_Name}}</td>
                                            <td>{{$value->room}}</td>
                                            <td>{{$value->pretty}}</td>
                                            <td>
                                                <a class="btn btn-primary btn-xs" href="{{Config::get('app.context')}}paymentdetail?txn={{$value->Txn_No}}&txndate={{$value->Txn_Date}}"><i class="fa fa-eye"></i> ดูรายการ</a>
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
@stop

@section('script')
<script>
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
        url: "ajax/paymentlist",
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
