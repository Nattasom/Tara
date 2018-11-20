 <header class="header">   
    <nav class="navbar navbar-expand-lg">
    <div class="container-fluid d-flex align-items-center justify-content-between">
        <div class="navbar-header">
        <!-- Navbar Header--><a href="{{Config::get('app.context')}}home" class="navbar-brand">
            <div class="brand-text brand-big visible text-uppercase"><img src="{{Config::get('app.context')}}assets/img/logotara.png" width="100"/></div>
            <div class="brand-text brand-sm"><img src="{{Config::get('app.context')}}assets/img/logotara.png" width="100"/></div></a>
        <!-- Sidebar Toggle Btn-->
        <button class="sidebar-toggle active"><i class="fa fa-long-arrow-right"></i></button>
        </div>
        <div class="list-inline-item dropdown">
        <a id="languages" rel="nofollow" data-target="#" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link language dropdown-toggle"><span class="d-none d-sm-inline-block"> 
        @if(Session::has('userinfo'))
            <!-- <div class="avatar"><img src="assets/img/avatar-6.jpg" alt="..." class="img-fluid rounded-circle"></div> -->
            <span>{{Session::get('userinfo')->User_Fullname}}</span>
        @endif
        </a>
            <div aria-labelledby="languages" class="dropdown-menu" style="display: none;">
            <a id="logout" href="{{Config::get('app.context')}}changepassword" class="nav-link"><i class="icon-controls"></i> เปลี่ยนรหัสผ่าน </a>
            <a id="logout" href="{{URL::to('logout')}}" class="nav-link"><i class="icon-logout"></i> ออกจากระบบ</a>
            </div>
        </div>
    </div>
    </nav>
</header>