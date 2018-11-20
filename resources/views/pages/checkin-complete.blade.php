@extends('layouts.default')
@section('title')
เช็คอินเรียบร้อย
@stop
@section('content')
    <div id="page-menu-key" data-menu="m_checkin" data-sub="" data-url="checkin" ></div>
    <section class="padding-top-sm ">
        <div class="container-fluid">
            <div class="block">
                <h2 class="text-center">เช็คอินเรียบร้อยแล้ว</h2>
                <p class="text-center">
                    ทำการพิมพ์บิลห้องเพื่อให้พนักงานเก็บไว้...
                </p>
                <div class="line gap-bottom"></div><br/><br/><br/>
                <div class="text-center">
                    <a class="btn btn-primary" href="javascript:;" onclick="printAll()">พิมพ์เอกสาร</a>
                    <a class="btn btn-secondary" href="{{Config::get('app.context')}}checkin">ย้อนกลับ</a>
                </div>
            </div>
            @foreach($list as $key=>$value)
            <input type="hidden" class="hd_id" value="{{$value->SysMassage_Angel_List_ID}}">
            @endforeach
        </div>
    </section>
@stop
@section('script')
<script>
var all_length = 0;
var cur_index = 0;
var new_window = null;
var myVar;
    $(document).ready(function(){
        //printAll();
    });
    function printAll(){
        cur_index = 0;
        myVar = setInterval(function(){
            window.open("{{Config::get('app.context')}}checkin/roombill?id="+$(".hd_id").eq(cur_index).val(),'pop_'+$(".hd_id").eq(cur_index).val(),'height=400,width=600');
            console.log("loop interval");
            cur_index++;
            checkTimer(cur_index);
        },1000);
    }
    function checkTimer(index){
        if(index==$(".hd_id").length){
            console.log("clear interval");
            clearInterval(myVar);
        }
    }
    window.onbeforeunload = function(){
        console.log("callback");
    }
</script>
@stop
