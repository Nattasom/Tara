<div class="loading-box text-center hide"><i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i><br/><br/>Loading...</div>
<div class="title"><strong>เลือกห้องพัก</strong></div>
<div class="title">
 <div class="pull-right"><button type="button" class="btn btn-info btn-xs " onclick="toggleSubSuite(this)">แบ่งห้องสูท</button><div class="clearfix"></div></div>
<span class="d-block">ห้องธรรมดา</span> 
</div>
<div class="room-block hide" id="sub_suite">
    @foreach($subsuite as $key=>$value)
        <div class="room-click room-ss {{($value->Room_Status=='CL'||$value->ParentStatus=='CL') ? 'closed':''}} {{($value->Room_Status=='IN'||$value->ParentStatus=='IN') ? 'used':''}}" data-parent = "{{$value->ParentRoom}}" data-name="{{$value->Room_Type_Desc}}" data-code = "{{$value->Room_No}}" data-id="{{$value->SysRoom_ID}}">{{$value->Room_No}}</div>
    @endforeach
</div>
<div class="line gap-bottom"></div>
<div class="room-block">
    @foreach($regular as $key=>$value)
        <div class="room-click {{($value->Room_Status=='CL') ? 'closed':''}} {{($value->Room_Status=='IN') ? 'used':''}}" data-name="{{$value->Room_Type_Desc}}" data-code = "{{$value->Room_No}}" data-id="{{$value->SysRoom_ID}}">{{$value->Room_No}}</div>
    @endforeach
</div>
<div class="line gap-bottom"></div>
<div class="title"><span class="d-block">ห้อง VIP</span></div>
<div class="room-block">
    @foreach($vip as $key=>$value)
        <div class="room-click {{($value->Room_Status=='CL') ? 'closed':''}} {{$value->Room_Status=='IN' ? 'used':''}}" data-name="{{$value->Room_Type_Desc}}" data-code = "{{$value->Room_No}}" data-id="{{$value->SysRoom_ID}}">{{$value->Room_No}}</div>
    @endforeach
</div>
<div class="line gap-bottom"></div>
<div class="title"><span class="d-block">ห้องสูท</span></div>
<div class="room-block">
    @foreach($suite as $key=>$value)
    <div class="room-click-suite {{($value->Room_Status=='CL') ? 'closed':''}} {{($value->Room_Status=='IN'||$value->UsedSubCount > 0) ? 'used':''}}" data-name="{{$value->Room_Type_Desc}}" data-code = "{{$value->Room_No}}"  data-id="{{$value->SysRoom_ID}}">{{$value->Room_No}}</div>
    @endforeach
</div>