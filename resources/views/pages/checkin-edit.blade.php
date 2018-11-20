@extends('layouts.default')
@section('title')
เช็คอิน
@stop
@section('content')
<div id="page-menu-key" data-menu="m_checkin" data-sub="" data-url="checkin" ></div>
<!-- Page Header-->
<div class="page-header no-margin-bottom">
    <div class="container-fluid">
    <h2 class="h5 no-margin-bottom">แก้ไขเช็คอิน</h2>
    </div>
</div>
<!-- Breadcrumb-->
<div class="container-fluid">
    <ul class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">เช็คอิน</a></li>
    <li class="breadcrumb-item active">แก้ไขเช็คอิน</li>
    </ul>
</div>
<section class="no-padding-top">
    <div class="container-fluid">
         <form id="mainForm" method="post" action="">
        <div class="row">
            <div class="col-md-5">
                <div class="block">
                    <div class="form-horizontal">
                         <div class="form-group row">
                            <label class="col-sm-4 form-control-label">เลือกตึก</label>
                            <div class="col-sm-8">
                                <select class="form-control" id="building">
                                    <option>เลือกตึก</option>
                                    @foreach($buildinglist as $key=>$value)
                                        <option value="{{$value->building_id}}" {{$building==$value->building_id ? "selected":""}}>{{$value->building_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div id="choose-room">
                        <div class="loading-box text-center hide"><i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i><br/><br/>Loading...</div>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <div class="block">
                    <div class="title"><strong>พนักงานต้อนรับ</strong></div>
                    <div class="block-body">
                        <div class="form-horizontal">
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label">รหัสพนักงานต้อนรับ <sup class="text-danger">*</sup></label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                    <input type="text" class="form-control readonly" name="recept_code" value="{{$recept_code}}">
                                    <input type="hidden"  name="recept_id" value="{{$recept_id}}">
                                    <div class="input-group-append">
                                        <button type="button" data-toggle="modal" data-target="#receiptModal" class="btn btn-info"><i class="fa fa-search"></i></button>
                                        <button type="button" class="btn btn-danger hide" id="clearParent"  onclick="clearInputGroupText_(this,'#recept_name')" ><i class="fa fa-trash"></i></button>
                                    </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label">ชื่อ - นามสกุล </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" value="{{$recept_name}}" id="recept_name" name="recept_name">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="recept-block">
                        @foreach($receptlist as $key => $value)
                            <div class="recept-click" data-name="{{$value->First_Name}} {{$value->Last_Name}}" data-id="{{$value->SysReception_ID}}" data-code="{{$value->Reception_ID}}">{{$value->Reception_ID}}: {{$value->Nick_Name}}</div>
                        @endforeach
                    </div>
                    <div class="line gap-bottom"></div>
                    <div class="title"><strong>พนักงานบริการ</strong></div>
                    <div class="table-responsive"> 
                        <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                            <th></th>
                            <th>ห้อง</th>
                            <th>โทรแจ้งเวลา</th>
                            <th>เพิ่มพนักงาน</th>
                            <th>พนักงานบริการ</th>
                            </tr>
                        </thead>
                        <tbody id="room-choose-list">
                            @php($last_room = "-1")
                            @foreach($roomlist as $key=>$value)
                                @php($room_type = "")
                                @if(strpos($value->Room_Type_Code, 'SU') === 0 )
                                    @php($room_type ="suite")
                                @else
                                    @php($room_type ="regular")
                                @endif
                                @if($last_room == $value->SysParent_Room_ID)
                                    <tr class="room-item" data-parent="{{$value->SysParent_Room_ID}}" data-type="suite" data-id="{{$value->SysRoom_ID}}">
                                        <td></td>
                                        <td>{{$value->Room_No}}: {{$value->Room_Type_Desc}} </td>
                                        <td><label><input type="checkbox" class="notcall" /> ห้ามโทรแจ้ง</label></td>
                                        <td><button type="button" class="btn btn-success btn-xs add-pretty" onclick="addpretty(this)"><i class="fa fa-plus"></i></button><input type="text" class="form-control input-select pretty-input" /></td>
                                        <td class="pretty">
                                            @if(array_key_exists($value->SysRoom_ID,$pretty))
                                                @foreach($pretty[$value->SysRoom_ID] as $k=>$v)
                                                    <p data-id="{{$v['id']}}"><button class="btn btn-danger btn-xs del-pretty" onclick="delpretty(this)"><i class="fa fa-minus"></i></button> {{$v['code']}}: {{$v["name"]}}</p>
                                                @endforeach
                                            @endif
                                        </td>
                                    </tr>
                                    
                                @else
                                    <tr class="room-item" data-type="{{$room_type}}" data-id="{{$value->SysRoom_ID}}">
                                        <td><button class="btn btn-danger btn-xs del-room" type="button" onclick="delroom(this)"><i class="fa fa-minus"></i></button></td>
                                        <td>{{$value->Room_No}}: {{$value->Room_Type_Desc}}</td>
                                        <td>
                                            @if($room_type!="suite")
                                            <label><input type="checkbox" class="notcall" {{$value->Warning_Flag=="N" ? "checked":""}} /> ห้ามโทรแจ้ง</label>
                                            @else
                                            @endif
                                            
                                        </td>
                                        <td>
                                            @if($room_type!="suite")
                                            <button type="button" class="btn btn-success btn-xs add-pretty" onclick="addpretty(this)"><i class="fa fa-plus"></i></button><input type="text" class="form-control input-select pretty-input" />
                                            @else
                                            @endif
                                            
                                        </td>
                                        <td class="pretty">
                                            @if(array_key_exists($value->SysRoom_ID,$pretty))
                                                @foreach($pretty[$value->SysRoom_ID] as $k=>$v)
                                                    <p data-id="{{$v['id']}}"><button class="btn btn-danger btn-xs del-pretty" onclick="delpretty(this)"><i class="fa fa-minus"></i></button> {{$v['code']}}: {{$v["name"]}}</p>
                                                @endforeach
                                            @endif
                                        </td>
                                    </tr>
                                    @php($last_room = $value->SysRoom_ID)
                                @endif
                                
                            @endforeach
                                <!-- <tr class="room-item" data-id="">
                                    <td><button class="btn btn-danger btn-xs del-room"><i class="fa fa-minus"></i></button></td>
                                    <td>662: ธรรมดา</td>
                                    <td><label><input type="checkbox" name="notcall" /> ห้ามโทรแจ้ง</label></td>
                                    <td><button class="btn btn-success btn-xs add-pretty"><i class="fa fa-plus"></i></button></td>
                                    <td class="pretty">
                                        <p><button class="btn btn-danger btn-xs del-pretty" onclick="delpretty(this)"><i class="fa fa-minus"></i></button> L103: แอล</p>
                                        <p><button class="btn btn-danger btn-xs del-pretty"><i class="fa fa-minus"></i></button> L104: k</p>
                                    </td>
                                </tr> -->
                        </tbody>
                        </table>
                    </div>
                    <input type="hidden" name="room_checkin" value="" />
                    {{ csrf_field() }}
                    <div class="line gap-bottom"></div>
                    <div class="form-group row">
                        <div class="col-sm-8 ml-auto">
                            <button type="submit" class="btn btn-primary">บันทึก</button>
                            <a href="{{Config::get('app.context')}}checkoutlist" class="btn btn-secondary">ยกเลิก</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </form>
    </div>
</section>
<!-- Modal -->
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

<div class="modal fade" id="prettyModal" tabindex="-1" role="dialog" >
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <h4 class="modal-title" >เลือกพนักงานบริการ</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
          <form id="formPopFilter" class="form-horizontal">
            <div class="row">
                <div class="col-md-4">
                    <div class="block-body">
                        <div class="form-group">
                            <label class="form-control-label">รหัสพนักงาน</label>
                            <input type="text" name="empcode" placeholder="" class="form-control">
                        </div>
                    </div>
                </div>
                 <div class="col-md-4">
                    <div class="block-body">
                        <div class="form-group">
                            <label class="form-control-label">ชื่อเล่น</label>
                            <input type="text" name="nickname" placeholder="" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="block-body">
                        <div class="form-group">
                            <label class="form-control-label">สถานะ</label>
                            <select class="form-control" name="status">
                                <option value="">ทั้งหมด</option>
                                <option value="AB">ไม่มาทำงาน</option>
                                <option value="NW" selected>พร้อมทำงาน</option>
                                <option value="WK">กำลังทำงาน</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="block-body">
                        <div class="form-group">
                            <label class="form-control-label">&nbsp; </label>
                            <div>
                                <input type="button" value="ค้นหา" onclick="formPrettySearch()" id="btn-search" class="btn btn-info">
                            </div>
                            
                        </div>
                    </div>
                    
                </div>
            </div>
        </form>
         <div data-type="pretty" class="ajax-paging-block">
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
    var $tmpPrettyClick;

    $(document).ready(function(){
        if("{{$building}}"!=""){
            loadRoom("{{$building}}");
        }
    });
$(document).on("submit","#mainForm",function(){
    var $tb = $("#room-choose-list").find(".room-item");
    var recept = $("input[name=recept_id]").val();
    if(recept==""){
        $.alert({
            theme:'dark',
            type:'red',
            title:'ผิดพลาด',
            content:'กรุณาเลือกพนักงานเชียร์แขก'
        });
        return false;
    }
    if($tb.length==0){
        $.alert({
            theme:'dark',
            type:'red',
            title:'ผิดพลาด',
            content:'กรุณาเลือกห้อง'
        });
        return false;
    }
    var flagErrPretty = false;
    var countPrettySuite = 0;
    var cSuite = 0;
    var cReg = 0;
    $tb.each(function(){
        var type = $(this).attr("data-type");
        var $pretty = $(this).find(".pretty p");
        if(type=="regular"){
            if($pretty.length==0){
                flagErrPretty = true;
                return false
            }
        }else if(type=="suite"){
            cSuite++;
            if($pretty.length>0){
                countPrettySuite++;
            }
        }
    });
    if(flagErrPretty || (cSuite > 0 && countPrettySuite==0)){
        $.alert({
            theme:'dark',
            type:'red',
            title:'ผิดพลาด',
            content:'กรุณาเลือกพนักงานบริการ'
        });
        return false;
    }
    var strsave = "";
    var i = 0;
    $tb.each(function(){
        var j = 0;
        if(i!=0)
        {strsave+='|';}
        var $warn = $(this).find(".notcall");
        var wFlag = 'Y';
        if($warn.is(":checked")){
            wFlag = 'N';
        }
        strsave += $(this).attr("data-id")+'='+wFlag+';';
        var $pretty = $(this).find(".pretty p");
        

        $pretty.each(function(){
            if(j!=0){
                strsave+=',';
            }
            strsave += $(this).attr('data-id');
            j++;
        });
        i++;
    });
    $("input[name=room_checkin]").val(strsave);
});
$(document).on("click",".recept-click",function(){
    var data_id = $(this).attr("data-id");
    var data_code = $(this).attr("data-code");
    var name = $(this).attr("data-name");
    $(".recept-click").removeClass("clicked");
    $(this).addClass("clicked");
    $("#clearParent").removeClass("hide");
    $("#mainForm input[name='recept_code']").val(data_code);
    $("#mainForm input[name='recept_id']").val(data_id);
    $("#mainForm input[name='recept_name']").val(name);

});
$(document).on("click","#receiptModal .selected-item",function(){
    var data_id = $(this).attr("data-id");
    var data_code = $(this).attr("data-code");
    var name = $(this).find(".text-name").text();
    $("#clearParent").removeClass("hide");
    $("#mainForm input[name='recept_code']").val(data_code);
    $("#mainForm input[name='recept_id']").val(data_id);
    $("#mainForm input[name='recept_name']").val(name);
    
    $("#receiptModal").modal("hide");

});
$(document).on("click","#prettyModal .selected-item",function(){
    var data_id = $(this).attr("data-id");
    var data_code = $(this).attr("data-code");
    var data_status = $(this).attr("data-status");
    var name = $(this).find(".text-name").text();
    if(data_status != "NW"){
        if(!checkBeforePretty(data_id)){
            $.alert({
                theme:'dark',
                type:'red',
                title:'ผิดพลาด',
                content:'กรุณาเลือกพนักงานที่พร้อมทำงาน'
            });
            return false;
        }
    }
    var flagDup = false;
    $("#room-choose-list").find(".pretty p").each(function(){
        if($(this).attr("data-id")==data_id){
            flagDup = true;
            return false;
        }
    });
    if(flagDup){
        $.alert({
                theme:'dark',
                type:'red',
                title:'ผิดพลาด',
                content:'เลือกพนักงานซ้ำ'
            });
        return false;
    }
    $tmpPrettyClick.find(".pretty").append('<p data-id="'+data_id+'"><button class="btn btn-danger btn-xs del-pretty" onclick="delpretty(this)"><i class="fa fa-minus"></i></button> '+data_code+': '+name+'</p>');
    
    $("#prettyModal").modal("hide");

});
$(document).on("change","#building",function(){
    if($(this).val()!=""){
        loadRoom($(this).val());
    }
});
$(document).on("change",".pretty-input",function(e){
    e.preventDefault();
    var $parent = $(this).parents("tr");
    $tmpPrettyClick = $parent;
    getPretty($(this).val());
});

$(document).on("click",'div.room-click:not(.room-ss):not(.used):not(.clicked):not(.closed)',function(){
    var id = $(this).attr("data-id");
    var code = $(this).attr("data-code");
    var name = $(this).attr("data-name");
    var template = '<tr class="room-item" data-type="regular" data-id="[id]">\
        <td><button class="btn btn-danger btn-xs del-room" type="button" onclick="delroom(this)"><i class="fa fa-minus"></i></button></td>\
        <td>[code]: [name]</td>\
        <td><label><input type="checkbox" class="notcall" /> ห้ามโทรแจ้ง</label></td>\
        <td><button type="button" class="btn btn-success btn-xs add-pretty" onclick="addpretty(this)"><i class="fa fa-plus"></i></button><input type="text" class="form-control input-select pretty-input" /></td>\
        <td class="pretty">\
        </td>\
    </tr>';
    $(this).addClass("clicked");
    var append = template.replace("[id]",id).replace("[code]",code).replace("[name]",name);
    $("#room-choose-list").append(append);
    $("#room-choose-list tr:last-child").find(".pretty-input").focus();
});
$(document).on("click",'div.room-click.room-ss:not(.used):not(.clicked):not(.closed)',function(){
    console.log("ss");
    var id = $(this).attr("data-id");
    var code = $(this).attr("data-code");
    var name = $(this).attr("data-name");
    var parent = $(this).attr("data-parent");
    var template = '<tr class="room-item" data-type="regular" data-id="[id]">\
        <td><button class="btn btn-danger btn-xs del-room" type="button" onclick="delroom(this)"><i class="fa fa-minus"></i></button></td>\
        <td>[code]: [name]</td>\
        <td><label><input type="checkbox" class="notcall" /> ห้ามโทรแจ้ง</label></td>\
        <td><button type="button" class="btn btn-success btn-xs add-pretty" onclick="addpretty(this)"><i class="fa fa-plus"></i></button><input type="text" class="form-control input-select pretty-input" /></td>\
        <td class="pretty">\
        </td>\
    </tr>';
    $(this).addClass("clicked");
    $("div.room-click-suite[data-code='"+parent+"']").addClass("clicked");
    var append = template.replace("[id]",id).replace("[code]",code).replace("[name]",name);
    $("#room-choose-list").append(append);
    $("#room-choose-list tr:last-child").find(".pretty-input").focus();
});
$(document).on("click",'div.room-click-suite:not(.used):not(.clicked):not(.closed)',function(){
    var id = $(this).attr("data-id");
    var code = $(this).attr("data-code");
    var name = $(this).attr("data-name");
    var template = '<tr class="room-item" data-type="suite" data-id="[id]">\
        <td><button class="btn btn-danger btn-xs del-room" type="button" onclick="delroom(this)"><i class="fa fa-minus"></i></button></td>\
        <td>[code]: [name] </td>\
        <td><label></td>\
        <td></td>\
        <td class="pretty">\
        </td>\
    </tr>';
    $(this).addClass("clicked");
    $("div.room-click[data-parent='"+code+"']").addClass("clicked");
    var append = template.replace("[id]",id).replace("[code]",code).replace("[name]",name);
    //$("#room-choose-list").append(append);
    loadSubSuite(id,append);
});
$("#receiptModal").on("show.bs.modal",function(){
    loadDataReceipt(1,10);
});
$("#prettyModal").on("show.bs.modal",function(){
    loadDataPretty(1,10);
});
function checkBeforePretty(id){
    var map = "{{$prettymap}}";
    var arr = map.split(',');
    var flagFound = false;
    for(var i = 0 ; i<arr.length;i++){
        if(arr[i]==id){
            flagFound = true;
            break;
        }
    }
    return flagFound;
}
var tmpCard = '';
function getPretty(card_no){
    if(tmpCard!=''){
        return false;
    }
    var params = "card="+card_no;
    tmpCard = card_no;
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
    $.ajax({
        url: "{{Config::get('app.context')}}ajax/getprettybycard",
        type: "POST",
        data:params,
        success: function(data){
            console.log(data);
            if(data.status=="01"){
                if(data.angel_status != "NW"){
                    if(!checkBeforePretty(data.id)){
                         $.confirm({
                            theme:'dark',
                            type:'red',
                            title:'ผิดพลาด',
                            content:'กรุณาเลือกพนักงานที่พร้อมทำงาน',
                            buttons: {
                            OK: function () {
                                    $tmpPrettyClick.find(".pretty-input").val('');
                                    $tmpPrettyClick.find(".pretty-input").focus();
                                }
                            }
                        });
                        return false;
                    }
                }
                var flagDup = false;
                $("#room-choose-list").find(".pretty p").each(function(){
                    if($(this).attr("data-id")==data.id){
                        flagDup = true;
                        return false;
                    }
                });
                if(flagDup){
                    $.confirm({
                            theme:'dark',
                            type:'red',
                            title:'ผิดพลาด',
                            content:'เลือกพนักงานซ้ำ',
                            buttons: {
                                OK: function () {
                                    $tmpPrettyClick.find(".pretty-input").val('');
                                    $tmpPrettyClick.find(".pretty-input").focus();
                                }
                            }
                        });
                    return false;
                }
                $tmpPrettyClick.find(".pretty").append('<p data-id="'+data.id+'"><button class="btn btn-danger btn-xs del-pretty" onclick="delpretty(this)"><i class="fa fa-minus"></i></button> '+data.code+': '+data.name+'</p>');
                $tmpPrettyClick.find(".pretty-input").val('');
                if($tmpPrettyClick.next().length>0){
                    $tmpPrettyClick.next().find(".pretty-input").focus();
                }
            }
            else{
                $.confirm({
                    theme:'dark',
                    type:'red',
                    title:'ผิดพลาด',
                    content: data.message,
                    buttons: {
                        OK: function () {
                            $tmpPrettyClick.find(".pretty-input").val('');
                            $tmpPrettyClick.find(".pretty-input").focus();
                        }
                    }
                });
            }
            tmpCard = '';
        }
    });
}
function loadSubSuite(id,html){
    var params = "id="+id;
    var template = '<tr class="room-item" data-parent="'+id+'" data-type="suite" data-id="[id]">\
        <td></td>\
        <td>[code]: [name] </td>\
        <td><label><input type="checkbox" class="notcall" /> ห้ามโทรแจ้ง</label></td>\
        <td><button type="button" class="btn btn-success btn-xs add-pretty" onclick="addpretty(this)"><i class="fa fa-plus"></i></button><input type="text" class="form-control input-select pretty-input" /></td>\
        <td class="pretty">\
        </td>\
    </tr>';
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
    $.ajax({
        url: "{{Config::get('app.context')}}ajax/loadsubsuitejson",
        type: "POST",
        data:params,
        success: function(data){
            // console.log(data);
            var append = "";
            $.each(data,function(k,v){
                append +=template.replace("[code]",v["Room_No"]).replace("[name]",v["Room_Type_Desc"]).replace("[id]",v["SysRoom_ID"]);
            });
            $("#room-choose-list").append(html);
            var $last = $("#room-choose-list tr:last-child");
            $("#room-choose-list").append(append);
            $last.next().find(".pretty-input").focus();
        }
    });
}
function gotopage(element){
    var $parent = $(element).parents(".ajax-paging-block");
    var type = $parent.attr("data-type");
    if(type=="receipt"){
        loadDataReceipt($(element).attr("data-page"),10);
    }
    else{
        loadDataPretty($(element).attr("data-page"),10);
    }
    
}
function formPrettySearch(){
    loadDataPretty(1,10);
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
function loadDataPretty(page,perpage){
    var params = $('#formPopFilter').serialize()+"&page="+page+"&perpage="+perpage;
    //console.log(params);
    var $loading = $("#prettyModal .ajax-paging-block .loading-box");
    $loading.removeClass("hide");
    var $box = $("#prettyModal .ajax-paging-block");
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
    $.ajax({
        url: "{{Config::get('app.context')}}ajax/loadprettypopup",
        type: "POST",
        data:params,
        success: function(data){
            $box.html(data);
            $loading.addClass("hide");
        }
    });
}

function loadRoom(building){
    var params = "building_id="+building;
    //console.log(params);
    var $loading = $("#choose-room .loading-box");
    $loading.removeClass("hide");
    var $box = $("#choose-room");
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
    $.ajax({
        url: "{{Config::get('app.context')}}ajax/loadcheckinroom",
        type: "POST",
        data:params,
        success: function(data){
            $box.html(data);
            $loading.addClass("hide");
            var roommap = "{{$roommap}}";
            console.log(roommap);
            var arrRoom = roommap.split(',');
            for(var i = 0 ;i < arrRoom.length;i++){
                $(".room-click[data-id='"+arrRoom[i]+"']").removeClass("used");
                $(".room-click[data-id='"+arrRoom[i]+"']").addClass("clicked");

                $(".room-click-suite[data-id='"+arrRoom[i]+"']").removeClass("used");
                $(".room-click-suite[data-id='"+arrRoom[i]+"']").addClass("clicked");
            }
        }
    });
}
function addpretty(element){
    var $parent = $(element).parents(".room-item");
    $tmpPrettyClick = $parent;
    loadDataPretty(1,10)
    
    $("#prettyModal").modal("show");
}
function delroom(element){
    console.log("delroom");
    var id = $(element).parents(".room-item").attr("data-id");
    $(".room-item[data-parent='"+id+"']").remove();
    $(element).parents(".room-item").remove();
    $(".room-click[data-id='"+id+"']").removeClass("clicked");
    $(".room-click-suite[data-id='"+id+"']").removeClass("clicked");
    
    var pCode = $(".room-click-suite[data-id='"+id+"']").attr("data-code");
    $(".room-click[data-parent='"+pCode+"']").removeClass("clicked");

    var parent = $(".room-click[data-id='"+id+"']").attr("data-parent");
    if (typeof parent !== "undefined") {
        var chk = $(".room-click.clicked[data-parent='"+parent+"']").length;
        if(chk==0){
            $(".room-click-suite[data-code='"+parent+"']").removeClass("clicked");
        }
    }
}
function delpretty(element){
    $(element).parent().remove();
}
function toggleSubSuite(element){
    if($("#sub_suite").hasClass("hide")){
        $(element).text("ปิด");
    }else{
        $(element).text("แบ่งห้องสูท");
    }
    $("#sub_suite").toggleClass("hide");
}
</script>
@stop
