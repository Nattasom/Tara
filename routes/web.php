<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', 'Login@index')->name('home');
Route::post('/login', 'Login@login')->name('auth.login');
Route::get('/login','Login@index');
Route::any('/logout','Login@logout');

Route::group(['middleware' => 'usersession'], function () {
    Route::any('/home', 'Home@index')->name('page.home');
    //Check-in
    Route::any('/checkin', 'CheckIn@index')->name('page.checkin');
    Route::any('/checkin/edit', 'CheckIn@edit')->name('page.checkinedit');
    Route::any('/checkin/complete', 'CheckIn@complete')->name('page.checkincomplete');
    Route::any('/checkin/roombill', 'CheckIn@roombill')->name('page.checkinroombill');
    Route::any('/checkinlist', 'CheckIn@checkinlist')->name('page.checkinlist');
    Route::post('/ajax/loadcheckinroom', 'CheckIn@ajaxLoadRoom')->name('page.loadcheckinroom');
    Route::post('/ajax/loadsubsuitejson', 'CheckIn@ajaxLoadSubSuiteJson')->name('page.loadsubsuitejson');

    //Check-out
    Route::any('/payment', 'CheckOut@index')->name('page.payment');
    Route::any('/paymentdetail', 'CheckOut@paymentDetail')->name('page.paymentdetail');
    Route::any('/debtdetail', 'CheckOut@debtDetail')->name('page.debtdetail');
    Route::any('/payment/complete', 'CheckOut@paymentComplete')->name('page.paymentcomplete');
    Route::any('/checkoutlist', 'CheckOut@checkoutlist')->name('page.checkoutlist');
    Route::post('/ajax/prettycheckout', 'CheckOut@prettyCheckout')->name('page.prettycheckout');
    Route::post('/ajax/cancelBill', 'CheckOut@cancelBill')->name('page.cancelBill');
    Route::post('/ajax/prettyaddtime', 'CheckOut@prettyAddTime')->name('page.prettyaddtime');
    Route::post('/ajax/moveroom', 'CheckOut@moveRoom')->name('page.moveroom');
    Route::post('/ajax/roomaddtime', 'CheckOut@roomAddTime')->name('page.roomaddtime');
    Route::post('/ajax/roomedittime', 'CheckOut@roomEditTime')->name('page.roomedittime');
    Route::post('/ajax/prettyedittime', 'CheckOut@prettyEditTime')->name('page.prettyedittime');
    Route::post('/ajax/roomcheckout', 'CheckOut@roomCheckout')->name('page.roomcheckout');
    Route::post('/ajax/telsave', 'CheckOut@telSave')->name('page.telsave');
    Route::post('/ajax/telroomsave', 'CheckOut@telRoomSave')->name('page.telroomsave');
    Route::post('/ajax/updateRecept', 'CheckOut@updateRecept')->name('page.updateRecept');
    Route::any('/ajax/loadworkinglist', 'CheckOut@ajaxLoadWorkingList')->name('page.ajaxworkinglist');
    Route::any('/ajax/loadpaymentlist', 'CheckOut@ajaxLoadPaymentList')->name('page.ajaxpaymentlist');
    Route::any('/checkout/receiptbill', 'CheckOut@receiptbill')->name('page.checkoutreceiptbill');
    Route::any('/checkout/paymentbill', 'CheckOut@paymentbill')->name('page.paymentbill');
    Route::any('/paymentlist', 'CheckOut@paymentList')->name('page.paymentlist');
    Route::any('/ajax/paymentlist', 'CheckOut@ajaxPaymentList')->name('page.ajaxpaymentlist');
    Route::any('/paymentdebtlist', 'CheckOut@paymentDebtList')->name('page.paymentdebtlist');
    Route::any('/ajax/paymentdebtlist', 'CheckOut@ajaxPaymentDebtList')->name('page.paymentdebtlist');

    //User setting
    Route::any('/changepassword', 'UserSetting@changePassword')->name('page.changepassword');
    Route::any('/usersetting', 'UserSetting@index')->name('page.usersetting');
    Route::any('/useradd', 'UserSetting@useradd')->name('page.useradd');
    Route::any('/useredit/{id}', 'UserSetting@useredit')->name('page.useredit');
    Route::post('/userdel', 'UserSetting@userdel')->name('page.userdel');
    Route::any('/ajax/loaduser','UserSetting@ajaxUser')->name('ajax.loadusersetting');

    //Role setting
    Route::any('/userrole', 'UserSetting@userrole')->name('page.userrole');
    Route::any('/userroleadd', 'UserSetting@userroleadd')->name('page.userroleadd');
    Route::any('/userroleedit/{id}', 'UserSetting@userroleedit')->name('page.userroleedit');
    Route::any('/userrolepermission/{id}', 'UserSetting@userrolepermission')->name('page.userrolepermission');
    Route::post('/userroledel', 'UserSetting@userroledel')->name('page.userroledel');
    Route::any('/ajax/loaduserrole','UserSetting@ajaxUserRole')->name('ajax.loaduserrole');

    //Employee
    Route::any('/time', 'Employee@time')->name('page.time');
    Route::post('/ajax/timestamp', 'Employee@ajaxtimestamp')->name('page.ajaxtimestamp');
    Route::any('/reception', 'Employee@reception')->name('page.reception');
    Route::any('/receptionadd', 'Employee@receptionadd')->name('page.receptionadd');
    Route::any('/receptionedit/{id}', 'Employee@receptionedit')->name('page.receptionedit');
    Route::post('/receptdel', 'Employee@receptdel')->name('page.receptdel');
    Route::post('/ajax/loadreceipt','Employee@ajaxRecept')->name('ajax.loadreceipt');
    Route::post('/ajax/loadreceiptpopup','Employee@ajaxReceiptPopup')->name('ajax.loadreceiptpopup');
    Route::any('/pretty', 'Employee@pretty')->name('page.pretty');
    Route::any('/prettytime', 'Employee@prettytime')->name('page.prettytime');
    Route::any('/prettyadd', 'Employee@prettyadd')->name('page.prettyadd');
    Route::any('/prettytypeadd', 'Employee@prettytypeadd')->name('page.prettytypeadd');
    Route::any('/prettytypeedit/{id}', 'Employee@prettytypeedit')->name('page.prettytypeedit');
    Route::any('/prettyedit/{id}', 'Employee@prettyedit')->name('page.prettyedit');
    Route::post('/prettydel', 'Employee@prettydel')->name('page.prettydel');
    Route::post('/prettytypedel', 'Employee@prettytypedel')->name('page.prettytypedel');
    Route::post('/ajax/loadprettypopup','Employee@ajaxPrettyPopup')->name('ajax.loadprettypopup');
    Route::post('/ajax/loadprettyreception','Employee@ajaxPrettyForReception')->name('ajax.loadprettyreception');
    Route::post('/ajax/loadpretty','Employee@ajaxPretty')->name('ajax.loadpretty');
    Route::post('/ajax/getprettybycard', 'Employee@ajaxGetPrettybyCard')->name('page.getprettybycard');
    Route::any('/prettypay', 'Employee@prettyPay')->name('page.prettypay');
    Route::any('/prettywage/{id}', 'Employee@prettyWage')->name('page.prettywage');
    Route::any('/prettywage-success', 'Employee@prettyWageSuccess')->name('page.prettywagesuccess');
    Route::any('/prettywage-bill', 'Employee@prettyWageBill')->name('page.prettywagebill');
    Route::post('/ajax/loadprettypay', 'Employee@ajaxPrettyPay')->name('page.loadprettypay');
    Route::post('/ajax/loadprettypayrec', 'Employee@ajaxPrettyPayRec')->name('page.loadprettypayrec');

    //Room
    Route::any('/roomsetting', 'RoomSetting@index')->name('page.roomsetting');
    Route::any('/roomadd', 'RoomSetting@roomadd')->name('page.roomadd');
    Route::any('/roomedit/{id}', 'RoomSetting@roomedit')->name('page.roomedit');
    Route::post('/roomdel', 'RoomSetting@roomdel')->name('page.roomdel');
    Route::any('/ajax/loadroom','RoomSetting@ajaxRoom')->name('ajax.loadroomsetting');
    Route::any('/ajax/loadroompopup','RoomSetting@ajaxRoomPopup')->name('ajax.loadroomsettingpopup');
    Route::any('/roomtypesetting', 'RoomSetting@roomtype')->name('page.roomtypesetting');
    Route::any('/roomtypeadd', 'RoomSetting@roomtypeadd')->name('page.roomtypeadd');
    Route::any('/roomtypeedit/{id}', 'RoomSetting@roomtypeedit')->name('page.roomtypeedit');
    Route::post('/roomtypedel', 'RoomSetting@roomtypedel')->name('page.roomtypedel');
    Route::any('/ajax/loadroomtype','RoomSetting@ajaxRoomType')->name('ajax.loadroomtypesetting');

    //Member
    Route::any('/member/sync', 'Member@memberSync')->name('page.membersync');
    Route::any('/member', 'Member@index')->name('page.member');
    Route::any('/memberpay', 'Member@memberPay')->name('page.memberpay');
    Route::any('/memberdebt', 'Member@memberDebt')->name('page.memberdebt');
    Route::any('/memberdebtpay/{id}', 'Member@memberDebtPay')->name('page.memberdebtpay');
    Route::any('/memberadd', 'Member@add')->name('page.memberadd');
    Route::any('/memberedit/{id}', 'Member@edit')->name('page.memberedit');
    Route::any('/membertopup/{id}', 'Member@topup')->name('page.membertopup');
    Route::any('/memberdeduct/{id}', 'Member@deduct')->name('page.memberdeduct');
    Route::post('/memberdel', 'Member@delete')->name('page.memberdel');
    Route::any('/member/paysuccess', 'Member@paysuccess')->name('page.memberpaysuccess');
    Route::any('/member/bill', 'Member@memberbill')->name('page.memberbill');
    Route::any('/ajax/loadmember','Member@ajaxLoadMember')->name('ajax.loadmember');
    Route::any('/ajax/loadmemberpay','Member@ajaxLoadMemberPay')->name('ajax.loadmemberpay');
    Route::any('/ajax/loadmemberdebt','Member@ajaxLoadMemberDebt')->name('ajax.loadmemberdebt');
    Route::any('/ajax/loadmemberpopup', 'Member@ajaxLoadmemberPopup')->name('page.loadmemberpopup');

    //Report
    Route::any('/statusall', 'Report@statusall')->name('page.statusall');
    Route::any('/report/daily', 'Report@daily')->name('page.daily');
    Route::any('/report/daily-print', 'Report@dailyPrint')->name('page.dailyprint');
    Route::any('/report/membersale', 'Report@membersale')->name('page.reportmembersale');
    Route::any('/report/membersale-print', 'Report@membersalePrint')->name('page.reportmembersaleprint');
    Route::any('/report/memberuse', 'Report@memberuse')->name('page.reportmemberuse');
    Route::any('/report/memberuse-print', 'Report@memberusePrint')->name('page.reportmemberuseprint');
    Route::any('/report/pr-monthly', 'Report@prettyMonthly')->name('page.prmonthly');
    Route::any('/report/pr-monthly-print', 'Report@prettyMonthlyPrint')->name('page.prmonthlyprint');
    Route::any('/report/recept-monthly', 'Report@receptMonthly')->name('page.receptmonthly');
    Route::any('/report/recept-monthly-print', 'Report@receptMonthlyPrint')->name('page.receptmonthlyprint');
    Route::any('/report/receptionwork', 'Report@receptionwork')->name('page.receptionwork');
    Route::any('/report/receptwork-print', 'Report@receptionworkPrint')->name('page.receptionworkprint');

    //SETTING
    Route::any('/systemsetting', 'Setting@index')->name('page.setting');

    //TEST
    Route::any('/testprint', 'Setting@testprint')->name('page.testprint');

});

Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    // return what you want
});
