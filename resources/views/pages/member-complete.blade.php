@extends('layouts.default')
@section('title')
ชำระเงินสมาชิกเรียบร้อย
@stop
@section('content')
    <div id="page-menu-key" data-menu="m_member" data-sub="member_ddl" data-url="memberadd"></div>
    <section class="padding-top-sm ">
        <div class="container-fluid">
            <div class="block">
                <h2 class="text-center">ชำระเงินสมาชิกเรียบร้อยแล้ว</h2>
                <p class="text-center">
                    
                </p>
                <div class="line gap-bottom"></div><br/><br/><br/>
                <div class="text-center">
                    <a class="btn btn-primary" href="{{Config::get('app.context')}}member/bill?id={{$app_id}}" target="_blank">พิมพ์ใบเสร็จ</a>
                    <a class="btn btn-secondary" href="{{Config::get('app.context')}}member">ย้อนกลับ</a>
                </div>
            </div>
        </div>
    </section>
@stop
@section("script")
<script>
    $(document).ready(function() {
        window.history.pushState(null, "", window.location.href);        
        window.onpopstate = function() {
            window.history.pushState(null, "", window.location.href);
        };
    });
</script>
@stop
