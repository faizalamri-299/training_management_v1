@extends('layouts.mainmaster')
@section('page_heading','Desktop')
@section('section')

   

  <div class="desktop col-sm-10 col-sm-offset-1">
 
<div class="panel panel-primary">
  <div class="panel-heading">Invoice Setting</div>
  <div class="panel-body">
  @if (count($invoice) > 0)
  <table class="table table-striped">
    <thead>
      <tr class="info">
        <th class="text-center">No</th>
        <th class="text-center">Prefix</th>
        <th class="text-center">Last Index</th>
        <th class="text-center">Year</th>
      </tr>
    </thead>
    <tbody>

        @foreach ($invoice as $key=>$invoice)
         <tr class="">
        <td class="text-center">{{$key+1}}</td>
        <td class="text-center">{{ $invoice->invCode}}</td>
        <td class="text-center">{{$invoice->invLastNo}}</td>
        <td class="text-center">{{$invoice->invYear}}</td>
      </tr>
        @endforeach
    </tbody>
  </table>
     
        @endif
  <form action="{{url('updinvoicesetting')}}" method="POST">
                {{ csrf_field() }}
                <div class="col-sm-3">
            <div class="form-group"><h4>
                <label>Invoice Prefix</label>
                <input class="form-control" name="prefix" type="text" placeholder="Halal Executive Jakim" required></h4>
                
                    </div> </div>
                <div class="col-sm-3">
                    <div class="form-group"><h4>
                <label>Last Index</label>
                <input class="form-control" name="lastindex" type="text" placeholder="Dewan Al-Marbawiy-UTM" required></h4>
                
            </div>
                </div>
                <div class="col-sm-3">
            <div class="form-group"><h4>
                <label>Year</label>
                <input class="form-control" name="year" type="text" placeholder="Halal Executive Jakim" required></h4>
                
                    </div> </div>
              
                 <div class="col-sm-3 ">
                 <label></label>
                 <button class="button button--large center " type="submit">Add/Update</button>
                    </div>
                 
                   
            </form>
  </div>
</div>
<div class="panel panel-primary">
  <div class="panel-heading">Biller Informations</div>
  <div class="panel-body">
 
  <form action="{{url('updbiller')}}" method="POST">
                {{ csrf_field() }}
                
                <div class="col-sm-6">
                <div class="form-group"><h4>
                <label>Biller Name</label>
                <input class="form-control" name="name" type="text"  value="{{$biller->billerName}}" placeholder="Halal Executive Jakim" required></h4>
                </div>
                <div class="form-group"><h4>
                <label>Phone No</label>
                <input class="form-control" name="tel" type="text"  value="{{$biller->billerTel}}" placeholder="Halal Executive Jakim" required></h4>
                
                    </div> 
                    <div class="form-group"><h4>
                <label>Subject</label>
                <input class="form-control" name="subject" type="text"  value="{{$biller->billerSubject}}" placeholder="Halal Executive Jakim" required></h4>
                
                    </div> 
                </div>
               

                <div class="col-sm-6">
                <div class="form-group"><h4>
                <label>Biller Address</label>
                <textarea class="form-control"  rows="4"  name="address" placeholder="Eg: NO1 UTM Johor" required>{{$biller->billerAddress}} </textarea>
                            
            </div>

              <div class="form-group"><h4>
                <label>Email</label>
                <input class="form-control" name="email" type="text" placeholder="Halal Executive Jakim" value="{{$biller->billerEmail}}" required></h4>
                
                    </div> 
                </div>
              

                     <div class="col-sm-12">
            <div class="form-group"><h4>
                <label>Biller Notes</label>
                <textarea id="summernote" name="note">{!!$biller->billerNote!!}</textarea>
                    </div> </div>
                    
                 <div class="col-sm-3 ">
                 <label></label>
                 <button class="button button--large center " type="submit">Update</button>
                    </div>
                 
                   
            </form>
  </div>
</div>
<div class="panel panel-primary">
  <div class="panel-heading">States List</div>
  <div class="panel-body">
  @if (count($states) > 0)
  <div class="col-sm-5 ">
  <table class="table table-striped ">
    <thead>
      <tr class="info">
        <th class="text-center" width="20%">No</th>
        <th class="text-center">State</th>
      </tr>
    </thead>
    <tbody>

        @foreach ($states as $key=>$states)
         <tr class="">
        <td class="text-center">{{$key+1}}</td>
        <td class="text-center">{{ $states->stateName}}</td>
      </tr>
        @endforeach
    </tbody>
  </table>
     </div>
        @endif
  <form action="{{url('addState')}}" method="POST">
                {{ csrf_field() }}
                <div class="col-sm-5">
            <div class="form-group"><h4>
                <label>State Name</label>
                <input class="form-control" name="stateName" type="text" placeholder="Johor" required></h4>
                
                    </div> </div>
               
              
                 <div class="col-sm-2 ">
                 <label></label>
                 <button class="button button--large center " type="submit">Add</button>
                    </div>
                 
                   
            </form>
  </div>
</div>
</div>
@stop