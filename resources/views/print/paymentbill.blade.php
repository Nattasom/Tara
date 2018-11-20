<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>ใบสรุปค่าใช้จ่าย</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">
    <link rel="stylesheet" href="{{Config::get('app.context')}}assets/css/print.css?v={{time()}}">
  </head>
  <body>
    <div class="print-container">
        <div class="header">
            <h1 class="text-center">ใบสรุปค่าใช้จ่าย</h1>
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
            <div class="col-50 text-right">
            <input type="number" class="print-hide" id="input-food" onblur="calc()"  />
            <p class="text-right no-print-hide"><strong class="double-line" id="lbl-food">{{$util->numberFormat($prFood)}}</strong></p>
            </div>
          </div>
          <p>ค่าบริการนวด</p>
          <table border="1" cellspacing="0" style="width:100%;">
            <tr>
              <th>No.</th>
              <th>พนง/ห้อง</th>
              <th>รอบ/เวลา</th>
              <th>ส่วนลด</th>
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
                <td class="text-right">{{$util->numberFormat(($value->Angel_Fee * $value->Round)-$value->Angel_Discount_Amt)}}</td>
              </tr>
              @php($rowcount++)
              @php($sum_service += ($value->Angel_Fee * $value->Round)-$value->Angel_Discount_Amt)
            @endforeach
          @php($sum_suite = 0)
          
          </table>
          <div class="clear gap-bottom"></div>


            <div class="row">
              <div class="col-50">
                <p class="text-right">รวมค่าบริการนวด</p>
              </div>
              <div class="col-50">
                <p class="text-right "><strong class="">{{$util->numberFormat($sum_service)}}</strong></p>
              </div>
            </div>
            <div class="row">
              <div class="col-50">
                <p class="text-right">รวมค่าห้อง</p>
              </div>
              <div class="col-50 text-right">
                <input type="number" class="print-hide" id="input-room" onblur="calc()"  />
                <p class="text-right no-print-hide"><strong class="single-line" id="lbl-room"></strong></p>
              </div>
            </div>
            <div class="row">
              <div class="col-50">
                <p class="text-right">รวมค่าบริการทั้งหมด</p>
              </div>
              <div class="col-50">
                <p class="text-right "><strong class="double-line" id="lbl-total">{{$util->numberFormat($sum_service+$prFood)}}</strong></p>
                <input type="hidden" id="sum-total" value="{{$sum_service+$prFood}}" />
              </div>
            </div>
            <p class="text-center gap-top">
                *** กรุณาตรวจสอบรายการเพื่อความถูกต้อง ***
            </p>
        </div>
        <div class="footer">
            <div class="text-center">
              <a href="" onclick="window.print()" class="print-hide">พิมพ์</a>&nbsp;
              <a href="{{Config::get('app.context')}}/checkoutlist" class="print-hide">ปิด</a>
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
          var total = $("#sum-total").val();
          var input_food = $("#input-food").val()=="" ? "0":$("#input-food").val();
          var input_room = $("#input-room").val()=="" ? "0":$("#input-room").val();
          $("#lbl-food").text(addCommas(input_food));
          $("#lbl-room").text(addCommas(input_room));
          $("#lbl-total").text(addCommas(parseInt(total)+parseInt(input_food)+parseInt(input_room)));
        }
       function addCommas(nStr) {
          nStr += '';
          x = nStr.split('.');
          x1 = x[0];
          x2 = x.length > 1 ? '.' + x[1] : '';
          var rgx = /(\d+)(\d{3})/;
          while (rgx.test(x1)) {
              x1 = x1.replace(rgx, '$1' + ',' + '$2');
          }
          return x1 + x2;
      }
    </script>
  </body>
</html>