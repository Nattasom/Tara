<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Receipt</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">
    <link rel="stylesheet" href="{{Config::get('app.context')}}assets/css/print.css?v={{time()}}">
  </head>
  <body>
    <div class="print-container">
        <div class="header">
            <h1 class="text-center">{{$prettylist[0]->building_name}}</h1>
            <div class="col-50">
              <p class="text-left">วันที่ทำรายการ: </p>
            </div>
            <div class="col-50">
              <p class="text-right"> {{$util->dateToThaiFormat(date("Y-m-d"))}} {{date("H:i")}}</p>
            </div>
            <div class="clear"></div>
            <div class="col-50">
            <p class="text-left">เลขที่:</p>
            </div>
            <div class="col-50">
                 <p class="text-right">{{$docNo}}</p>
            </div>
            <div class="clear"></div>
            <div class="col-50">
            <p class="text-left">แคชเชียร์:</p>
            </div>
            <div class="col-50">
                 <p class="text-right">{{$cashier}}</p>
            </div>
            <div class="clear"></div>
        </div>
        <div class="content">
          <div class="row">
            <div class="col-50">
              <p class="text-left">ค่าอาหารและเครื่องดื่ม</p>
            </div>
            <div class="col-50">
            <p class="text-right"><strong class="double-line">{{$util->numberFormat($prFood)}}</strong></p>
            </div>
          </div>
          <p>ค่าบริการนวด</p>
          <table border="1" cellspacing="0" style="width:100%;">
            <tr>
              <th>No.</th>
              <th>พนง/ห้อง</th>
              <th>รอบ/เวลา</th>
              <th>ส่วนลด</th>
              <th>เพิ่ม</th>
              <th>รวม</th>
            </tr>
            @php($rowcount= 1)
            @php($sum_service = 0)
            @foreach($prettylist as $key=>$value)
              <tr>
                <td>{{$rowcount}}</td>
                <td>{{$value->Angel_ID}}/{{$value->Room_No}}</td>
                <td class="text-center">{{$value->Round}}<br/>{{date("H:i",strtotime($value->Check_In_Time))}}-{{date("H:i",strtotime($value->Check_Out_Time))}}</td>
                <td class="text-right">{{$util->numberFormat($value->Angel_Discount_Amt)}}</td>
                <td class="text-right">{{$util->numberFormat($value->OT_Fee)}}</td>
                <td class="text-right">{{$util->numberFormat(($value->Angel_Fee * $value->Round)-$value->Angel_Discount_Amt + $value->OT_Fee)}}</td>
              </tr>
              @php($rowcount++)
              @php($sum_service += ($value->Angel_Fee * $value->Round)-$value->Angel_Discount_Amt + $value->OT_Fee)
            @endforeach
          @php($sum_suite = 0)
            @foreach($roomlist as $key=>$value)
              <tr>
                <td>{{$rowcount}}</td>
                <td>{{$value->Room_No}}</td>
                <td class="text-center">{{date("H:i",strtotime($value->Check_In_Time))}}-{{date("H:i",strtotime($value->Check_Out_Time))}}</td>
                <td class="text-right">
                  @if($value->Suite_Used > 0)
                    - ({{$util->numberFormat($value->Suite_Used,1)}}S)
                  @else
                    {{$util->numberFormat($value->Discount)}}
                  @endif
                </td>
                <td class="text-right">-</td>
                <td class="text-right">{{$util->numberFormat($value->Fee - $value->Discount - $value->Suite_Amt)}}</td>
              </tr>
              @php($rowcount++)
              @php($sum_suite += $value->Suite_Used)
              @php($sum_service += $value->Fee - $value->Discount - $value->Suite_Amt)
            @endforeach
          </table>
          <div class="clear gap-bottom"></div>
            <!-- <div class="row">
              <div class="col-50">
                <p class="pad-left-15"><u>รายการ</u></p>
              </div>
              <div class="col-20">
                <p class="text-right"><u>จำนวน</u></p>
              </div>
              <div class="col-30">
                <p class="text-right"><u>ราคา</u></p>
              </div>
            </div> -->

            <!-- <div class="row">
              <div class="col-50">
                <p class="pad-left-15">1. อาหารและเครื่องดื่ม</p>
              </div>
              <div class="col-20">
                <p class="text-right">-</p>
              </div>
              <div class="col-30">
                <p class="text-right">xx,xxx</p>
              </div>
            </div>
            <div class="row">
              <div class="col-50">
                <p class="pad-left-15">2. XL:แพท</p>
              </div>
              <div class="col-20">
                <p class="text-right">1.0</p>
              </div>
              <div class="col-30">
                <p class="text-right">xx,xxx</p>
              </div>
            </div>
             <div class="row">
              <div class="col-50">
                <p class="pad-left-15">3. XL:นุ่น</p>
              </div>
              <div class="col-20">
                <p class="text-right">1.0</p>
              </div>
              <div class="col-30">
                <p class="text-right">xx,xxx</p>
              </div>
            </div> -->


            <div class="row">
              <div class="col-50">
                <p class="text-right">รวมค่าบริการนวด</p>
              </div>
              <div class="col-50">
                <p class="text-right "><strong class="single-line">{{$util->numberFormat($sum_service)}}</strong></p>
              </div>
            </div>
            <div class="row">
              <div class="col-50">
                <p class="text-right">รวมค่าบริการทั้งหมด</p>
              </div>
              <div class="col-50">
                <p class="text-right "><strong class="double-line">{{$util->numberFormat($sum_service+$prFood)}}</strong></p>
              </div>
            </div>
            <div class="row">
              <div class="col-50">
                <p class="text-right">ค่าบริการอื่นๆ<br/>
                <span class="text-xs">หมายเหตุ: {{$other_charge_remark=="" ? "-":$other_charge_remark}}</span>
                </p>
              </div>
              <div class="col-50">
                <p class="text-right "><strong class="double-line">{{$util->numberFormat($other_charge)}}</strong></p>
              </div>
            </div>
            <p class="text-center gap-top">
                *** กรุณาตรวจสอบรายการเพื่อความถูกต้อง ***
            </p>
        </div>
        <div class="footer">
            <p class="pad-left-15">ชำระโดย</p>
            @if($pay_cash > 0 )
              <div class="row pay-by">
                <div class="col-50">
                  <p class="text-right">เงินสด</p>
                </div>
                <div class="col-50">
                  <p class="text-right"><span class="num-amt">{{$util->numberFormat($pay_cash)}}</span></p>
                </div>
              </div>
            @endif

            @if($pay_credit > 0 )
              <div class="row pay-by">
                <div class="col-50">
                  <p class="text-right">บัตรเครดิต</p>
                </div>
                <div class="col-50">
                  <p class="text-right"><span class="num-amt">{{$util->numberFormat($pay_credit)}}</span></p>
                </div>
              </div>
            @endif

            @if($pay_member > 0 )
              <div class="row pay-by">
                <div class="col-50">
                  <p class="text-right">สมาชิก</p>
                </div>
                <div class="col-50">
                  <p class="text-right"><span class="num-amt">{{$util->numberFormat($pay_member)}}</span></p>
                </div>
              </div>
            @endif

            @if($pay_unpaid > 0 )
              <div class="row pay-by">
                <div class="col-50">
                  <p class="text-right">ค้างชำระ</p>
                </div>
                <div class="col-50">
                  <p class="text-right"><span class="num-amt">{{$util->numberFormat($pay_unpaid)}}</span></p>
                </div>
              </div>
            @endif


            <div class="row">
              <div class="col-50">
                <p class="text-right">รวม</p>
              </div>
              <div class="col-50">
                <p class="text-right"><strong class="double-line">{{$util->numberFormat($sum_service+$prFood+$other_charge)}}</strong></p>
              </div>
            </div>
            <div class="row">
              <div class="col-50">
                <p class="text-right">สูทสมาชิก</p>
              </div>
              <div class="col-50">
                <p class="text-right"><strong class="double-line">{{$util->numberFormat($sum_suite,1)}}</strong></p>
              </div>
            </div>

            @if(!is_null($member_data))
            <p class="gap-top">สมาชิก</p>
              <table border="1" cellspacing="0" style="width:100%;">
                <tr>
                  <th>รหัสสมาชิก</th>
                  <th>วงเงิน</th>
                  <th>สูท</th>
                  <th>สูท7/1</th>
                  <th>เหล้า</th>
                </tr>
                <tr>
                  <td>{{$member_data->Member_ID}}</td>
                  <td>{{$util->numberFormat($member_data->Credit_Amt)}}</td>
                  <td>{{$util->numberFormat($member_data->Suite)}}</td>
                  <td>{{$util->numberFormat($member_data->Suite2)}}</td>
                  <td>{{$util->numberFormat($member_data->Whisky)}}/{{$util->numberFormat($member_data->Whisky2)}}</td>
                </tr>
              </table>
            @endif
            <!-- <div class="row">
              <div class="col-20">
                <p>เชียร์แขก:</p>
              </div>
              <div class="col-80">
                <p>xxxxx xxxxxx</p>
              </div>
            </div>
            <div class="row">
              <div class="col-20">
                <p>แคชเชียร์:</p>
              </div>
              <div class="col-80">
                <p>xxxxx xxxxxx</p>
              </div>
            </div> -->
        </div>
        <div class="bottom-page"></div>
    </div>
    <!-- JavaScript files-->
    <script src="{{Config::get('app.context')}}assets/vendor/jquery/jquery.min.js"></script>
    <script>
        $(document).ready(function(){
          $(".pay-by").last().find(".num-amt").addClass("single-line");
           window.print();
          window.close();
        });
       
    </script>
  </body>
</html>