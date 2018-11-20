<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Room Bill</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">
    <link rel="stylesheet" href="{{Config::get('app.context')}}assets/css/print.css?v={{time()}}">
  </head>
  <body>
    <div class="print-container">
        <div class="">
            <h1 class="text-center">{{$pr->building_name}}</h1>
            <div class="">
              <p class="text-center">วันที่: {{$util->dateToThaiFormat(date("Y-m-d"))}}</p>
            </div>
            <div class="clear"></div>
        </div>
        <div class="content">
          <p class="text-center" style="font-size:24px;">ห้อง: {{$pr->Room_No}} ({{$pr->building_name}})</p>
            <p>เบอร์</p>
            <div class="text-center big-size-text">
                <strong>{{$pr->Angel_ID}}</strong>
            </div>
            <p class="text-center " style="font-size:24px;">เชียร์แขก: {{$pr->Reception_ID}}:{{$pr->Rc_Nick_Name}}</p>
            <p class="text-center text-md">เวลาเข้า: {{date("H:i:s",strtotime($pr->Check_In_Time))}}</p>
            <p class="text-center text-md">แคชเชียร์: {{$cashier}}</p>
        </div>

        <div class="bottom-page"></div>
    </div>
    <!-- JavaScript files-->
    <script src="{{Config::get('app.context')}}assets/vendor/jquery/jquery.min.js" charset="UTF-8"></script>
    <script>
        window.print();
        window.close();
        
    </script>
  </body>
</html>