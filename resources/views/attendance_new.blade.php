@extends('layouts.mainmaster')
@section('page_heading',$train->trainName)
@section('section')
@php
  $begin = new DateTime( "@".strtotime($train->trainDtStart.' UTC') );
  $end   = new DateTime( "@".strtotime($train->trainDtEnd.' UTC') );
  $dateArray=[];
@endphp
<div class="col-sm-6">
<button  data-toggle="modal" data-target="#addindicator"  class="btn btn-lg btn-primary btn-block hideprint" type="button">Indicator</button>
</div><div class="col-sm-6">
<button onclick="Print()" class="btn btn-lg btn-primary btn-block hideprint" type="button">Print</button>
</div>
<div class="col-sm-6">
<a class="btn btn-lg btn-primary btn-block hideprint" href="{{url('migrateattnd/'.$train->trainPK)}}"  >
Re-Migrate Data
      </a>
</div>
<div class="col-sm-10 col-sm-offset-1 showprint">
 <img  class="center showprint" src="{{ asset('./logo.png') }}"/>
 <h2 class="text-center showprint">{{$train->courseName}}</i></h2>
 <h4 class="text-center showprint">{{date('d/m/Y',strtotime( $train->trainDtStart)).' - '.date('d/m/Y',strtotime( $train->trainDtEnd))}}</i></h4>
</div>
<div class="col">
  <div class="col">
    <table class="table table-bordered table-striped">
      <thead>
        <tr class="info">
          <th class="text-center">No</th>
          <th>Name</th>
          @for ($i = $begin; $i <= $end; $i->modify('+1 day'))
            @if(!in_array($i->format("Y-m-d"), $train->trainDateExclude))
            <th class="text-center">{{$i->format("d M Y")}}
            @php array_push($dateArray, $i->format("Y-m-d"));  @endphp
              <br/>
              
              <form action="{{ url('excludetraindate') }}" method="POST" class="dropdown-item hideprint">
                            {{ csrf_field() }} {{ method_field('DELETE') }}
                            <input type="hidden" name="pk" value="{{$train->trainPK}}" />
                            <input type="hidden" name="date" value='{{$i->format("Y-m-d")}}'/>
                            <button type="submit" class="btn btn-link link-delete"><i class="fa fa-trash"></i></button>
                        </form>
              
            </th>
            @endif
          @endfor
        </tr>
      </thead>
      <tbody>
        @if (count($train->trainingAttnd) > 0)
        
          @foreach ($train->trainingAttnd as $key=>$attnd)
          <tr>
            <td class="text-center">{{$key+1}}</td>
            <td>
              <a class="hideprint" href="{{url('trneeattendance/'.encrypt($attnd->userPK))}}"  >
              {{strtoupper($attnd->trneeName)}}
              </a>
              
            <span class="showprint">
              {{strtoupper($attnd->trneeName)}}
            </span>
            </td>
            @foreach ($attnd->attendance as $p=>$a)

            @php
              $exact_date = new DateTime( "@".strtotime($train->trainDtStart.' UTC'));
              $exact_date->modify('+'.$p.' day');
            @endphp
            @if(!in_array($a->date, $train->trainDateExclude))
            <td class="text-center">
            @if($exact_date->format("Y-m-d")==$a->date)
              @foreach ($a->record as $key=>$b)
                @if(empty($b))
                
              <button type="button" class="btn btn-link hideprint"  data-toggle="modal" data-target="#addAttnd"  data-pk="{{$attnd->userPK}}"  data-date="{{$a->date}}" data-post="{{$key}}">
              <i class="fa fa-clock-o "></i>
              </button>
                @else
                <button type="button" class="btn btn-link" data-toggle="modal" data-target="#addAttnd"  data-pk="{{$attnd->userPK}}" data-date="{{$a->date}}"  data-time="{{$b}}" data-post="{{$key}}">
                  {{$b}}
                </button>
                @endif
                <br/>
              @endforeach
            @endif
            </td>
            @endif
            @endforeach
          </tr>
          @endforeach
        @endif
      </tbody>
    </table>
  </div>
</div>


<div class="modal fade " id="addindicator" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="myLargeModalLabel">Attendance Indicator</h4>
                </div>
                <div class="modal-body">
                        <div class="row">
                                
            <div class="col-sm-10 col-sm-offset-1">  
              <ul class="media-list">
                @if (count($train->attndNote) > 0) 
                @foreach ($train->attndNote as $key=>$note)
                <li class="media">
                  
                  <div class="media-body">
                    <h5 class="media-heading"> {{$note->code." - ".$note->desc}}</h5>
                  </div>
                  <div class="media-right">
                    <form action="{{ url('removeindicator') }}" method="POST" class="dropdown-item hideprint">
                      {{ csrf_field() }} {{ method_field('DELETE') }}
                      <input type="hidden" name="pk" value="{{$train->trainPK}}" />
                      <input type="hidden" name="code" value="{{$note->code}}" />
                      <button type="submit" class="btn btn-link link-delete right"><i class="fa fa-trash"></i></button>
                  </form>
                  </div>
                </li>
                @endforeach
                @endif
              </ul>
            </div>
            <div class="col-sm-10 col-sm-offset-1">  
                <form class="form-horizontal" action="{{url('addindicator')}}" id="record" method="post">
                {{ csrf_field() }}
                      <input type="hidden" name="id" value="{{$train->trainPK}}">
						<div class="form-group">
              
							<div class="cols-sm-2">
              
              <label for="name" class="cols-sm-2 control-label">Code</label>
								<div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-clock-o" aria-hidden="true"></i></span>
                  <input type="text" class="form-control" name="code" required />
								</div>
							</div>
              <div class="cols-sm-10">
              
              <label for="name" class="cols-sm-2 control-label">Notes</label>
								<div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-clock-o" aria-hidden="true"></i></span>
                  <input type="text" class="form-control" name="desc" required />
								</div>
							</div>
						</div>
                            <div class="form-group ">
                                
                    <button class="button button--large" type="submit">Submit</button>
						</div>
                    </form>
                
            </div>
                    </div>
    </div>
            </div>
        </div>
</div>


<div class="modal fade " id="addAttnd" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="myLargeModalLabel">Attendance Record</h4>
                </div>
                <div class="modal-body">
                        <div class="row">
                                
            <div class="col-sm-10 col-sm-offset-1">  
                <form class="form-horizontal" action="{{url('addnewattnd')}}" id="record" method="post">
                {{ csrf_field() }}
                      <input type="hidden" name="id" value="{{$train->trainPK}}">
                      <input type="hidden" name="pk">
                      <input type="hidden" name="date">
                      <input type="hidden" name="post">
						<div class="form-group">
              <label for="name" id="editLabel" class="cols-sm-2 control-label">Time</label>
              
              <a href="#" onclick="changeinputtype()" > <i class="fa fa-refresh "></i></a>
							<div class="cols-sm-10">
								<div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-clock-o" aria-hidden="true"></i></span>
                  <input type="time" id="appt" class="form-control" name="time" required />
								</div>
							</div>
						</div>
                            <div class="form-group ">
                                
                    <button class="button button--large" type="submit">Submit</button>
						</div>
                    </form>
                
            </div>
                    </div>
    </div>
            </div>
        </div>
</div>
@endsection

@section('page-script')
<script>
const data={!! count($train->trainingAttnd) >0?json_encode($train->trainingAttnd):"[]"!!}
const indicator={!! count($train->attndNote) >0?json_encode($train->attndNote):"[]"!!}
const datearray={!! count($dateArray) >0?json_encode($dateArray):"[]"!!}
console.log(data);
// console.log(datearray);
  const Print=()=>{
        var  dd = {
                      // a string or { width: number, height: number }
          pageSize: 'A4',
          // by default we use portrait, you can change it to landscape if you wish
          pageOrientation: 'landscape',

          // [left, top, right, bottom] or [horizontal, vertical] or just a number for equal margins
          // pageMargins: [ 40, 60, 40, 60 ],
          fontSize:8,
          images : {},
          content: [{
              image: 'logo',
              width: 300,
              alignment: 'center',
              margin: [0, 1]
          }, {
              text: "{{$train->courseName}}",
              fontSize: 28,
              bold: true,
              alignment: 'center',
              margin: [0, 2]
          }, {
              text: "{{date('d/m/Y',strtotime( $train->trainDtStart)).' - '.date('d/m/Y',strtotime( $train->trainDtEnd))}}",
              fontSize: 14,
              bold: true,
              alignment: 'center',
              margin: [0, 0, 0, 30]
          }],
          footer: {
            
            margin: [40, 0, 30, 0],
            stack: [ ]
          },
      }

      indicator.forEach(function({code,desc},index){
      dd.footer.stack.push({ text:["* ",	{text:code, bold: true}," - ",desc],fontSize: 10, });
    });

    dd.footer.stack.push({ text: 'This is a computer-generated document. No signature is required.',fontSize: 10, });
    


      let tableHeader=[
        {
            text: 'No.',
            bold: true,
            fillColor: '#4CAF50',
            color: '#ffffff'
        }, {
            text: "Name",
            bold: true,
            fillColor: '#4CAF50',
            color: '#ffffff'
        }
      ]
      let tableWidth=['auto', 'auto']
      datearray.forEach(function(date,index){
        let fdate=new Date(date);
        tableHeader.push({
            text: fdate.toLocaleDateString('en-sg', {  year: 'numeric', month: 'short', day: 'numeric' }),
            bold: true,
            fillColor: '#4CAF50',
            color: '#ffffff',
            alignment: 'center',
        });
        tableWidth.push('auto');
    });

      let item = {
          table: {
            headerRows: 1,
            widths:tableWidth,
              body: [
                 tableHeader,
              ]
          }
      }

      data.forEach(function(x,index){
        let row=[{rowSpan: 2, text:index+1}, {rowSpan: 2, text:x.trneeName.toUpperCase()}];
        let row2=['',''];
        if(x.attendance){
          x.attendance.forEach(function(a,ai){
            if(datearray.includes(a.date)){
              let datepost=datearray.indexOf(a.date);
             // row[datepost+2]={text:a.record.join('\n'),alignment: 'center',};
             row[datepost+2]={text:a.record[0], noWrap: Check_arrival(a.record[0])?true:false,alignment: 'center',};
             row2[datepost+2]={text:a.record[1], noWrap:Check_arrival(a.record[1])?true:false,alignment: 'center',};
            }
          })

        }
        item.table.body.push(row);
        item.table.body.push(row2);
    });
    
    dd.content.push(item);
      toDataUrl("{{ asset('./logo.png') }}", function (base64Img) {
            dd.images.logo = base64Img;
            //console.log(JSON.stringify(docDefinition, null, '\t'));

            pdfMake.createPdf(dd).print();

        }, 'image/jpg', 500);

  }
  
  function toDataUrl(src, callback, outputFormat, size) {
    var img = new Image();
    img.onload = function () {
        var canvas = document.createElement('CANVAS');
        var ctx = canvas.getContext('2d');
        var dataURL;
        var calwidth;
        var calheight;

        if (this.width < this.height) {
            var fixsize = size / 2;
            calheight = fixsize;
            calwidth = (fixsize / this.height) * this.width;
        } else {
            calwidth = size;
            calheight = (size / this.width) * this.height;
        }

        canvas.height = calheight;
        canvas.width = size;
        ctx.drawImage(this, canvas.width / 2 - calwidth / 2, 0, calwidth, calheight)
        dataURL = canvas.toDataURL(outputFormat);
        callback(dataURL);
    };
    img.src = src;
}
  const convertTime12to24 = (time12h) => {
  const [time, modifier] = time12h.split(' ');

  let [hours, minutes] = time.split(':');

  if (hours === '12') {
    hours = '00';
  }

  if (modifier === 'PM') {
    hours = parseInt(hours, 10) + 12;
  }

  return `${hours}:${minutes}`;
}
function Check_arrival(time) {
            //Assuming you are working in 12 hour time, 0 is not a valid
            var patt = new RegExp("^(1[0-2]|0?[1-9]):[0-5][0-9] (AM|PM)$");
            var res = patt.test(time);
            return res;
        }
  function changeinputtype() {
    let label= document.getElementById("editLabel").innerText;
    let input= document.getElementById("appt");
    if(label=="Time"){
      document.getElementById("editLabel").innerText = "Notes";
      input.type="text"
    }
    else{
      document.getElementById("editLabel").innerText = "Time";
      input.type="time"
    }
  
}
$('#addAttnd').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
   let pk = button.data('pk') // Extract info from data-* attributes
   let date = button.data('date') // Extract info from data-* attributes
   let post = button.data('post') // Extract info from data-* attributes
   let time = button.data('time') // Extract info from data-* attributes

//   // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
//   // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
  var modal = $(this)
  modal.find('[name="pk"]').val(pk)
  modal.find('[name="date"]').val(date)
  modal.find('[name="post"]').val(post)

  if(Check_arrival(time)){
    modal.find('[name="time"]').val(convertTime12to24(time))
    document.getElementById("editLabel").innerText = "Time";
    document.getElementById("appt").type="time"
    }
   else{
    document.getElementById("editLabel").innerText = "Notes";
    document.getElementById("appt").type="text"
    modal.find('[name="time"]').val(time)
  }
})
</script>
@endsection