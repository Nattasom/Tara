<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>ส่งเงินประจำวัน</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">
    <link rel="stylesheet" href="{{Config::get('app.context')}}assets/css/a4print.css?v={{time()}}">
  </head>
  <body>
    <div class="book">
      <div class="page">
          <div class="subpage">
              <h3 class="text-center">สรุปส่งเงินประจำวันที่ {{$date}} - ตึก: {{$building}}</h3>
              <table class="tbcontent gap-bottom" border="1" cellspacing="0" cellpadding="5" style="width:100%;">
                  <tr>
                    <th>ประเภทพนักงาน</th>
                    <th>จำนวนรอบ</th>
                    <th>ยอดเงิน</th>
                    <th>ส่วนลด</th>
                    <th>คงเหลือหลักหักส่วนลด</th>
                  </tr>
                  @php($sumRound = 0)
                  @php($sumPaid = 0)
                  @php($sumDis = 0)
                  @php($sumNet = 0)
                  @foreach($prlist as $key => $value)
                    <tr>
                        <td>{{$value->Angel_Type_Code}}</td>
                        <td class="text-center">{{$util->numberFormat($value->Round,1)}}</td>
                        <td class="text-right">{{$util->numberFormat($value->Paid_Amt,2)}}</td>
                        <td class="text-right">{{$util->numberFormat($value->Discount_Amt,2)}}</td>
                        <td class="text-right">{{$util->numberFormat($value->Net_Amt,2)}}</td>
                    </tr>
                    <?php
                        $sumRound += $value->Round;
                        $sumPaid += $value->Paid_Amt;
                        $sumDis += $value->Discount_Amt;
                        $sumNet += $value->Net_Amt;
                    ?>
                  @endforeach
                  

                    <tr>
                      <th class="text-right">รวม</th>
                      <th class="text-center">{{$util->numberFormat($sumRound,1)}}</th>
                      <th class="text-right">{{$util->numberFormat($sumPaid,2)}}</th>
                      <th class="text-right">{{$util->numberFormat($sumDis,2)}}</th>
                      <th class="text-right">{{$util->numberFormat($sumNet,2)}}</th>
                    </tr>
              </table>
                <div class="row">
                    <div class="col-50 pad-15 bordered">
                        <h3 class="text-center">ยอดใช้เงินสด</h3>
                        <table style="width:100%;" cellpadding="5">
                            <tr>
                                <td class="text-right" style="width:50%;">ค่านวด</td>
                                <td class="text-right">{{$util->numberFormat($payment->MasCash,2)}}</td>
                            </tr>
                            <tr>
                                <td class="text-right" style="width:50%;">ค่าอาหาร</td>
                                <td class="text-right">{{$util->numberFormat($payment->FNBCash,2)}}</td>
                            </tr>
                            <tr>
                                <th class="text-right" style="width:50%;">รวม</th>
                                <th class="text-right">{{$util->numberFormat($payment->MasCash+$payment->FNBCash,2)}}</th>
                            </tr>
                        </table>
                        <h3 class="text-center">ยอดใช้บัตรเครดิต</h3>
                        <table style="width:100%;" cellpadding="5">
                            <tr>
                                <td class="text-right" style="width:50%;">ค่านวด</td>
                                <td class="text-right">{{$util->numberFormat($payment->MasCredit,2)}}</td>
                            </tr>
                            <tr>
                                <td class="text-right" style="width:50%;">ค่าอาหาร</td>
                                <td class="text-right">{{$util->numberFormat($payment->FNBCredit,2)}}</td>
                            </tr>
                            <tr>
                                <td class="text-right" style="width:50%;">รวม</td>
                                <td class="text-right">{{$util->numberFormat($payment->MasCredit+$payment->FNBCredit,2)}}</td>
                            </tr>
                        </table>
                        <h3 class="text-center">ยอดชำระด้วย Member</h3>
                        <table style="width:100%;" cellpadding="5">
                            <tr>
                                <td class="text-right" style="width:50%;">ค่านวด</td>
                                <td class="text-right">{{$util->numberFormat($payment->FNBMember,2)}}</td>
                            </tr>
                            <tr>
                                <td class="text-right" style="width:50%;">ค่าอาหาร</td>
                                <td class="text-right">{{$util->numberFormat($payment->MasMember,2)}}</td>
                            </tr>
                            <tr>
                                <td class="text-right" style="width:50%;">รวม</td>
                                <td class="text-right">{{$util->numberFormat($payment->MasMember+$payment->FNBMember,2)}}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-50  bordered">
                        <div class="pad-15">
                            <h3 class="text-center">ยอดชำระห้อง</h3>
                            <table style="width:100%;" class="gap-bottom" cellpadding="5">
                                <tr>
                                    <td class="text-right" style="width:50%;">ใช้ห้องสูท</td>
                                    <td class="text-right"></td>
                                </tr>
                                <tr>
                                    <td class="text-right" style="width:50%;">ใช้ห้อง VIP</td>
                                    <td class="text-right"></td>
                                </tr>
                                <tr>
                                    <td class="text-right" style="width:50%;">หักสูท</td>
                                    <td class="text-right">{{$util->numberFormat($payment->SuiteUsed,1)}}</td>
                                </tr>
                                <tr>
                                    <td class="text-right" style="width:50%;">เงินสด สูท</td><!--เงินสด สูท -->
                                    <td class="text-right"></td>
                                </tr>
                                <tr>
                                    <td class="text-right" style="width:50%;">เงินสด VIP</td><!-- เงินสด VIP -->
                                    <td class="text-right"></td>
                                </tr>
                                <tr>
                                    <td class="text-right" style="width:50%;">เครดิต สูท</td> <!-- เครดิต สูท -->
                                    <td class="text-right"></td>
                                </tr>
                                <tr>
                                    <td class="text-right" style="width:50%;">เครดิต VIP</td> <!-- เครดิต VIP -->
                                    <td class="text-right"></td>
                                </tr>
                                <tr>
                                    <td class="text-right" style="width:50%;">member สูท</td> <!-- member สูท -->
                                    <td class="text-right"></td>
                                </tr>
                                <tr>
                                    <td class="text-right" style="width:50%;">member VIP</td>  <!-- member VIP -->
                                    <td class="text-right"></td>
                                </tr>
                            </table>
                        </div>
                        <div style="border-top:1px solid #000; padding: 33px 15px;">
                            <table style="width:100%;"  cellpadding="5">
                                <tr>
                                    <th class="text-right" style="width:50%;">รายการค้างชำระ</th>
                                    <th class="text-right">{{$util->numberFormat($payment->Unpaid,2)}}</th>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="text-right gap-top-40">
                    แคชเชียร์_____________________________การเงิน______________________________
                </div>
          </div>   <!--subpage--> 
      </div>
  </div>

    <!-- JavaScript files-->
    <script src="{{Config::get('app.context')}}assets/vendor/jquery/jquery.min.js"></script>
    <script>
        window.print();
        window.close();
        
    </script>
  </body>
</html>