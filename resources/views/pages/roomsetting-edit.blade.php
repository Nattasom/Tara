@extends('layouts.default')
@section('title')
แก้ไขห้องพัก
@stop
@section('content')
    <div id="page-menu-key" data-menu="m_roomsetting" data-sub="room_ddl" data-url="roomsetting" ></div>
    <!-- Page Header-->
    <div class="page-header no-margin-bottom">
        <div class="container-fluid">
        <h2 class="h5 no-margin-bottom">แก้ไขห้องพัก</h2>
        </div>
    </div>
    <!-- Breadcrumb-->
    <div class="container-fluid">
        <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">ดูแลห้องพัก</a></li>
        <li class="breadcrumb-item"><a href="{{Config::get('app.context')}}roomsetting">จัดการห้องพัก</a></li>
        <li class="breadcrumb-item active">แก้ไขห้องพัก</li>
        </ul>
    </div>

    <section class="no-padding-top">
        <div class="container-fluid">
            <div class="block">
                <div class="block-body">
                    @if($status == "01")
                        <div class="alert alert-success alert-dismissible in" role="alert"> 
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button> 
                            <strong>เรียบร้อย !</strong> {{$message}}
                        </div>
                    @elseif($status == "00")
                        <div class="alert alert-warning alert-dismissible in" role="alert"> 
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button> 
                            <strong>ผิดพลาด !</strong> {{$message}}
                        </div>
                    @endif
                    <form class="form-horizontal" id="mainForm" method="post" action="" novalidate>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">หมายเลขห้อง <sup class="text-danger">*</sup></label>
                            <div class="col-sm-6">
                            <input type="text" class="form-control" name="roomno" value="{{$room->Room_No}}" required>
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">หมายเลขห้องพักหลัก</label>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="parent_code" value="{{$room->ParentRoom}}"  readonly>
                                    <input type="hidden" name="parent_id" value="{{$room->SysParent_Room_ID}}" />
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#roomModal"><i class="fa fa-search"></i></button>
                                        <button type="button" class="btn btn-danger hide" id="clearParent"  onclick="clearInputGroupText(this)" ><i class="fa fa-trash"></i></button>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">ตึก</label>
                            <div class="col-sm-6">
                            <select id="building" class="form-control" name="building">
                                    <option value="">กรุณาเลือก</option>
                                    @foreach($buildinglist as $key=>$value)
                                    <option value="{{$value->building_id}}" data-floor="{{$value->floor_count}}" {{$room->building_id == $value->building_id ? 'selected':''}}>{{$value->building_name}}</option>
                                    @endforeach
                                    </select>
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">ชั้น</label>
                            <div class="col-sm-6">
                                <select id="floor" class="form-control" name="floor">
                                    <option value="">กรุณาเลือก</option>
                                    
                                </select>
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">ประเภทห้อง <sup class="text-danger">*</sup></label>
                            <div class="col-sm-6">
                                <select class="form-control" name="type" required><!--query-->
                                    <option value="">กรุณาเลือก</option>
                                        @foreach($roomtypelist as $key=>$value)
                                            <option value="{{$value->SysRoom_Type_ID}}" {{$room->SysRoom_Type_ID == $value->SysRoom_Type_ID ? 'selected' : ''}}>{{$value->Room_Type_Desc}}</option>
                                        @endforeach
                                    </select>
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">สถานะ</label>
                            <div class="col-sm-6">
                                <select name="status" class="form-control" id="">
                                        <option value="VA" {{$room->Room_Status=='VA' ? 'selected':''}}>ว่าง</option>
                                        <option value="IN" {{$room->Room_Status=='IN' ? 'selected':''}}>ใช้งาน</option>
                                        <option value="CL" {{$room->Room_Status=='CL' ? 'selected':''}}>ปิดปรับปรุง</option>
                                    </select>
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-8 ml-auto">
                                <button type="submit" class="btn btn-primary">บันทึก</button>
                                <a href="{{Config::get('app.context')}}roomsetting" class="btn btn-secondary">กลับ</a>
                            </div>
                        </div>
                        {{ csrf_field() }}
                    </form>
                </div>
            </div>
            
        </div>
    </section>
    <!-- Modal -->
<div class="modal fade" id="roomModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
      <h4 class="modal-title" id="myModalLabel">เลือกห้องหลัก</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <form id="formPopFilter" class="form-horizontal">
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
                            <label class="form-control-label">ประเภท</label>
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
                            <label class="form-control-label">&nbsp; </label>
                            <div>
                                <input type="button" value="ค้นหา" onclick="formSearch()" id="btn-search" class="btn btn-info">
                            </div>
                            
                        </div>
                    </div>
                    
                </div>
            </div>
        </form>
        <div class="ajax-paging-block">
            
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
      </div>
    </div>
  </div>
</div>
@stop

@section('script')
<script>
$(document).ready(function(){
    $("#mainForm").validate({
        rules: {
            roomno: {
                required:true
            },
            building:{
                required:true
            },
            floor:{
                required:true
            },
            type:{
                required:true
            }
        },
            messages: {
                roomno: {
                    required:"กรุณากรอกข้อมูล"
                },
                building:{
                    required:"กรุณากรอกข้อมูล"
                },
                floor:{
                    required:"กรุณากรอกข้อมูล"
                },
                type:{
                    required:"กรุณากรอกข้อมูล"
                }
            }
        });
    if($("#building").val()!=""){
         var $floor  = $("#floor");
        $floor.find(".append-item").remove();
         var floor = $(this).find("option:selected").attr("data-floor");
         var servefloor = "{{$room->Floor}}";
        var appendhtml = '';
        if(parseInt(floor)>0){
            for(var i = 1;i <= floor;i++){
                appendhtml +='<option class="append-item" value="'+i+'" '+(servefloor==i?"selected":"")+'>'+i+'</option>';
            }
            
            $floor.append(appendhtml);
        }
    }
});
$(document).on("change","#building",function(){
    var bid = $(this).val();
    var $floor  = $("#floor");
    $floor.find(".append-item").remove();
    if(bid!=""){
        var floor = $(this).find("option:selected").attr("data-floor");
        var appendhtml = '';
        if(parseInt(floor)>0){
            for(var i = 1;i <= floor;i++){
                appendhtml +='<option class="append-item" value="'+i+'">'+i+'</option>';
            }
            
            $floor.append(appendhtml);
        }
    }
});
$(document).on("click","#roomModal .selected-item",function(){
    var data_id = $(this).attr("data-id");
    var data_code = $(this).attr("data-code");
    $("#clearParent").removeClass("hide");
    $("#mainForm input[name=parent_code]").val(data_code);
    $("#mainForm input[name=parent_id]").val(data_id);
    $("#roomModal").modal("hide");

});
$("#roomModal").on("show.bs.modal",function(){
    loadData(1,10);
});
function formSearch(){
    loadData(1,10);
}
function gotopage(element){
    loadData($(element).attr("data-page"),10);
}
function loadData(page,perpage){
    var context = '{{Config::get("app.context")}}';
    var params = $('#formPopFilter').serialize()+"&page="+page+"&perpage="+perpage;
    //console.log(params);
    var $loading = $("#roomModal .ajax-paging-block .loading-box");
    $loading.removeClass("hide");
    var $box = $("#roomModal .ajax-paging-block");
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
    $.ajax({
        url: context+"ajax/loadroompopup",
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
