<div class="loading-box hide"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i><br/><br/>Loading...</div>
                                    <div class="table-responsive"> 
                                        <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                            <th>รหัสพนักงาน</th>
                                            <th>ชื่อพนักงาน</th>
                                            <th>ชื่อเล่น</th>
                                            <th>ประเภท</th>
                                            <th>สถานะ</th>
                                            <th>วันที่ปฏิบัติงาน</th>
                                            <th>จัดการ</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($pretty_list as $key=>$value)
                                                <tr>
                                                    <td>{{$value->Angel_ID}}</td>
                                                    <td>{{$value->First_Name}} {{$value->Last_Name}}</td>
                                                    <td>{{$value->Nick_Name}}</td>
                                                    <td>
                                                        {{$value->Angel_Type_Code}}
                                                    </td>
                                                    <td>
                                                        @if($value->Angel_Status=="AB")
                                                        <span class="text-warning">ไม่มาทำงาน</span>
                                                        @elseif($value->Angel_Status=="NW")
                                                        <span class="text-success">พร้อมทำงาน</span>
                                                        @elseif($value->Angel_Status=="WK")
                                                        <span class="text-danger">กำลังทำงาน</span>
                                                        @endif
                                                    </td>
                                                    <td>{{$util->dateToThaiFormat($value->Work_From_Date)}}</td>
                                                    <td>
                                                     @if($flag_edit)
<a href="{{Config::get('app.context')}}prettyedit/{{$value->SysAngel_ID}}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> แก้ไข</a>
                                                            <button type="button" onclick="delPretty(this)" data-id="{{$value->SysAngel_ID}}" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> ลบ</button>
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