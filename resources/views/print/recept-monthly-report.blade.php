<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>รายงานสรุปยอดพนักงานเชียร์แขก (รายเดือน)</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">
    <!-- <link rel="stylesheet" href="{{Config::get('app.context')}}assets/css/a4print.css?v={{time()}}"> -->
    <style>
           body {
              
        width: 100%;
        height: 100%;
        margin: 0;
        padding: 0;
        background-color: #FAFAFA;
        font: 14px "Tahoma";
    }
        .page {
            position: relative;
            min-height: 210mm;
            width: 297mm;
            padding: 10mm;
            margin: 10mm auto;
            /* border: 1px #D3D3D3 solid; */
            border-radius: 5px;
            background: white;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }
        .subpage {
        position: relative;
        padding: 0cm;
        /* border: 5px red solid; */
        height: 180mm;
    }
    table.tbcontent{
        width:100%;
    }
    .table-bordered td,.table-bordered th{
        border:1px solid #000;
    }
    .gap-top-40{
        margin-top:40px;
    }
    .bg-day-0,.bg-day-1,.bg-day-6{
        background-color: #FF9999;
    }
    .bg-day-5{
        background-color: #CCFFFF;
    }
    /* .bg-day-6{
        background-color: #CC99FF;
    } */
     .tbcontent th,
    .tbcontent td {
        font-size: 12px;
    }
    .text-center{
        text-align:center;
    }
       @media print{
            @page {size: A4 landscape; margin:0;}
            .page {
                margin: 0;
                max-height: 210mm;
                border: initial;
                border-radius: initial;
                width: initial;
                min-height: initial;
                box-shadow: initial;
                background: initial;
                page-break-after: always;
            }
            .subpage {
            position: relative;
            padding: 0cm;
            /* border: 5px red solid; */
               height: 180mm;
            }
            .bg-day-0,.bg-day-1,.bg-day-6{
            background-color: #FF9999 !important;
            -webkit-print-color-adjust: exact;
        }
        .bg-day-5{
            background-color: #CCFFFF !important;
            -webkit-print-color-adjust: exact; 
        }
        /* .bg-day-6{
            background-color: #CC99FF !important;
            -webkit-print-color-adjust: exact; 
        } */
       }
    </style>
  </head>
  <body>
   <div class="book">
      <div class="page">
          <div class="subpage">
              <h3 class="text-center">รายงานสรุปยอดพนักงานเชียร์แขก (รายเดือน) ประจำเดือน {{$month}} ปี {{$year}}</h3>
              <table class="tbcontent table-bordered" cellspacing = "0" cellpadding="3">
                <thead>
                    <tr class="header">
                        <th>รหัส</th>
                        @foreach($col_day as $kcol=>$vcol)
                        <th class="bg-day-{{$vcol['weekday']}}">{{$kcol}}</th>
                        @endforeach
                        <th>รวม</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pr_list as $key => $value)
                    @php($sumRowRound = 0)
                    <tr>
                        <td>{{$value->Reception_ID}}:{{$value->Nick_Name}}</td>
                        @foreach($col_day as $kcol=>$vcol)
                            @php($round = "")
                            @if(array_key_exists($vcol['date'],$value->Txn))
                                @php($round = $value->Txn[$vcol['date']])
                                @php($sumRowRound += $value->Txn[$vcol['date']])
                            @endif
                        <td class="bg-day-{{$vcol['weekday']}}">{{$round}}</td>
                        @endforeach
                        <td>{{$util->numberFormat($sumRowRound,1)}}</td>
                    </tr>
                    @endforeach
                </tbody>
              </table>
          </div>
      </div>
  </div>

    <!-- JavaScript files-->
    <script src="{{Config::get('app.context')}}assets/vendor/jquery/jquery.min.js"></script>
    <script>
        // window.print();
        // window.close();
        $(document).ready(function(){
          setTimeout(cutpage(),1000);
          setTimeout(function(){
              window.print();
                window.close();
            },2000);
        });

        function cutpage(){
        $(".subpage").each(function(){
            var $parent = $(this).parents(".page");
            var limitHeight = $(this).outerHeight();
            var $tr = $(this).find("table  tr");
            var sumHeight = 0;
            $tr.each(function(){
                var h = $(this).outerHeight();
                sumHeight += h;
                if(sumHeight > limitHeight){
                    $(this).addClass('over-flow');
                }
            });

            if($(this).find("table  tr.over-flow").length > 0){
                var appendHtml = '';
                var $th = $(this).find("table tr.header");
                var appendHtml = '<tr class="header">'+$th.html()+'</tr>';
                $(this).find("table  tr.over-flow").each(function(){
                    appendHtml += "<tr>"+$(this).html()+"</tr>";
                });
                $(this).find("table  tr.over-flow").remove();
                appendPage(appendHtml,$parent);
            }
        });
    }
    function appendPage(html,$parent){
        var template =' <div class="page ">';
                //template += '[;head;] <hr/>';
                template += '<div class="subpage ">';
                    template += '<h3 class="text-center">รายงานสรุปยอดพนักงานบริการ (รายเดือน) ประจำเดือน {{$month}} ปี {{$year}}</h3>';
                    template += '<table class="tbcontent table-bordered" cellspacing = "0" cellpadding="3">';
                       // template += '<tbody>';
                        template += '   [;append;]';
                       //template += ' </tbody>';
                    template += '</table> ';
                  //template += ' [;footer;]';
               template += ' </div>';
           template += ' </div>';

        $parent.after(template.replace("[;append;]",html));
        //console.log(html);
        cutpageMore($parent.next());
    }
    function cutpageMore($element){
        var $parent = $element;
        var limitHeight = $element.find(".subpage").outerHeight();
        var $tr = $element.find("table  tr");
        var sumHeight = 0;
        $tr.each(function(){
            var h = $(this).outerHeight();
            console.log(h);
            sumHeight += h;
            console.log(limitHeight);
            if(sumHeight > limitHeight){
                $(this).addClass('over-flow');
            }
        });
        if($element.find("table  tr.over-flow").length > 0){
            var appendHtml = '';
            var $th = $element.find("table tr.header");
            var appendHtml = '<tr class="header">'+$th.html()+'</tr>';
            $element.find("table  tr.over-flow").each(function(){
                appendHtml += "<tr>"+$(this).html()+"</tr>";
            });
            $element.find("table  tr.over-flow").remove();
            appendPage(appendHtml,$parent);
        }
        
    }
        
    </script>
  </body>
</html>