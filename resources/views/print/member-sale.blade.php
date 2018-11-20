<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>ยอดขายเมมเบอร์</title>
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
                    <th>ลำดับ</th>
                    <th>วันที่สมัคร</th>
                    <th>เมมเบอร์</th>
                    <th>ชื่อ</th>
                    <th>เชียร์แขก</th>
                    <th>ราคา</th>
                    <th>เงินสด</th>
                    <th>บัตรเครดิต</th>
                    <th>ค้างชำระ</th>
                    <th>ผู้ทำรายการ</th>
                  </tr>
                  @php($sumTotal = 0)
                  @php($sumCash = 0)
                  @php($sumCredit = 0)
                  @php($sumUnpaid = 0)
                  @foreach($list as $key => $value)
                    <tr>
                      <td>{{++$key}}</td>
                      <td>{{$util->dateToThaiFormat($value->Member_From_Date)}}</td>
                      <td>{{$value->Member_ID}}</td>
                      <td>{{$value->First_Name}}</td>
                      <td>{{$value->RC_Nickname}}</td>
                      <td class="text-right">{{$util->numberFormat($value->Add_Credit,2)}}</td>
                      <td class="text-right">{{$util->numberFormat($value->Cash_Amt,2)}}</td>
                      <td class="text-right">{{$util->numberFormat($value->Credit_Amt,2)}}</td>
                      <td class="text-right">{{$util->numberFormat($value->Unpaid_Amt,2)}}</td>
                      <td>{{$value->User_Fullname}}</td>
                      <?php
                        $sumTotal += $value->Add_Credit;
                        $sumCash += $value->Cash_Amt;
                        $sumCredit += $value->Credit_Amt;
                        $sumUnpaid += $value->Unpaid_Amt;
                      ?>
                    </tr>
                  @endforeach
                  

                    <tr>
                      <th colspan="5" class="text-right">รวม</td>
                      <th class="text-right">{{$util->numberFormat($sumTotal,2)}}</th>
                      <th class="text-right">{{$util->numberFormat($sumCash,2)}}</th>
                      <th class="text-right">{{$util->numberFormat($sumCredit,2)}}</th>
                      <th class="text-right">{{$util->numberFormat($sumUnpaid,2)}}</th>
                      <th></th>
                    </tr>
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