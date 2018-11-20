@extends('layouts.default')
@section('title')
สถานะพนักงานบริการ
@stop
@section('content')
    <div id="page-menu-key" data-menu="m_employee" data-sub="employee_ddl" data-url="prettytime" ></div>
    <!-- Page Header-->
    <div class="page-header no-margin-bottom">
        <div class="container-fluid">
        <h2 class="h5 no-margin-bottom">สถานะพนักงานบริการ</h2>
        </div>
    </div>
    <!-- Breadcrumb-->
    <div class="container-fluid">
        <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">พนักงาน</a></li>
        <li class="breadcrumb-item active">สถานะพนักงานบริการ</li>
        </ul>
    </div>
    <section class="no-padding-top">
        <div class="container-fluid">
            <div class="block">
                <form id="mainForm" method="post" action="" class="form-horizontal">
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
                                    <label class="form-control-label">ประเภทพนักงาน</label>
                                    <select class="form-control" name="pretty_type_select" id="">
                                        <option value="">ทั้งหมด</option>
                                        @foreach($pretty_type_select as $key=>$value)
                                            <option value="{{$value->SysAngelType}}" {{($selected_type==$value->SysAngelType) ? "selected":""}}>{{$value->Angel_Type_Code}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        {{ csrf_field() }}
                        <div class="col-md-4">
                            <div class="block-body">
                                <div class="form-group">
                                    <label class="form-control-label">&nbsp; </label>
                                    <div>
                                        <input type="submit" value="ค้นหา"  id="btn-search" class="btn btn-info">
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
                    @foreach($pretty_type as $key=>$value)
                        <div class="title"><strong>{{$value->Angel_Type_Code}}</strong></div>
                            <div  class="block paging-block">
                                <div  class="ajax-paging-block">
                                    <div class="table-responsive"> 
                                        <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                            <th>พนง.</th>
                                            <th>สถานะ</th>
                                            <th>ขึ้น</th>
                                            <th>ต่อ</th>
                                            <th>รวม</th>
                                            <th>1</th>
                                            <th>2</th>
                                            <th>3</th>
                                            <th>4</th>
                                            <th>5</th>
                                            <th>6</th>
                                            <th>7</th>
                                            <th>8</th>
                                            <th>9</th>
                                            <th>10</th>
                                            <th>เข้า</th>
                                            <th>ออก</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(array_key_exists($value->SysAngelType,$pretty_list))
                                            @foreach($pretty_list[$value->SysAngelType] as $k=>$v)
                                                <tr>
                                                    <td>{{$v->Angel_ID}}</td>
                                                    <td>
                                                        @if($v->Angel_Status=="NW")
                                                            <span class="text-success">พร้อมทำงาน</span>
                                                        @elseif($v->Angel_Status == "WK")
                                                            <span class="text-danger">กำลังทำงาน</span>
                                                        @else
                                                            <span class="">ไม่มาทำงาน</span>
                                                        @endif
                                                    </td>
                                                    <td>{{$v->StartRound}}</td>
                                                    <td>{{$v->AddRound}}</td>
                                                    <td>{{($v->StartRound+$v->AddRound)}}</td>
                                                    <td class="size-xs" style="width:50px;padding-left:0;padding-right:0;">{!! $v->lap[0] !!}</td>
                                                    <td class="size-xs" style="width:50px;padding-left:0;padding-right:0;">{!! $v->lap[1] !!}</td>
                                                    <td class="size-xs" style="width:50px;padding-left:0;padding-right:0;">{!! $v->lap[2] !!}</td>
                                                    <td class="size-xs" style="width:50px;padding-left:0;padding-right:0;">{!! $v->lap[3] !!}</td>
                                                    <td class="size-xs" style="width:50px;padding-left:0;padding-right:0;">{!! $v->lap[4] !!}</td>
                                                    <td class="size-xs" style="width:50px;padding-left:0;padding-right:0;">{!! $v->lap[5] !!}</td>
                                                    <td class="size-xs" style="width:50px;padding-left:0;padding-right:0;">{!! $v->lap[6] !!}</td>
                                                    <td class="size-xs" style="width:50px;padding-left:0;padding-right:0;">{!! $v->lap[7] !!}</td>
                                                    <td class="size-xs" style="width:50px;padding-left:0;padding-right:0;">{!! $v->lap[8] !!}</td>
                                                    <td class="size-xs" style="width:50px;padding-left:0;padding-right:0;">{!! $v->lap[9] !!}</td>
                                                    <td>{{empty($v->Time_ComeIn) ? "":date("H:i",strtotime($v->Time_ComeIn))}}</td>
                                                    <td>{{empty($v->Time_ComeOut) ? "":date("H:i",strtotime($v->Time_ComeOut))}}</td>
                                                </tr>
                                            @endforeach
                                            @endif
                                        </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                    @endforeach
                    
                </div>
            </div>
            
        </div>
    </section>
    <form id="formPrettyDel" method="post" action="{{Config::get('app.context')}}prettydel">
        <input name="del_id" type="hidden" value=""/>
        {{ csrf_field() }}
    </form>
    <form id="formPrettyTypeDel" method="post" action="{{Config::get('app.context')}}prettytypedel">
        <input name="del_id" type="hidden" value=""/>
        {{ csrf_field() }}
    </form>
@stop

@section('script')
<script>
</script>
@stop
