@extends('layouts.default')
@section('title')
จัดการประเภทห้องพัก
@stop
@section('content')
    <div id="page-menu-key" data-menu="m_roomsetting" data-sub="room_ddl" data-url="roomtypesetting" ></div>
    <!-- Page Header-->
    <div class="page-header no-margin-bottom">
        <div class="container-fluid">
        <h2 class="h5 no-margin-bottom">จัดการประเภทห้องพัก</h2>
        </div>
    </div>
    <!-- Breadcrumb-->
    <div class="container-fluid">
        <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">ดูแลห้องพัก</a></li>
        <li class="breadcrumb-item active">จัดการประเภทห้องพัก</li>
        </ul>
    </div>
    <section class="text-right gap-bottom">
        <div class="container-fluid">
        @if($flag_edit)
<a href="roomtypeadd" class="btn btn-success" ><i class="fa fa-plus fa-fw"></i> เพิ่มประเภทห้องพัก</a>
        @endif
        </div>
    </section>
    <section class="no-padding-top">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
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
                                    <th>รหัสประเภท</th>
                                    <th>ประเภท</th>
                                    <th>แก้ไข</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($roomtypelist as $key=>$value)
                                        <tr>
                                            <td>{{$value->Room_Type_Code}}</td>
                                            <td>{{$value->Room_Type_Desc}}</td>
                                            <td>
                                            @if($flag_edit)
                                                <a class="btn btn-primary btn-sm" href="roomtypeedit/{{$value->SysRoom_Type_ID}}"><i class="fa fa-edit"></i> แก้ไข</a>
                                                <button class="btn btn-danger btn-sm btn-del" data-id="{{$value->SysRoom_Type_ID}}"><i class="fa fa-trash"></i> ลบ</button>
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
                    <form id="formDel" method="post" action="{{Config::get('app.context')}}roomtypedel">
                        <input name="del_id" type="hidden" value=""/>
                        {{ csrf_field() }}
                    </form>
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
    var params = "page="+page+"&perpage="+perpage;
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
        url: "ajax/loadroomtype",
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
