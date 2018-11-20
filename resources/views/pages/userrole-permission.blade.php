@extends('layouts.default')
@section('title')
จัดการสิทธิ์ผู้ใช้งาน
@stop
@section('content')
    <div id="page-menu-key" data-menu="m_usersetting" data-sub="setting_ddl" data-url="userrole" ></div>
    <!-- Page Header-->
    <div class="page-header no-margin-bottom">
        <div class="container-fluid">
        <h2 class="h5 no-margin-bottom">จัดการสิทธิ์ผู้ใช้งาน</h2>
        </div>
    </div>
    <!-- Breadcrumb-->
    <div class="container-fluid">
        <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">จัดการข้อมูลระบบ</a></li>
        <li class="breadcrumb-item"><a href="{{Config::get('app.context')}}userrole">จัดการข้อมูลกลุ่มผู้ใช้งาน</a></li>
        <li class="breadcrumb-item active">จัดการสิทธิ์ผู้ใช้งาน</li>
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
                    <form id="mainForm" class="form-horizontal" method="post" action="" novalidate>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <label class="col-sm-3 form-control-label">ชื่อกลุ่มผู้ใช้ <sup class="text-danger">*</sup></label>
                            <div class="col-sm-6">
                            <input type="text" class="form-control" name="rolename" value="{{$role->Role_Name}}" readonly>
                            </div>
                        </div>
                        <div class="line"></div>
                        @foreach($page_group as $key => $value)
                            @if($key % 4 == 0)
                                <div class="row">
                            @endif
                            
                                <div class="col-md-3">
                                    <h3><u>{{$value->page_group_name}}</u></h3>
                                    @if(array_key_exists($value->page_group_id,$page))
                                        @foreach($page[$value->page_group_id] as $kp => $vp)
                                            <h5>{{$vp->page_name}}</h5>
                                            @foreach($vp->action as $ka=>$va)
                                                @php($checked = "")
                                                @if($userObj->checkPermission($role_id,$va->page_id,$va->action_code))
                                                    @php($checked = "checked")
                                                @endif
                                                <div class="i-checks">
                                                    <input id="chk_act_{{$va->page_id}}_{{$ka}}" data-code="{{$va->action_code}}" data-page="{{$va->page_id}}" type="checkbox" value="" class="checkbox-template chk-action" {{$checked}}>
                                                    <label for="chk_act_{{$va->page_id}}_{{$ka}}">{{$va->action_name}}</label>
                                                </div>
                                            @endforeach
                                            
                                        @endforeach
                                        
                                    @endif
                                    
                                </div>
                            
                            @if($key % 4 == 3 || $key == count($page_group)-1)
                                </div>
                            @endif
                            
                        @endforeach
                        
                        <input type="hidden" name="str_save" id="hd_str_save" />
                        <div class="line"></div>
                        <div class="form-group row">
                            <div class="col-sm-8 ml-auto">
                                <button type="submit" class="btn btn-primary">บันทึก</button>
                                <a href="{{Config::get('app.context')}}userrole" class="btn btn-secondary">กลับ</a>
                            </div>
                        </div>
                        {{csrf_field()}}
                    </form>
                </div>
            </div>
            
        </div>
    </section>
@stop

@section('script')
<script>
$("#mainForm").submit(function(){
    var str_save = "";
    var $chk = $(".chk-action:checked");
    if($chk.length == 0){
         $.alert({
            theme:'dark',
            type:'red',
            title:'ผิดพลาด',
            content:'กรุณาเลือกอย่างน้อยหนึ่งรายการ'
        });
        return false;
    }
    var i=0;
    $chk.each(function(){
        var id = $(this).attr("data-page");
        var code = $(this).attr("data-code");
        if(i!=0){
            str_save += ",";
        }
        str_save += id+":"+code;
        i++;
    });
    $("#hd_str_save").val(str_save);
});
</script>
@stop
