@extends('layouts.core')

@section('body')

 <ons-page>
      <ons-toolbar>
        <div class="center">Log In</div>
      </ons-toolbar>

      <div class="login-form">
          
            <div class="col-md-4 col-md-offset-4">
                 <form action="{{url('loginauth')}}" method="POST">
                {{ csrf_field() }}
                <div class="col-sm-12">
            <div class="form-group"><h4>
                <label>Username</label>
                <input class="form-control" name="username"  id="username" type="text" placeholder="Ahmad" required></h4>
                
                    </div> </div>
                <div class="col-sm-12   ">
                    <div class="form-group"><h4>
                <label>Password</label>
                <input class="form-control" name="password" id="password" type="password" placeholder="*****" required></h4>
                
            </div>
                     </div>
                     
                     <button class="button login-button button--large" type="submit"> Log In</button>
                </form>
               
                @if (count($errors) > 0)
                    @foreach ($errors->all() as $error)
                    <div class="alert alert-warning" role="alert">
                        <div class="row">
                            <div class="col-sm-1">
                                <i class="fa fa-warning"></i>
                            </div>
                            <div class="col-sm-10">
                                {{ $error }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endif
                @if (Session::has('success'))
                <div class="alert alert-success" role="alert">
                    <div class="row">
                        <div class="col-sm-1">
                            <i class="fa fa-user"></i>
                        </div>
                        <div class="col-sm-10">
                             {{ Session::get('success') }}
                        </div>
                    </div>
                </div>
                @endif
            </div>

      </div>

</ons-page>

@stop