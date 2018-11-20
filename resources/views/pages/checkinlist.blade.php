@extends('layouts.default')
@section('title')
ค้นหาข้อมูลเช็คอิน
@stop
@section('content')
    <div id="page-menu-key" data-menu="m_checkin" data-sub="checkin_ddl" data-url="checkinlist" ></div>
    <!-- Page Header-->
    <div class="page-header no-margin-bottom">
        <div class="container-fluid">
        <h2 class="h5 no-margin-bottom">ค้นหาข้อมูลเช็คอิน</h2>
        </div>
    </div>
    <!-- Breadcrumb-->
    <div class="container-fluid">
        <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">เช็คอิน</a></li>
        <li class="breadcrumb-item active">ค้นหาข้อมูลเช็คอิน</li>
        </ul>
    </div>
    <section class="no-padding-top">
        <div class="container-fluid">
           <div class="row">
                <div class="col-lg-12">
                    <div class="block paging-block">
                        <div class="ajax-paging-block">
                            <div class="loading-box hide"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i><br/><br/>Loading...</div>
                            <div class="title"><strong>รายการที่อยู่ระหว่างการใช้งาน</strong></div>
                            <div class="table-responsive"> 
                                <table class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                    <th>หมายเลขห้อง</th>
                                    <th>พนักงาน</th>
                                    <th>ประเภทห้อง</th>
                                    <th>เชียร์แขก</th>
                                    <th>เวลาขึ้น</th>
                                    <th>เวลาลง</th>
                                    <th>เวลาที่เหลือ</th>
                                    <th>โทรเตือน</th>
                                    <th>เวลาโทรล่าสุด</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>xxx</td>
                                        <td>xxx</td>
                                        <td>xxx</td>
                                        <td>xxx</td>
                                        <td>xxx</td>
                                        <td>xxx</td>
                                        <td>xxx</td>
                                        <td>xxx</td>
                                        <td>xxx</td>
                                    </tr>
                                    <tr>
                                        <td>xxx</td>
                                        <td>xxx</td>
                                        <td>xxx</td>
                                        <td>xxx</td>
                                        <td>xxx</td>
                                        <td>xxx</td>
                                        <td>xxx</td>
                                        <td>xxx</td>
                                        <td>xxx</td>
                                    </tr>
                                </tbody>
                                </table>
                            </div>
                            <div class="line gap-bottom"></div>
                            <div class="table-responsive"> 
                                <table class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                    <th>สูท</th>
                                    <th>เชียร์แขก</th>
                                    <th>เวลาขึ้น</th>
                                    <th>เวลาลง</th>
                                    <th>เวลาที่ใช้</th>
                                    <th>เวลาที่เหลือ</th>
                                    <th>โทรเตือน</th>
                                    <th>เวลาโทรล่าสุด</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>xxx</td>
                                        <td>xxx</td>
                                        <td>xxx</td>
                                        <td>xxx</td>
                                        <td>xxx</td>
                                        <td>xxx</td>
                                        <td>xxx</td>
                                        <td>xxx</td>
                                    </tr>
                                    <tr>
                                        <td>xxx</td>
                                        <td>xxx</td>
                                        <td>xxx</td>
                                        <td>xxx</td>
                                        <td>xxx</td>
                                        <td>xxx</td>
                                        <td>xxx</td>
                                        <td>xxx</td>
                                    </tr>
                                </tbody>
                                </table>
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
    $("#employee .paging-block").removeClass("hide");
    //loadData(1,$(".paging-perpage").val());
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
        url: "ajax/loaduserrole",
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
