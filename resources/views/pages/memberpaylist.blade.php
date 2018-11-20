@extends('layouts.default')
@section('title')
ค้นหาข้อมูลชำระเงินสมาชิก
@stop
@section('content')
    <div id="page-menu-key" data-menu="m_member" data-sub="member_ddl" data-url="memberpay" ></div>
    <!-- Page Header-->
    <div class="page-header no-margin-bottom">
        <div class="container-fluid">
        <h2 class="h5 no-margin-bottom">ค้นหาข้อมูลชำระเงินสมาชิก</h2>
        </div>
    </div>
    <!-- Breadcrumb-->
    <div class="container-fluid">
        <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">สมาชิก</a></li>
        <li class="breadcrumb-item active">ค้นหาข้อมูลชำระเงินสมาชิก</li>
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
                                <label class="form-control-label">วันที่</label>
                                    <div class="input-group"> 
                                        <input type="text" class="form-control readonly " name="datetrans" >
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
                                    <th>วันที่ทำรายการ</th>
                                    <th>หมายเลขสมาชิก</th>
                                    <th>ยอดชำระ</th>
                                    <th>สูท</th>
                                    <th>Gold</th>
                                    <th>Black</th>
                                    <th>Beer</th>
                                    <th>ประเภทชำระ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($memberlist as $key=>$value)
                                        <tr>
                                            <td>{{$util->dateToThaiFormat($value->Paid_Date)}}</td>
                                            <td>{{$value->Member_ID}}:{{$value->First_Name}}</td>
                                            <td>{{$util->numberFormat($value->Add_Credit)}}</td>
                                            <td>{{$value->Add_Suite}}</td>
                                            <td>{{$value->Add_Whisky}}</td>
                                            <td>{{$value->Add_Whisky2}}</td>
                                            <td>{{$value->Add_Beer}}</td>
                                            <td>
                                                @php($strType = "")
                                                @if($value->Cash_Amt > 0)
                                                    @php($strType .= "เงินสด")
                                                @endif
                                                @if($value->Credit_Amt > 0)
                                                    @if($strType != "")
                                                        @php($strType .= ",")
                                                        @php($strType .= "บัตรเครดิต")
                                                    @endif
                                                @endif
                                                {{$strType}}
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
@stop

@section('script')
<script>
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
        url: "ajax/loadmemberpay",
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
