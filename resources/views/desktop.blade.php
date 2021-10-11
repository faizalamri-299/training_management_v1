@extends('layouts.mainmaster')
@section('page_heading','Desktop')
@section('section')

   

 <div class="desktop col-sm-10 col-sm-offset-1">

 <div class="col-md-3">
        <a class="btn btn-block btn-lg btn-success"  href="{{url('course/')}}" >
            <i class="fa fa-book" id="icone_grande"></i> <br><br>
            <span class="texto_grande"><i class="fa fa-plus-circle"></i> Courses</span></a>
      </div>
      <div class="col-md-3">
        <a class="btn btn-block btn-lg btn-danger"href="{{url('train/')}}">
            <i class="fa fa-list" id="icone_grande"></i> <br><br>
            <span class="texto_grande"><i class="fa fa-plus-circle"></i> Trainings</span></a>
      </div>
      <div class="col-md-3">
        <a class="btn btn-block btn-lg btn-primary"href="{{url('setting/')}}">
            <i class="fa fa-cog fa-spin" id="icone_grande"></i> <br><br>
            <span class="texto_grande"><i class="fa fa-edit"></i> SETTING</span></a>
      </div>
      
</div>
@stop