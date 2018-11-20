<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>TEST Bill</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">
    <link rel="stylesheet" href="{{Config::get('app.context')}}assets/css/print.css?v={{time()}}">
  </head>
  <body>
    <div class="print-container">
        <div class="">
            <h1 class="text-center">{{Config::get('app.name')}}</h1>
            <div class="col-50">
              <p class="text-left">เบอร์: 111</p>
            </div>
            <div class="col-50">
              <p class="text-right">วันที่ทำรายการ: 1111111</p>
            </div>
            <div class="clear"></div>
        </div>
        <div class="content">
            <p>ห้อง</p>
            <div class="text-center big-size-text">
                <strong>1111</strong>
            </div>
            <p class="text-center">เวลาเข้า: 111</p>
            <p class="text-center">เชียร์แขก: 111</p>
            <p class="text-center">แคชเชียร์: 111</p>
        </div>

        <div class="bottom-page"></div>
    </div>

    <div class="print-container">
        <div class="">
            <h1 class="text-center">{{Config::get('app.name')}}</h1>
            <div class="col-50">
              <p class="text-left">เบอร์: 111</p>
            </div>
            <div class="col-50">
              <p class="text-right">วันที่ทำรายการ: 1111111</p>
            </div>
            <div class="clear"></div>
        </div>
        <div class="content">
            <p>ห้อง</p>
            <div class="text-center big-size-text">
                <strong>1111</strong>
            </div>
            <p class="text-center">เวลาเข้า: 111</p>
            <p class="text-center">เชียร์แขก: 111</p>
            <p class="text-center">แคชเชียร์: 111</p>
        </div>

        <div class="bottom-page"></div>
    </div>
    <!-- JavaScript files-->
    <script src="{{Config::get('app.context')}}assets/vendor/jquery/jquery.min.js"></script>
    <script>
        window.print();
        window.close();
    </script>
  </body>
</html>