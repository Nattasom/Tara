<div class="loading-box hide"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i><br/><br/>Loading...</div>
                                    <div class="table-responsive"> 
                                        <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                            <th>#</th>
                                            <th>หมายเลขพนักงานต้อนรับ</th>
                                            <th>ชื่อพนักงาน</th>
                                            <th>ชื่อเล่น</th>
                                            <th>วันที่ปฏิบัติงาน</th>
                                            <th>จัดการ</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($list as $key=>$value)
                                                <tr>
                                                    <th scope="row">{{++$paging->start}}</th>
                                                    <td>{{$value->Reception_ID}}</td>
                                                    <td>{{$value->First_Name}} {{$value->Last_Name}}</td>
                                                    <td>{{$value->Nick_Name}}</td>
                                                    <td>{{$value->Work_From_Date}}</td>
                                                    <td>
                                                       @if($flag_edit)
                                                            <a href="{{Config::get('app.context')}}receptionedit/{{$value->SysReception_ID}}" class="btn btn-primary btn-sm" ><i class="fa fa-edit"></i> แก้ไข</a>
                                                            <button class="btn btn-danger btn-sm btn-del" data-id="{{$value->SysReception_ID}}"><i class="fa fa-trash"></i> ลบ</button>
                                                        @else
                                                        -
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                            
                                        </tbody>
                                        </table>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="paging-detail-text">{{$paging->paging_detail}}</div>
                                        </div>
                                        <div class="col-md-7 text-md-right">
                                            <nav >
                                                <ul class="pagination">
                                                    {!!$paging->renderHtml()!!}
                                                </ul>
                                            </nav>
                                        </div>
                                    </div> 