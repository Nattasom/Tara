<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head> 
        @include('includes.head')
  </head>
  <body>
    @include('includes.header')
    <div class="d-flex align-items-stretch">
      <!-- Sidebar Navigation-->
      @include('includes.sidebar')
      <!-- Sidebar Navigation end-->
      <div class="page-content active">
        @yield('content')

        @include('includes.footer')
      </div>
    </div>
    @include('includes.script')
    @yield('script')
  </body>
</html>
