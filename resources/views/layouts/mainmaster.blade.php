@extends('layouts.core')

@section('body')

<ons-splitter>
  <ons-splitter-side id="sidebar" side="left" width="220px" collapse>
    <ons-page>
      <ons-list> 
        <ons-list-item onclick="window.location='{{ url("/") }}'"  tappable>
           
          Home
                
        </ons-list-item>
        <ons-list-item onclick="window.location='{{ url("setting") }}'" tappable>
          Settings
        </ons-list-item>
        <ons-list-item onclick="fn.load('about.html')" tappable>
          About
        </ons-list-item>
          
        <ons-list-item onclick="window.location='{{ url("logout") }}'"  tappable>
          Logout
        </ons-list-item>
          
      </ons-list>
    </ons-page>
  </ons-splitter-side>
  <ons-splitter-content id="content" >
      
<ons-page id="helloworld-page">
  <ons-toolbar  class="hideprint">
  <div class="left">
       <ons-toolbar-button onclick="window.location='{{ url("/") }}'"  >
          <ons-icon icon="md-home" size="2em"></ons-icon>
        </ons-toolbar-button>
    </div>
    <div class="center">@yield('page_heading')</div>
    <div class="right">
       <ons-toolbar-button onclick="openMenu()">
          <ons-icon icon="md-menu" size="2em"></ons-icon>
        </ons-toolbar-button>
    </div>
  </ons-toolbar>

    
  <div class="col-md-12">
 
    @yield('section')
    </div>
</ons-page>
    </ons-splitter-content>
</ons-splitter>


@stop