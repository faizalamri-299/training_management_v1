@extends('layouts.mainmaster')
@section('page_heading','Desktop')
@section('section')

   

  <div class="desktop col-sm-10 col-sm-offset-1">

  <div class="panel panel-primary">
  <div class="panel-heading">States List</div>
  <div class="panel-body">
  @if (count($users) > 0)
  <div class="col-sm-4 ">
  <table class="table table-striped ">
    <thead>
      <tr class="info">
        <th class="text-center" width="20%">No</th>
        <th class="text-center">Username</th>
        <th></th>
      </tr>
    </thead>
    <tbody>

        @foreach ($users as $key=>$users)
         <tr class="">
        <td class="text-center">{{$key+1}}</td>
        <td class="text-center">{{ $users->username}}</td>
        <td>
            <button type="button" data-toggle="modal"
                data-target="#edtUser" data-userid="{{$users->userPK}}"
                class="btn btn-warning btn-circle btn-sm">
                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
            </button>
        </td>
      </tr>
        @endforeach
    </tbody>
  </table>
     </div>
        @endif
  <form action="{{url('addadmin')}}" method="POST">
                {{ csrf_field() }}
                <div class="col-sm-3">
            <div class="form-group"><h4>
                <label>Userame</label>
                <input class="form-control" name="username" type="text" placeholder="holistics" required></h4>
                
                    </div> </div>
                    <div class="col-sm-3">
            <div class="form-group"><h4>
                <label>Password</label>
                <input class="form-control" name="password" type="password" placeholder="Johor" required></h4>
                
                    </div> </div>
               
              
                 <div class="col-sm-2 ">
                 <label></label>
                 <button class="button button--large center " type="submit">Add</button>
                    </div>
                 
                   
            </form>
  </div>
</div>
</div>

<div class="modal fade qs-item-modal-sm" id="edtUser" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="myLargeModalLabel">Change Password</h4>
                </div>
                
            <form action="{{url('updpwd')}}" method="POST">
                {{ csrf_field() }}
                
                            <input type="hidden" name="userid" value="">
                <div class="col-sm-12">
                    <h4>
                        <label>New Password</label>
                        <input class="form-control" type="password" name="newpwd">
                    </h4>
                </div>
                <button class="button button--large " type="submit">Update</button>
            </form>
            </div>
        </div>
    </div>
@stop