<nav id="sidebar" class="shrinked">
<!-- Sidebar Header-->
<div class="line gap-bottom">
    
</div>
<!-- Sidebar Navidation Menus--><span class="heading">Main</span>
<ul class="list-unstyled" id="main-menu">
    @if(Session::has('userinfo'))
        @php($lastPG = "")
        @foreach (Session::get('userinfo')->Menu as $key => $menu)
            @php($pgUrl = "")
            @php($pgCollapse = "")
            @if($menu->page_group_hassub=="1")
                @php($pgUrl = "#".$menu->page_group_url)
                @php($pgCollapse = "aria-expanded=false data-toggle=collapse")
            @else
                @php($pgUrl = Config::get('app.context').$menu->page_group_url)
            @endif
            <li id="{{$menu->page_group_element_id}}"><a href="{{$pgUrl}}" {{$pgCollapse}}> {!! $menu->page_group_icon !!}{{$menu->page_group_name}}</a>
                @if($menu->page_group_hassub=="1")
                    <ul id="{{$menu->page_group_url}}" class="collapse list-unstyled ">
                        @foreach($menu->sub_menu as $k=>$v)
                            <li><a href="{{Config::get('app.context')}}{{$v->page_url}}">{{$v->page_name}}</a></li>
                        @endforeach
                    </ul>
                @endif
            </li>
            
        @endforeach
    @endif
</nav>