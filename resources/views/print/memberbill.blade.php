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
              <p class="text-left">รายการชำระสมาชิก: </p>
            </div>
            <div class="col-50">
              <p class="text-right"> {{$util->dateToThaiFormat(date("Y-m-d"))}} {{date("H:i")}}</p>
            </div>
            <div class="clear"></div>
            <div class="col-30">
            <p class="text-left">หมายเลขสมาชิก:</p>
            </div>
            <div class="col-50">
                 <p class="text-left">{{$member->Member_ID}}</p>
            </div>
            <div class="clear"></div>
            <div class="col-30">
            <p class="text-left">ชื่อสมาชิก:</p>
            </div>
            <div class="col-50">
                 <p class="text-left">{{$member->First_Name}}</p>
            </div>
            <div class="clear"></div>
            <div class="col-30">
            <p class="text-left">พนักงานต้อนรับ:</p>
            </div>
            <div class="col-50">
                 <p class="text-left">{{$member->Recept_Name}}</p>
            </div>
            <div class="clear"></div>
            <div class="col-30">
            <p class="text-left">แคชเชียร์:</p>
            </div>
            <div class="col-50">
                 <p class="text-left">{{$cashier}}</p>
            </div>
            <div class="clear"></div>
            <div class="col-30">
            <p class="text-left">วันหมดอายุ:</p>
            </div>
            <div class="col-50">
                 <p class="text-left">{{$util->dateToThaiFormat($member->Expired_Date)}}</p>
            </div>
            <div class="clear"></div>
        </div>
        <div class="content">
          <div class="row">
            <div class="col-20">
              <p class="text-left"></p>
            </div>
            <div class="col-40">
            <p class="text-left"><strong >เพิ่ม</strong></p>
            </div>
            <div class="col-40">
            <p class="text-left"><strong class="double-line">รวม</strong></p>
            </div>
          </div>
          <div class="clear"></div>
           <div class="row">
            <div class="col-20">
              <p class="text-left">วงเงิน:</p>
            </div>
            <div class="col-40">
            <p class="text-left">{{$util->numberFormat($app->Add_Credit)}}</p>
            </div>
            <div class="col-40">
            <p class="text-left">{{$util->numberFormat($member->Credit_Amt)}}</p>
            </div>
          </div>
          <div class="clear"></div>
          <div class="row">
            <div class="col-20">
              <p class="text-left">สูท</p>
            </div>
            <div class="col-40">
            <p class="text-left">{{$util->numberFormat($app->Add_Suite)}}</p>
            </div>
            <div class="col-40">
            <p class="text-left">{{$util->numberFormat($member->Suite)}}</p>
            </div>
          </div>
          <div class="clear"></div>
          <div class="row">
            <div class="col-20">
              <p class="text-left">สูท 7/1</p>
            </div>
            <div class="col-40">
            <p class="text-left">{{$util->numberFormat($app->Add_Suite2)}}</p>
            </div>
            <div class="col-40">
            <p class="text-left">{{$util->numberFormat($member->Suite2)}}</p>
            </div>
          </div>
          <div class="clear"></div>
          <div class="row">
            <div class="col-20">
              <p class="text-left">Spa</p>
            </div>
            <div class="col-40">
            <p class="text-left">{{$util->numberFormat($app->Add_Suite3)}}</p>
            </div>
            <div class="col-40">
            <p class="text-left">{{$util->numberFormat($member->Suite3)}}</p>
            </div>
          </div>
          <div class="clear"></div>
          <div class="row">
            <div class="col-20">
              <p class="text-left">วิสกี้</p>
            </div>
            <div class="col-40">
            <p class="text-left">Blue: {{$util->numberFormat($app->Add_Whisky)}}<br/>Black: {{$util->numberFormat($app->Add_Whisky2)}}</p>
            </div>
            <div class="col-40">
            <p class="text-left">Blue: {{$util->numberFormat($member->Whisky)}}<br/>Black: {{$util->numberFormat($member->Whisky2)}}</p>
            </div>
          </div>
           
        </div>
        <div class="footer">
            <p class="pad-left-15">ชำระโดย</p>
            @if($pay_cash > 0 )
              <div class="row pay-by">
                <div class="col-50">
                  <p class="text-right">เงินสด</p>
                </div>
                <div class="col-40">
                  <p class="text-right"><span class="num-amt">{{$util->numberFormat($pay_cash)}}</span></p>
                </div>
              </div>
            @endif

            @if($pay_credit > 0 )
              <div class="row pay-by">
                <div class="col-50">
                  <p class="text-right">บัตรเครดิต</p>
                </div>
                <div class="col-40">
                  <p class="text-right"><span class="num-amt">{{$util->numberFormat($pay_credit)}}</span></p>
                </div>
              </div>
            @endif


            @if($pay_unpaid > 0 )
              <div class="row pay-by">
                <div class="col-50">
                  <p class="text-right">ค้างชำระ</p>
                </div>
                <div class="col-40">
                  <p class="text-right"><span class="num-amt">{{$util->numberFormat($pay_unpaid)}}</span></p>
                </div>
              </div>
            @endif


             <p class="text-center gap-top">
                *** กรุณาตรวจสอบรายการเพื่อความถูกต้อง ***
            </p>
        </div>
        <div class="bottom-page"></div>
    </div>
    <!-- JavaScript files-->
    <script src="{{Config::get('app.context')}}assets/vendor/jquery/jquery.min.js"></script>
    <script>
        $(document).ready(function(){
          // $(".pay-by").last().find(".num-amt").addClass("single-line");
           window.print();
          window.close();
        });
       
    </script>
  </body>
</html>