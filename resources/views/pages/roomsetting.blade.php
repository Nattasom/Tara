@extends('layouts.default')
@section('title')
จัดการห้องพัก
@stop
@section('content')
    <div id="page-menu-key" data-menu="m_roomsetting" data-sub="room_ddl" data-url="roomsetting" ></div>
    <!-- Page Header-->
    <div class="page-header no-margin-bottom">
        <div class="container-fluid">
        <h2 class="h5 no-margin-bottom">จัดการห้องพัก</h2>
        </div>
    </div>
    <!-- Breadcrumb-->
    <div class="container-fluid">
        <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">ดูแลห้องพัก</a></li>
        <li class="breadcrumb-item active">จัดการห้องพัก</li>
        </ul>
    </div>
    <section class="text-right gap-bottom">
        <div class="container-fluid">
        @if($flag_edit)
            <a href="roomadd" class="btn btn-success" ><i class="fa fa-plus fa-fw"></i> เพิ่มห้องพัก</a>
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
                                    <label class="form-control-label">ห้อง</label>
                                    <input type="text" name="roomno" placeholder="" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="block-body">
                                <div class="form-group">
                                    <label class="form-control-label">ชั้น</label>
                                    <select class="form-control" name="floor">
                                    <option value="">ทั้งหมด</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="block-body">
                                <div class="form-group">
                                    <label class="form-control-label">ประเภทห้อง</label>
                                    <select class="form-control" name="type"><!--query-->
                                    <option value="">ทั้งหมด</option>
                                        @foreach($roomtypelist as $key=>$value)
                                            <option value="{{$value->SysRoom_Type_ID}}">{{$value->Room_Type_Desc}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="block-body">
                                <div class="form-group">
                                    <label class="form-control-label">สถานะ</label>
                                    <select name="status" class="form-control" id="">
                                        <option value="">ทั้งหมด</option>
                                        <option value="VA">ว่าง</option>
                                        <option value="IN">ใช้งาน</option>
                                        <option value="CL">ปิดปรับปรุง</option>
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
                            <strong>เรียบร้อย !</strong> เพิ่มห้องเรียบร้อย
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
                                    <th>ห้อง</th>
                                    <th>ชั้น</th>
                                    <th>ประเภทห้อง</th>
                                    <th>สถานะ</th>
                                    <th>แก้ไข</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($roomlist as $key=>$value)
                                        <tr>
                                            <th scope="row">{{$value->Room_No}}</th>
                                            <td>{{$value->Floor}}</td>
                                            <td>{{$value->Room_Type_Desc}}</td>
                                            <td>
                                            @if($value->Room_Status == 'IN')
                                                <span class="text-danger">{{$value->Room_Status_Text}}</span>
                                            @elseif($value->Room_Status == 'VA')
                                                <span class="text-success">{{$value->Room_Status_Text}}</span>
                                            @elseif($value->Room_Status == 'CL')
                                            <span class="text-info">{{$value->Room_Status_Text}}</span>
                                            @endif
                                            
                                            </td>
                                            <td>
                                            @if($flag_edit)
                                                <a class="btn btn-primary btn-sm" href="{{Config::get('app.context')}}roomedit/{{$value->SysRoom_ID}}"><i class="fa fa-edit"></i> แก้ไข</a>
                                                <button type="button" class="btn btn-danger btn-sm btn-del" data-id="{{$value->SysRoom_ID}}"><i class="fa fa-trash"></i> ลบ</button>
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
                        <form id="formDel" method="post" action="{{Config::get('app.context')}}roomdel">
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
        url: "ajax/loadroom",
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
