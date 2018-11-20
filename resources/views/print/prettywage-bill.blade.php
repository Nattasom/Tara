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
            <h1 class="text-center">{{Config::get('app.name')}}</h1>
            <div class="col-50">
              <p class="text-left">ค่าตอบแทนพนักงานวันที่: </p>
            </div>
            <div class="col-50">
              <p class="text-right">{{$txndate}}</p>
            </div>
            <div class="clear"></div>
            <div class="col-50">
              <p class="text-left">เวลาทำรายการ: </p>
            </div>
            <div class="col-50">
              <p class="text-right">{{$util->dateToThaiFormat(date("Y-m-d"))}} {{date("H:i")}}</p>
            </div>
            <div class="clear"></div>
            <div class="text-center big-size-text">
                <strong>{{$pretty->Angel_ID}}</strong>
            </div>
            <p class="text-center">({{$pretty->Nick_Name}})</p>
            <div class="clear"></div>
        </div>
        <div class="content">
          <table class="bordered" cellspacing="0" style="width:100%;">
            <tr>
              <th>ลำดับ</th>
              <th>เวลา</th>
              <th>ห้อง</th>
              <th>รอบ</th>
              <th>เพิ่ม</th>
              <th>รับ</th>
            </tr>
            <?php
                $sumWage = 0;
                $sumComm = 0;
                $sumOT=0;
                $sumTotal = 0;
                $sumRound = 0;
            ?>
            @foreach($hist->Detail_List as $key=>$value)
              <tr>
                <td>{{++$key}}</td>
                <td>{{date("H:i",strtotime($value->Check_In_Time))}}</td>
                <td>{{$value->Room_No}}</td>
                <td>{{$value->Round}}</td>
                <td>{{$util->numberFormat($value->OT_Fee)}}</td>
                <td>{{$util->numberFormat($value->Total_Amt)}}</td>
              </tr>
              <?php
                  $sumRound += $value->Round;
                  $sumTotal += ($value->Total_Amt);
              ?>
            @endforeach
              <tr class="no-border">
                  <td colspan="3" class="text-right">รวม</td>
                  <td class="text-right">{{$util->numberFormat($sumRound,1)}}</td>
                  <td class="text-right single-line" colspan="3">{{$util->numberFormat($sumTotal)}}</td>
              </tr>
          </table>
          <div class="clear gap-bottom"></div>
          <div class="row">
              <div class="col-50">
                <p class="text-right">รายได้อื่น ๆ</p>
              </div>
              <div class="col-50">
                <p class="text-right "><strong class="">{{$util->numberFormat($hist->Income_Amt)}}</strong></p>
              </div>
            </div>
            <div class="row">
              <div class="col-50">
                <p class="text-right">ค่าการ์ด/แม่บ้าน</p>
              </div>
              <div class="col-50">
                <p class="text-right "><strong class="">- {{$util->numberFormat($hist->Card_Amt)}}</strong></p>
              </div>
            </div>
            <div class="row">
              <div class="col-50">
                <p class="text-right">ค่าเสริมสวย</p>
              </div>
              <div class="col-50">
                <p class="text-right "><strong class="">- {{$util->numberFormat($hist->Makeup_Amt)}}</strong></p>
              </div>
            </div>
            <div class="row">
              <div class="col-50">
                <p class="text-right">หักค่าใช้จ่ายอื่น ๆ</p>
                <div class="text-right print-hide">
                  <textarea rows="3" id="input-debt-remark" onblur="calc()"></textarea>
                </div>
                <p class="text-xs no-print-hide text-right" id="lbl-debt-remark"></p>
              </div>
              <div class="col-50">
                <p class="text-right "><strong class="single-line">- {{$util->numberFormat($hist->Charge_Amt)}}</strong></p>
              </div>
            </div>
             <div class="row">
              <div class="col-50">
                <p class="text-right">คงเหลือ</p>
              </div>
              <div class="col-50">
                <p class="text-right "><strong class="">{{$util->numberFormat($hist->Total_Amt)}}</strong></p>
              </div>
            </div>
            <div class="row">
              <div class="col-50">
                <p class="text-right">รวมรับ</p>
              </div>
              <div class="col-50">
                <p class="text-right "><strong class="double-line">{{$util->numberFormat($hist->Total_Amt)}}</strong></p>
              </div>
            </div>
            <p class="text-center gap-top">
                *** กรุณาตรวจสอบรายการเพื่อความถูกต้อง ***
            </p>
        </div>
        <div class="footer">
           <div class="text-center">
              <a href="" onclick="window.print()" class="print-hide">พิมพ์</a>&nbsp;
              <a href="javascript:window.close();" class="print-hide">ปิด</a>
           </div>
        </div>
        <div class="bottom-page"></div>
    </div>
    <!-- JavaScript files-->
    <script src="{{Config::get('app.context')}}assets/vendor/jquery/jquery.min.js"></script>
    <script>
        $(document).ready(function(){
          $(".pay-by").last().find(".num-amt").addClass("single-line");
          //  window.print();
          // window.close();
        });
        function calc(){
          var input = $("#input-debt-remark").val();
          var $lbl = $("#lbl-debt-remark");
          $lbl.html(input.replace(/\n/g, "<br />")
);
        }
       
    </script>
  </body>
</html>