@extends('layouts.default')
@section('title')
จ่ายค่าตอบแทน
@stop
@section('content')
    <div id="page-menu-key" data-menu="m_pay" data-sub="" data-url="prettypay" ></div>
    <!-- Page Header-->
    <div class="page-header no-margin-bottom">
        <div class="container-fluid">
        <h2 class="h5 no-margin-bottom">จ่ายค่าตอบแทน</h2>
        </div>
    </div>
    <!-- Breadcrumb-->
    <div class="container-fluid">
        <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">พนักงาน</a></li>
        <li class="breadcrumb-item active">จ่ายค่าตอบแทน</li>
        </ul>
    </div>
    <section class="no-padding-top">
        <div class="container-fluid">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" ><a class="active" href="#notrec"  role="tab" data-toggle="tab">รายการยังไม่ได้รับ</a></li>
                <li role="presentation"><a href="#rec"  role="tab" data-toggle="tab">รายการรับแล้ว</a></li>
                
            </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="notrec">
                    <div class="block">
                        <form id="formFilter">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="block-body">
                                        <div class="form-group">
                                            <label class="form-control-label">วันที่ทำรายการ</label>
                                            <div class="input-group"> 
                                                <input type="text" class="form-control readonly "  name="datetrans" value=""  >
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
                                            <label class="form-control-label">รหัสพนักงาน</label>
                                            <input type="text" name="prcode" placeholder="" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="block-body">
                                        <div class="form-group">
                                            <label class="form-control-label">ชื่อเล่น</label>
                                            <input type="text" name="prname" placeholder="" class="form-control">
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
                                <div  class="ajax-paging-block" data-type="pay">
                                    <div class="loading-box hide"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i><br/><br/>Loading...</div>
                                    <div class="table-responsive"> 
                                        <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                            <th>วันที่</th>
                                            <th>พนักงาน</th>
                                            <th>รอบ</th>
                                            <th>ค่าตอบแทน</th>
                                            <th>ค่าการ์ด/แม่บ้าน</th>
                                            <!-- <th>ค่าเสริมสวย</th>
                                            <th>หักหนี้ค้างชำระ</th> -->
                                            <th>ยอดรวม</th>
                                            <!-- <th>รับแล้ว</th> -->
                                            <th>จ่ายค่าตอบแทน</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($pretty_list as $key=>$value)
                                                <tr>
                                                    
                                                    <td>{{$util->dateToThaiFormat($value->Txn_Date)}}</td>
                                                    <td>{{$value->Angel_ID}}</td>
                                                    <td>{{$value->Round}}</td>
                                                    <td>{{$util->numberFormat($value->Wage_Amt)}}</td>
                                                    <td>{{$util->numberFormat($value->Comm)}}</td>
                                                    <!-- <td>{{$util->numberFormat($value->Makeup)}}</td>
                                                    <td>{{$util->numberFormat($value->Debt)}}</td> -->
                                                    <td>
                                                        <!-- {{$util->numberFormat(($value->Wage_Amt)-$value->Comm-$value->Makeup-$value->Debt)}} -->
                                                        {{$util->numberFormat(($value->Wage_Amt-$value->Comm))}}
                                                    </td>
                                                    <!-- <td>
                                                        {{$util->numberFormat($value->Receive)}}
                                                    </td> -->
                                                    <td><a href="{{Config::get('app.context')}}prettywage/{{$value->SysAngel_ID}}?txndate={{$value->Txn_Date}}" class="btn btn-success btn-xs"><i class="fa fa-usd"></i></a></td>
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
                <div role="tabpanel" class="tab-pane" id="rec">
                    <div class="block">
                        <form id="formFilter1">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="block-body">
                                        <div class="form-group">
                                            <label class="form-control-label">วันที่ทำรายการ</label>
                                            <div class="input-group"> 
                                                <input type="text" class="form-control readonly "  name="datetrans" value=""  >
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
                                            <label class="form-control-label">รหัสพนักงาน</label>
                                            <input type="text" name="prcode" placeholder="" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="block-body">
                                        <div class="form-group">
                                            <label class="form-control-label">ชื่อเล่น</label>
                                            <input type="text" name="prname" placeholder="" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="block-body">
                                        <div class="form-group">
                                            <label class="form-control-label">&nbsp; </label>
                                            <div>
                                                <input type="button" value="ค้นหา" onclick="formSearch1()" id="btn-search" class="btn btn-info">
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
                            <div id="pretty_list_1" class="block paging-block">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="paging-header" >
                                            <label>แสดง 
                                                <select  class="form-control form-control-sm paging-perpage" onchange="formSearch1()">
                                                    <option value="10">10</option>
                                                    <option value="30">30</option>
                                                    <option value="50">50</option>
                                                </select> รายการ
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div  class="ajax-paging-block" data-type="rec">
                                    <div class="loading-box hide"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i><br/><br/>Loading...</div>
                                    <div class="table-responsive"> 
                                        <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                            <th>วันที่</th>
                                            <th>พนักงาน</th>
                                            <th>รอบ</th>
                                            <th>ค่าตอบแทน</th>
                                            <th>ค่าการ์ด/แม่บ้าน</th>
                                            <th>ค่าเสริมสวย</th>
                                            <th>หักหนี้ค้างชำระ</th>
                                            <th>รับแล้ว</th>
                                            <!-- <th>จ่ายค่าตอบแทน</th> -->
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($pretty_list1 as $key=>$value)
                                                <tr>
                                                    
                                                    <td>{{$util->dateToThaiFormat($value->Txn_Date)}}</td>
                                                    <td>{{$value->Angel_ID}}</td>
                                                    <td>{{$value->Round}}</td>
                                                    <td>{{$util->numberFormat($value->Wage_Amt)}}</td>
                                                    <td>{{$util->numberFormat($value->Comm)}}</td>
                                                    <td>{{$util->numberFormat($value->Makeup)}}</td>
                                                    <td>{{$util->numberFormat($value->Debt)}}</td>
                                                    <td>
                                                        {{$util->numberFormat($value->Receive)}}
                                                    </td>
                                                    <!-- <td>
                                                    <a href="{{Config::get('app.context')}}prettywage/{{$value->SysAngel_ID}}?txndate={{$value->Txn_Date}}" class="btn btn-success btn-xs"><i class="fa fa-usd"></i></a>
                                                    </td> -->
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        </table>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="paging-detail-text">{{$paging1->paging_detail}}</div>
                                        </div>
                                        <div class="col-md-7 text-md-right">
                                            <nav >
                                                <ul class="pagination">
                                                    {!!$paging1->renderHtml()!!}
                                                </ul>
                                            </nav>
                                        </div>
                                    </div> 
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
    loadData(1,$("#pretty_list .paging-perpage").val());
}
function formSearch1(){
   //$("#employee .paging-block").removeClass("hide");
    loadData1(1,$("#pretty_list_1 .paging-perpage").val());
}
function gotopage(element){
    var type = $(element).parents(".ajax-paging-block").attr("data-type");
    if(type=="rec"){
        loadData1($(element).attr("data-page"),$("#pretty_list_1 .paging-perpage").val());
    }else{
        loadData($(element).attr("data-page"),$("#pretty_list .paging-perpage").val());
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
        url: "ajax/loadprettypay",
        type: "POST",
        data:params,
        success: function(data){
            $box.html(data);
            $loading.addClass("hide");
        }
    });
}
function loadData1(page,perpage){
    var params = $('#formFilter1').serialize()+"&page="+page+"&perpage="+perpage;
    console.log(params);
    var $loading = $("#pretty_list_1 .ajax-paging-block .loading-box");
    $loading.removeClass("hide");
    var $box = $("#pretty_list_1 .ajax-paging-block");
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
    $.ajax({
        url: "ajax/loadprettypayrec",
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
