<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>การใช้สิทธิ์เมมเบอร์</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">
    <link rel="stylesheet" href="{{Config::get('app.context')}}assets/css/a4print.css?v={{time()}}">
  </head>
  <body>
    <div class="book">
      <div class="page">
          <div class="subpage">
              <h3 class="text-center">สรุปยอดขายเมมเบอร์ประจำวันที่ {{$start}} ถึง {{$end}}</h3>
              <table class="tbcontent" border="1" cellspacing="0" cellpadding="5" style="width:100%;">
                  <tr>
                    <th>สมาชิก</th>
                    <th>ยอดนวด</th>
                    <th>ยอดอาหาร</th>
                    <th>สิทธิ์สูท</th>
                    <th>รวมใช้สิทธิ์</th>
                    <th>ยอดคงเหลือ</th>
                  </tr>
                  @php($sumTotal = 0)
                  @php($sumMassage = 0)
                  @php($sumFood = 0)
                  @php($sumSuite = 0)
                  @foreach($list as $key => $value)
                    <tr>
                      <td>{{$value->Member_ID}}</td>
                      <td class="text-right">{{$util->numberFormat($value->Angel_Amt,2)}}</td>
                      <td class="text-right">{{$util->numberFormat($value->FNB_Amt,2)}}</td>
                      <td class="text-right">{{$util->numberFormat($value->Suite_Used,2)}}</td>
                      <td class="text-right">{{$util->numberFormat($value->Sum_Total,2)}}</td>
                      <td class="text-right">{{$util->numberFormat($value->Credit_Amt,2)}}</td>
                      <?php
                        $sumTotal += $value->Sum_Total;
                        $sumMassage += $value->Angel_Amt;
                        $sumFood += $value->FNB_Amt;
                        $sumSuite += $value->Suite_Used;
                      ?>
                    </tr>
                  @endforeach
                  

                    <tr>
                      <th  class="text-right">รวม</th>
                      <th class="text-right">{{$util->numberFormat($sumMassage,2)}}</th>
                      <th class="text-right">{{$util->numberFormat($sumFood,2)}}</th>
                      <th class="text-right">{{$util->numberFormat($sumSuite,2)}}</th>
                      <th class="text-right">{{$util->numberFormat($sumTotal,2)}}</th>
                      <th></th>
                    </tr>
              </table>
          </div>    
      </div>
      
  </div>

    <!-- JavaScript files-->
    <script src="{{Config::get('app.context')}}assets/vendor/jquery/jquery.min.js"></script>
    <script>
        
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
            var limitHeight = $(this).outerHeight()-100;
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
                    //template += '<h2 class="text-center">การสัมภาษณ์เพื่อการวางแผนการเงิน</h2>';
                    template += '<table class="tbcontent" border="1" cellspacing="0" cellpadding="5" style="width:100%;">';
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
        var limitHeight = $element.find(".subpage").outerHeight() - 200;
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