@extends('layouts.default')
@section('title')
สถานะห้องและพนักงาน
@stop
@section('content')
    <div id="page-menu-key" data-menu="m_status_all" data-sub="" data-url="statusall" ></div>
    <!-- Page Header-->
    <div class="page-header no-margin-bottom">
        <div class="container-fluid">
        <h2 class="h5 no-margin-bottom">สถานะห้องและพนักงาน</h2>
        </div>
    </div>
    <!-- Breadcrumb-->
    <div class="container-fluid">
        <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">สถานะห้องและพนักงาน</a></li>
        <li class="breadcrumb-item active">สถานะห้องและพนักงาน</li>
        </ul>
    </div>
    <section class="no-padding-top">
        <div class="container-fluid">
                <div class="row">
                    <div class="col-md-5">
                        <div class="block">
                            <h2 class="title"><strong>สถานะห้อง</strong></h2>
                            <div class="form-horizontal">
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label">เลือกตึก</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" name="building" id="building">
                                            @foreach($buildinglist as $key=>$value)
                                                <option value="{{$value->building_id}}"  {{($selected_building==$value->building_id) ? 'selected':''}}>{{$value->building_name}}</option>
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
                        <div class="block" id="pretty_block">
                            <h2 class="title"><strong>สถานะพนักงานบริการ (ที่มาทำงาน)</strong><small class="pull-right">มาทำงานทั้งหมด  {{$util->numberFormat($working_all)}} คน</small></h2>
                            <form id="formFilter" class="form-horizontal">
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
                    </div>
                </div>
        </div>
    </section>
@stop
@section('script')
    <script>
        $(document).ready(function(){
            // loadRoom($("#building").val());
            // loadDataPretty(1,10);
            setInterval(loadRoom($("#building").val()),60000);
            setInterval(loadDataPretty(1,10),60000);
        });
        $(document).on("change","#building",function(){
            if($(this).val()!=""){
                loadRoom($(this).val());
            }
        });
        function formPrettySearch(){
            loadDataPretty(1,10);
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
                url: "ajax/loadcheckinroom",
                type: "POST",
                data:params,
                success: function(data){
                    $box.html(data);
                    $loading.addClass("hide");
                }
            });
        }
        function loadDataPretty(page,perpage){
            var params = $('#formFilter').serialize()+"&page="+page+"&perpage="+perpage;
            //console.log(params);
            var $loading = $("#pretty_block .ajax-paging-block .loading-box");
            $loading.removeClass("hide");
            var $box = $("#pretty_block .ajax-paging-block");
            $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            });
            $.ajax({
                url: "ajax/loadprettyreception",
                type: "POST",
                data:params,
                success: function(data){
                    $box.html(data);
                    $loading.addClass("hide");
                }
            });
        }
        function gotopage(element){
            var $parent = $(element).parents(".ajax-paging-block");
            var type = $parent.attr("data-type");
            if(type=="pretty"){
                loadDataPretty($(element).attr("data-page"),10);
            }
            
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
