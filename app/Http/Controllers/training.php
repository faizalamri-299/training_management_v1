<?php
namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;

use Illuminate\Http\Request;
use DB;
use View;
use Hash;
use App\Http\Controllers\Controller;
use Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Auth;

use PDF;

class training extends Controller

	{
	function add(Request $request)
		{
      //  $response = (object)['status' => '', 'message' => '' ];
        $train = new \App\Models\trainings;
        $train->trainName=$request->trainName;
        $train->courseFK=$request->courseFK;
        $train->trainState=$request->trainState;
        $train->isFulltime=$request->isFulltime;
        $train->trainPlace=$request->trainPlace;
        $train->trainDetail=$request->trainDetail;
        $train->trainAddress=$request->trainAddress;
        $train->trainDtStart=$request->trainDtStart;
        $train->trainDtEnd=$request->trainDtEnd;
        $train->save();
            
        return redirect()->back();
    }
    function deleteTraining(Request $request)
    {
        
    \App\Models\trainee_trainings::where('trainFK',$request->pk)->delete();
    \App\Models\trainings::where('trainPK',$request->pk)->delete();
    return redirect()->back();
}
    function update(Request $request)
		{
      //  $response = (object)['status' => '', 'message' => '' ];
        

        $train = \App\Models\trainings::where('trainPK', $request->trainPK)->firstOrFail();
        $train->trainName=$request->trainName;
        $train->courseFK=$request->courseFK;
        $train->trainState=$request->trainState;
        $train->isFulltime=$request->isFulltime;
        $train->trainPlace=$request->trainPlace;
        $train->trainDetail=$request->trainDetail;
        $train->trainAddress=$request->trainAddress;
        $train->trainDtStart=$request->trainDtStart;
        $train->trainDtEnd=$request->trainDtEnd;
        $train->save();
            
        return redirect()->back();
    }
//     function getCourseTrain($code)
//     {
//         $train =  DB::select(DB::raw("SELECT d.discntVal,d.discntTo,a.*,c.stateName,b.courseFee FROM trainings a inner join courses b on a.courseFK=b.coursePK inner join states c on a.trainState=c.statePK LEFT JOIN discounts d on d.trainFK=a.trainPK and NOW() BETWEEN d.discntFrom and d.discntTo   where b.courseLink=:code  and NOW()<a.trainDtStart") , array(
//             'code' => $code,
//         ));
//     return  response()->json($train)->header('Access-Control-Allow-Origin', '*')->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
    
// }

function getCourseTrain($code,$year=null,$month=null)
{   
    if($year!==null && $month!==null){
    $train =  DB::select(DB::raw("SELECT d.discntVal,d.discntTo,a.*,c.stateName,b.courseFee,b.courseFeeInt FROM trainings a inner join courses b on a.courseFK=b.coursePK inner join states c on a.trainState=c.statePK LEFT JOIN discounts d on d.trainFK=a.trainPK and NOW() BETWEEN d.discntFrom and d.discntTo and d.discntAuto=true   where  MONTH(a.trainDtStart)=:tmonth and YEAR(a.trainDtStart)=:tyear and  b.courseLink=:code and  a.istest=false order by a.trainDtStart asc") , array(
        'code' => $code,'tmonth' => $month,'tyear' => $year,
    ));}
    else if($year!==null){
        $train =  DB::select(DB::raw("SELECT d.discntVal,d.discntTo,a.*,c.stateName,b.courseFee,b.courseFeeInt FROM trainings a inner join courses b on a.courseFK=b.coursePK inner join states c on a.trainState=c.statePK LEFT JOIN discounts d on d.trainFK=a.trainPK and NOW() BETWEEN d.discntFrom and d.discntTo and d.discntAuto=true   where YEAR(a.trainDtStart)=:tyear and  b.courseLink=:code and  a.istest=false order by a.trainDtStart asc") , array(
            'code' => $code,'tyear' => $year,
        ));}
        else{
            $train =  DB::select(DB::raw("SELECT d.discntVal,d.discntTo,a.*,c.stateName,b.courseFee,b.courseFeeInt FROM trainings a inner join courses b on a.courseFK=b.coursePK inner join states c on a.trainState=c.statePK LEFT JOIN discounts d on d.trainFK=a.trainPK and NOW() BETWEEN d.discntFrom and d.discntTo and d.discntAuto=true   where b.courseLink=:code  and NOW()<a.trainDtStart and  a.istest=false order by a.trainDtStart asc") , array(
                'code' => $code,
            ));
        }
    return  response()->json($train)->header('Access-Control-Allow-Origin', '*')->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');

}
function getTraineeDtlAPI($id){
    $train = \App\Models\trainings::where('trainPK', $id)
             ->join('courses', 'courses.coursePK', '=', 'trainings.courseFK')
             ->join('states', 'states.statePK', '=', 'trainings.trainState')
             ->firstOrFail();
    $discnt = \App\Models\discounts::where('trainFK', $id)->whereRaw('CAST(? AS DATE) between discntFrom and discntTo and discntAuto=true', [date('Y-m-d')])->first();
    
    $stdprice= (float) $train->courseFee;
    $stdintprice= (float) $train->courseFeeInt;

    if (!$discnt) {
        // Do stuff if it doesn't exist.
        $discntprice=$stdprice;
        $discntintprice=$stdintprice;
   }
   else{
    $discntprice=round($stdprice-($stdprice*(float)$discnt->discntVal/100));
    //$discntintprice=$stdintprice-($stdintprice*(float)$discnt->discntVal/100);
    $discntintprice=$stdintprice;
   }
        $data = [
            'course'=> $train->courseName,
            'fee'=> $train->courseFee,
            'feeint'=> $train->courseFeeInt,
            'discntprice'=> $discntprice,
            'discntintprice'=> $discntintprice,
            'detail'=> $train->trainDetail,
            'state'=> $train->stateName,
            'date'=> $train->trainDtStart,
            'mode'=>($train->isFulltime?"Full time":"Part time")
            ];
            return  response()->json($data)->header('Access-Control-Allow-Origin', '*')->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');

}
    
    function getdtl($id)
    {
            $id=decrypt($id);
            
        $train = \App\Models\trainings::where('trainPK', $id)->firstOrFail();
            
        $trainee = \App\Models\trainees::leftJoin('trainee_trainings', 'trneePK','=', 'trainee_trainings.trneeFK')
        ->leftJoin('invoices', function($join){
            $join->on('invoices.invTrneeTrainFK', '=', 'trainee_trainings.tnee_trainPK')
            ->where('invoices.invType', 'P');
        })
        ->leftJoin('invoices as i', function($join){
            $join->on('i.invTrneeTrainFK', '=', 'trainee_trainings.tnee_trainPK')
            ->where('i.invType','I');
        })
        ->leftJoin('reciepts', 'reciepts.invFK','=', 'i.invPK')
        ->selectRaw("trainees.*, trainee_trainings.*, invoices.*, i.invPK as realinvoicePK, reciepts.* ")
        ->where('trainFK', $id)->get();
        
        $discnt = \App\Models\discounts::where('trainFK', $id)->get();
        
        $trainlist = \App\Models\trainings::where('istest', '0')->orderBy('trainDtStart', 'DESC')->get();
           // dd($train->trainName);
        
        $attch = \App\Models\attachments::where('courseFK', $train->courseFK)->get();
            
      return view('training', ['train' => $train ,'trainee' => $trainee ,'discnt'=>$discnt,'trainlist'=>$trainlist,'attch'=>$attch]);
        
    }
    
    function adddiscount(Request $request){

        $discnt=new \App\Models\discounts;
        $discnt->trainFK=$request->trainFK;
        $discnt->discntVal=$request->discntval;
        $discnt->discntFrom=$request->discntfrom;
        $discnt->discntTo=$request->discntto;
        $discnt->discntCode=$request->discntcode;
        $discnt->discntAuto=($request->discntauto=="yes" ? true : false);
        $discnt->save();
        
        return redirect()->back();
    }
    function deletediscount(Request $request)
    {
       \App\Models\discounts::where('discntPK',$request->pk)->delete();
        return redirect()->back();
    }
    function changetrainee(Request $request)
	{
        
            $train2 = \App\Models\trainee_trainings::where('tnee_trainPK',$request->pk2)->first();
            $train2->trainFK=$request->newtrainPK;
            $train2->save();

              
            $inv = \App\Models\invoices::where('invPK',$request->invPK)->first();
            
            if($inv->invPromocode!=''){
            $discnt = \App\Models\discounts::where('trainFK', $request->trainFK)->whereRaw('(discntAuto = true or (discntCode=?))',[ $inv->invPromocode])->whereRaw('CAST(? AS DATE) between discntFrom and discntTo', [$inv->invDate])->orderBy('discntVal', 'DESC')->first();
        
            
            if ($discnt) {
                
                $newcode="MIGRATE#".$request->pk;
                $inv->invPromocode=$newcode;
                $inv->save();

                $discnt2= \App\Models\discounts::firstOrNew(['discntCode'=>$newcode]);
                $discnt2->trainFK=$request->newtrainPK;
                $discnt2->discntVal=$discnt->discntVal;
                $discnt2->discntFrom=$discnt->discntFrom;
                $discnt2->discntTo=$discnt->discntTo;
                $discnt2->discntCode=$newcode;
                $discnt2->discntAuto="0";
                $discnt2->save();
           }
        }

            return redirect('training/'.encrypt($request->newtrainPK));
    }

    function deletetrainee(Request $request)
    {
        $inv = \App\Models\invoices::where('invPK',$request->invPK)->delete();
        $train2 = \App\Models\trainee_trainings::where('tnee_trainPK',$request->pk2)->delete();
        // $trainee =  \App\Models\trainees::where('trneePK',$request->pk)->delete();
        return redirect()->back();
    }

    function copytest (Request $request)
    {
        $trainori =  \App\Models\trainings::where('trainPK',$request->trainPK)->first();

        $train = new \App\Models\trainings;
        $train->trainName="#Testing ".$trainori->trainName;
        $train->courseFK=$trainori->courseFK;
        $train->trainState=$trainori->trainState;
        $train->isFulltime=$trainori->isFulltime;
        $train->trainPlace=$trainori->trainPlace;
        $train->trainDetail=$trainori->trainDetail;
        $train->trainAddress=$trainori->trainAddress;
        $train->trainDtStart=date('Y-m-d');
        $train->trainDtEnd=date('Y-m-d', strtotime("+1 month"));
        $train->istest="1";
        $train->save();
            
        
        $traineeori = \App\Models\trainee_trainings::where('trainFK',$request->trainPK)->get();

        foreach($traineeori as $a)
        {
                $train2 = new \App\Models\trainee_trainings;
                $train2->trneeFK=$a->trneeFK;
                $train2->trainFK=$train->trainPK;
                $train2->referrer=$a->referrer;
                $train2->save();
        }

        return redirect('training/'.encrypt($train->trainPK));
    }

    
    function traineepending(Request $request)
	{
        $trainstat= \App\Models\trainee_trainings::where('tnee_trainPK',$request->pk2)->first();
        $trainstat->joinStatus="P";
        $trainstat->save();
        return redirect()->back();
    }
    function traineeconfirm(Request $request)
	{
        $trainstat= \App\Models\trainee_trainings::where('tnee_trainPK',$request->pk2)->first();
        $trainstat->joinStatus="T";
        $trainstat->save();
        return redirect()->back();
    }
    function traineecancel(Request $request)
	{
        $trainstat= \App\Models\trainee_trainings::where('tnee_trainPK',$request->pk2)->first();
        $trainstat->joinStatus="X";
        $trainstat->save();
        return redirect()->back();
    }

    function paypending(Request $request)
	{
        $trainstat= \App\Models\trainee_trainings::where('tnee_trainPK',$request->pk2)->first();
        $trainstat->paymentStatus="P";
        $trainstat->save();
        return redirect()->back();
    }
    function payconfirm(Request $request)
	{
        $trainstat= \App\Models\trainee_trainings::where('tnee_trainPK',$request->pk2)->first();
        $trainstat->paymentStatus="T";
        $trainstat->save();
        return redirect()->back();
    }
    function payfloat(Request $request)
	{
        $trainstat= \App\Models\trainee_trainings::where('tnee_trainPK',$request->pk2)->first();
        $trainstat->paymentStatus="F";
        $trainstat->save();
        return redirect()->back();
    }

    function addtrainee(Request $request)
	{
        if ($request->pk) {
            $trainee =  \App\Models\trainees::where('trneePK',$request->pk)->first();
        
            $trainee->trneeIcNo=$request->trneeIcNo;
            $trainee->trneeName=$request->trneeName;
            $trainee->trneeAddr=$request->trneeAddr;
            $trainee->trneeCmpny=$request->trneeCmpny;
            $trainee->trneeEmail=$request->trneeEmail;
            $trainee->trneePhNo=$request->trneePhNo;
            $trainee->trneePost=$request->trneePost;
            $trainee->trneeDiet=$request->trneeDiet === null ? '' : $request->trneeDiet;
            $trainee->save();

            

            $train2 = \App\Models\trainee_trainings::where('tnee_trainPK',$request->pk2)->first();
            $train2->referrer=$request->referrer === null ? '' : $request->referrer;
            
            $train2->save();
            
            $inv = \App\Models\invoices::where('invPK',$request->invPK)->first();
            $inv->invPromocode=$request->discntcode === null ? '' : $request->discntcode;
            $inv->save();
        }
        else
        {

        
        $trainee =  \App\Models\trainees::firstOrNew(['trneeIcNo'=>$request->trneeIcNo]);
        
            $trainee->trneeIcNo=$request->trneeIcNo;
            $trainee->trneeName=$request->trneeName;
            $trainee->trneeAddr=$request->trneeAddr;
            $trainee->trneeCmpny=$request->trneeCmpny;
            $trainee->trneeEmail=$request->trneeEmail;
            $trainee->trneePhNo=$request->trneePhNo;
            $trainee->trneePost=$request->trneePost;
            $trainee->trneeDiet=$request->trneeDiet === null ? '' : $request->trneeDiet;
            $trainee->save();
            $train=$trainee;
        
       
         

        $train2 = new \App\Models\trainee_trainings;
        $train2->trneeFK=$train->trneePK;
        $train2->trainFK=$request->trainFK;
        $train2->referrer=$request->referrer === null ? '' : $request->referrer;
        
        $train2->save();
        // Will return a ModelNotFoundException if no user with that id
            try
            {
                $invoice = \App\Models\invgenerators::where('invYear', Carbon::now()->format('Y'))
                ->where('invCode', 'HL')
                ->where('invType', 'P')
                ->firstOrFail();
            }
            // catch(Exception $e) catch any exception
            catch(ModelNotFoundException $e)
            {
                $invoice = new \App\Models\invgenerators;
                $invoice->invYear= Carbon::now()->format('Y');
                $invoice->invLastNo=0;
                $invoice->invCode="HL";
                $invoice->invType="P";
                
                $invoice->save();
        
            }
        
        $train3 = \App\Models\trainings::where('trainPK', $request->trainFK)->firstOrFail();
        $discnt = \App\Models\discounts::where('trainFK', $request->trainFK)->whereRaw('(discntAuto = true or (discntCode=?))',[ $request->discntcode])->whereRaw('CAST(? AS DATE) between discntFrom and discntTo', [date('Y-m-d')])->orderBy('discntVal', 'DESC')->first();
        $crs = \App\Models\courses::where('coursePK', $train3->courseFK)->firstOrFail();
        $biller = \App\Models\billers::where('billerPK',1)->firstOrFail();
        

        $stdprice= (float) $crs->courseFee;

        if (!$discnt) {
             // Do stuff if it doesn't exist.
             $discntprice=$stdprice;
             $discnote="";
        }
        else{
            $discntprice=$stdprice-($stdprice*(float)$discnt->discntVal/100);
            $discnote="*Payment must be make before ".Carbon::createFromFormat('Y-m-d', $discnt->discntTo)->format('d M Y');
        }

        $invoice->invLastNo = $invoice->invLastNo+1;
        $invoice->save();

        $invNo=$invoice->invCode.$invoice->invLastNo.'/'.$invoice->invYear;
        
        $data = [
            'date'=> Carbon::now()->format('d/m/Y'),
            'discnote'=>$discnote,
            'from'=> $biller->billerName,
            'fromaddr'=> nl2br($biller->billerAddress),
            'fromtel'=> $biller->billerTel,
            'fromemail'=> $biller->billerEmail,
            'course'=> $crs->courseName,
            'invNo'=>$invNo,
        'to'=>$request->trneeCmpny,
        'toaddr'=>nl2br($request->trneeAddr),
        'tel'=>$request->trneePhNo,
        'trnee'=>$request->trneeName,
        'email'=>$request->trneeEmail,
        'price'=>$discntprice,
        'details'=>$train3->trainDetail,
        'paynote'=>$biller->billerNote
        ];

        // Mail::send('mail', $data, function($message)use ($request,$biller) {

        //     $message->to($request->trneeEmail, $request->trneeName)->subject
        //         ($biller->billerSubject);
        //     $message->from('training@holisticslab.my','Holisticslab Training Department');
        // });


        $inv = new \App\Models\invoices;
        $inv->invDate= Carbon::now();
        $inv->invNo=$invNo;
        $inv->invPromocode=$request->discntcode === null ? '' : $request->discntcode;
        $inv->invTrneeTrainFK=$train2->tnee_trainPK;
        
        $inv->save();


    }   
        return redirect()->back();  
        
    }
  
    function checkregisterapi($pk,$id=''){
        $id=str_replace(" ","",$id);
        $id=str_replace("-","",$id);
        $trainee =  \App\Models\trainees::where('trneeIcNo', $id)->first();
        $isexist=true;
        if (empty($trainee)) {
            $isexist=false;
        }
        else{
            $train2 =  \App\Models\trainee_trainings::where('trainFK', $pk)->where('trneeFK', $trainee->trneePK)->first();
        }

      
        $isexist=true;
        if (empty($trainee)) {
            $isexist=false;
        }

        $isregister=true;
        if (empty($train2)) {
            $isregister=false;
        }
        
        $response = (object)['isregister' => $isregister,'isexist' => $isexist];
        return response()->json($response)->header('Access-Control-Allow-Origin', '*')->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
  
    }
    function getuserapi($pk){
        $trainee =  \App\Models\trainees::where('trneeIcNo', $pk)->first();
        return response()->json($trainee)->header('Access-Control-Allow-Origin', '*')->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
  
    }
    function addtraineeapi(Request $request)
    {
        $trneeicno=str_replace(" ","",$request->trneeIcNo);
        $trneeicno=str_replace("-","",$trneeicno);
        $train =  \App\Models\trainees::where('trneeIcNo', $trneeicno)->first();
        
        $train3 = \App\Models\trainings::where('trainPK', $request->trainFK)->firstOrFail();
   
       
        if (empty($train)) {

            $trainee = new \App\Models\trainees;
            $trainee->trneeIcNo=$trneeicno;
            $trainee->trneeName=$request->trneeName;
            $trainee->trneeAddr=$request->trneeAddr;
            $trainee->trneeCmpny=$request->trneeCmpny;
            $trainee->trneeEmail=$request->trneeEmail;
            $trainee->trneePhNo=$request->trneePhNo;
            $trainee->trneePost=$request->trneePost;
            $trainee->trneeDiet=$request->trneeDiet === null ? '' : $request->trneeDiet;
            $trainee->save();
            $train=$trainee;
        }
       
        $needhrdf=$request->has('needhrdf') ? true : false;
        $train2 = new \App\Models\trainee_trainings;
        $train2->trneeFK=$train->trneePK;
        $train2->trainFK=$request->trainFK;
        $train2->referrer=$request->referrer === null ? '' : $request->referrer;
        $train2->isHRDF=$needhrdf;
        
        $train2->save();
        // Will return a ModelNotFoundException if no user with that id
            try
            {
                $invoice = \App\Models\invgenerators::where('invYear', Carbon::now()->format('Y'))
                ->where('invCode', 'HL')
                ->where('invType', 'P')
                ->firstOrFail();
            }
            // catch(Exception $e) catch any exception
            catch(ModelNotFoundException $e)
            {
                $invoice = new \App\Models\invgenerators;
                $invoice->invYear= Carbon::now()->format('Y');
                $invoice->invLastNo=0;
                $invoice->invCode="HL";
                $invoice->invType="P";
                
                $invoice->save();
        
            }
        
        $discnt = \App\Models\discounts::where('trainFK', $request->trainFK)->whereRaw('(discntAuto = true or (discntCode=?))', [$request->discntcode])->whereRaw('CAST(? AS DATE) between discntFrom and discntTo', [date('Y-m-d')])->orderBy('discntVal', 'DESC')->first();
        $crs = \App\Models\courses::where('coursePK', $train3->courseFK)->firstOrFail();
        $biller = \App\Models\billers::where('billerPK',1)->firstOrFail();
        

        $stdprice= (float) $crs->courseFee;

        if (!$discnt) {
             // Do stuff if it doesn't exist.
             $discntprice=$stdprice;
             $discntprice="RM ".round($discntprice);
             $discnote="";
        }
        else{
            $discntprice=$stdprice-($stdprice*(float)$discnt->discntVal/100);
            $discntprice="RM ".round($discntprice);
            $discnote="*Payment must be make before ".Carbon::createFromFormat('Y-m-d', $discnt->discntTo)->format('d M Y');
        }

        $invoice->invLastNo = $invoice->invLastNo+1;
        $invoice->save();

        $invNo=$invoice->invCode.$invoice->invLastNo.'/'.$invoice->invYear;
        $emailto=strtolower($request->trneeEmail);
        $data = [
            'date'=> Carbon::now()->format('d/m/Y'),
            'discnote'=>$discnote,
            'from'=> $biller->billerName,
            'fromaddr'=> nl2br($biller->billerAddress),
            'fromtel'=> $biller->billerTel,
            'fromemail'=> $biller->billerEmail,
            'course'=> $crs->courseName,
            'invNo'=>$invNo,
        'to'=>$request->trneeCmpny,
        'toaddr'=>nl2br($request->trneeAddr),
        'tel'=>$request->trneePhNo,
        'trnee'=>$request->trneeName,
        'email'=>$emailto,
        'price'=>$discntprice,
        'details'=>$train3->trainDetail,
        'paynote'=>$biller->billerNote
        ];


        $inv = new \App\Models\invoices;
        $inv->invDate= Carbon::now();
        $inv->invNo=$invNo;
        $inv->invPromocode=$request->discntcode === null ? '' : $request->discntcode;
        $inv->invTrneeTrainFK=$train2->tnee_trainPK;
        
        $inv->save();

        $pdf = PDF::loadView('attchInvoice', $data)->setPaper('a4', 'potrait'); 

        
      $attch = \App\Models\attachments::where('courseFK', $train3->courseFK)->where('attchType','HRDF')->get();
           
      $emailpic = \App\Models\attachments::where('courseFK', $train3->courseFK)->where('attchType','EMAIL')->first();

      if($emailpic!==null){
          $emailpic = url('/getimg/'.encrypt($emailpic->attchLink));
      }
      else{
        $emailpic = 'http://training.holisticslab.my/wp-content/uploads/2019/12/Mobile-Slider-2020-02.jpg';
      }
     
      $emailvar = [
     'course'=> $crs->courseName,
    'trnee'=>$request->trneeName,
    'email'=>$emailto,
    'emailpic'=>$emailpic
    ];
        Mail::send('thanksEmail', $emailvar, function($message)use ($request,$biller,$emailto,$pdf,$attch,$needhrdf) {

            $message->to($emailto, $request->trneeName)->subject
                ($biller->billerSubject);
            $message->from('training@holisticslab.my','Holisticslab Training Department')
            ->attachData($pdf->output(), "invoice.pdf");

        });

        // if($needhrdf){
        //     for ($i=0; $i < count($attch); $i++) {
        //         $contents = Storage::get($attch[$i]->attchLink);
        //         $ext = pathinfo($attch[$i]->attchLink, PATHINFO_EXTENSION);
        //         $message->attachData($contents,$attch[$i]->attchDesc.'.'.$ext);
        //     }}
        $response = (object)['ispass' => true, 'message' => "Successfull Register", 'reqtyp' => ""];
        return response()->json($response)->header('Access-Control-Allow-Origin', '*')->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
  
    
    }

    function addtraineeintapi(Request $request)
    {
        
        $trneeicno=str_replace(" ","",$request->trneeIcNo);
        $trneeicno=str_replace("-","",$trneeicno);
        $train =  \App\Models\trainees::where('trneeIcNo', $trneeicno)->first();

        if (empty($train)) {

            $trainee = new \App\Models\trainees;
            $trainee->trneeIcNo=$trneeicno;
            $trainee->trneeName=$request->trneeName;
            $trainee->trneeAddr=$request->trneeAddr;
            $trainee->trneeCmpny=$request->trneeCmpny;
            $trainee->trneeEmail=$request->trneeEmail;
            $trainee->trneePhNo=$request->trneePhNo;
            $trainee->trneePost=$request->trneePost;
            $trainee->trneeIsMalaysian=false;
            $trainee->trneeDiet=$request->trneeDiet === null ? '' : $request->trneeDiet;
            $trainee->save();
            $train=$trainee;
        }
       
            
        $train2 = new \App\Models\trainee_trainings;
        $train2->trneeFK=$train->trneePK;
        $train2->trainFK=$request->trainFK;
        $train2->referrer=$request->referrer === null ? '' : $request->referrer;
        
        $train2->save();
        // Will return a ModelNotFoundException if no user with that id
            try
            {
                $invoice = \App\Models\invgenerators::where('invYear', Carbon::now()->format('Y'))
                ->where('invCode', 'HL')
                ->where('invType', 'P')
                ->firstOrFail();
            }
            // catch(Exception $e) catch any exception
            catch(ModelNotFoundException $e)
            {
                $invoice = new \App\Models\invgenerators;
                $invoice->invYear= Carbon::now()->format('Y');
                $invoice->invLastNo=0;
                $invoice->invCode="HL";
                $invoice->invType="P";
                
                $invoice->save();
        
            }
        
        $train3 = \App\Models\trainings::where('trainPK', $request->trainFK)->firstOrFail();
        $discnt = \App\Models\discounts::where('trainFK', $request->trainFK)->whereRaw('(discntAuto = true or (discntCode=?))', [$request->discntcode])->whereRaw('CAST(? AS DATE) between discntFrom and discntTo', [date('Y-m-d')])->orderBy('discntVal', 'DESC')->first();
        $crs = \App\Models\courses::where('coursePK', $train3->courseFK)->firstOrFail();
        $biller = \App\Models\billers::where('billerPK',1)->firstOrFail();
        
        $stdprice= (float) $crs->courseFeeInt;

      //  if (!$discnt) {
             // Do stuff if it doesn't exist.
             $discntprice=$stdprice;
             $discntprice="USD $ ".round($discntprice);
            
            $discnote="";
        // }
        // else{
        //     $discntprice=$stdprice-($stdprice*(float)$discnt->discntVal/100);
        //     $discntprice="USD $ ".round($discntprice);
        //     $discnote="*Payment must be make before ".Carbon::createFromFormat('Y-m-d', $discnt->discntTo)->format('d M Y');
        // }

        $invoice->invLastNo = $invoice->invLastNo+1;
        $invoice->save();

        $invNo=$invoice->invCode.$invoice->invLastNo.'/'.$invoice->invYear;
        $emailto=strtolower($request->trneeEmail);
        $data = [
            'date'=> Carbon::now()->format('d/m/Y'),
            'discnote'=>$discnote,
            'from'=> $biller->billerName,
            'fromaddr'=> nl2br($biller->billerAddress),
            'fromtel'=> $biller->billerTel,
            'fromemail'=> $biller->billerEmail,
            'course'=> $crs->courseName,
            'invNo'=>$invNo,
        'to'=>$request->trneeCmpny,
        'toaddr'=>nl2br($request->trneeAddr),
        'tel'=>$request->trneePhNo,
        'trnee'=>$request->trneeName,
        'email'=>$emailto,
        'price'=>$discntprice,
        'details'=>$train3->trainDetail,
        'paynote'=>$biller->billerNote
        ];


        $inv = new \App\Models\invoices;
        $inv->invDate= Carbon::now();
        $inv->invNo=$invNo;
        $inv->invPromocode=$request->discntcode === null ? '' : $request->discntcode;
        $inv->invTrneeTrainFK=$train2->tnee_trainPK;
        
        $inv->save();

        $pdf = PDF::loadView('attchInvoice', $data)->setPaper('a4', 'potrait'); 

        
        Mail::send('thanksEmail', $data, function($message)use ($request,$biller,$emailto,$pdf) {

            $message->to($emailto, $request->trneeName)->subject
                ($biller->billerSubject);
            $message->from('training@holisticslab.my','Holisticslab Training Department')
            ->attachData($pdf->output(), "invoice.pdf");
        });
        

        $response = (object)['ispass' => true, 'message' => "Successfull Register", 'reqtyp' => ""];
        return response()->json($response)->header('Access-Control-Allow-Origin', '*')->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
  
    
    }
    function resendInvoice($id,$userid,$email=null){

        
        $id=decrypt($id);
        $userid=decrypt($userid);
        
        $biller = \App\Models\billers::where('billerPK',1)->firstOrFail();
        $invraw =  DB::select(DB::raw("
            select IFNULL(e.discntVal, '0') discntVal,e.discntTo,f.courseName,f.courseFee,f.courseFeeInt,d.invNo,d.invDate,a.*,c.trainDetail,b.isHRDF,c.courseFK from trainees a 
                inner join trainee_trainings b on a.trneePK=b.trneeFK 
                inner join trainings c on b.trainFK=c.trainPK
                inner join invoices d on b.tnee_trainPK=d.invTrneeTrainFK
                left join discounts e on c.trainPK=e.trainFK and CAST(d.invDate AS DATE) between e.discntFrom and e.discntTo and (e.discntAuto=true or e.discntCode=d.invPromocode)
                inner join courses f on c.courseFK=f.coursePK
                where a.trneePK=:userid and c.trainPK=:id order by discntVal desc
                limit 1") , array(
            'userid' => $userid,'id' => $id
        ));
        $inv=$invraw[0];

        if (!$inv->discntVal || $inv->discntVal=== "0" || $inv->discntVal=== "0") {
            // Do stuff if it doesn't exist.
            if($inv->trneeIsMalaysian==true){
                $discntprice=$inv->courseFee;
                $discntprice="RM ".round($discntprice);
            }
            else{
                $discntprice=$inv->courseFeeInt;
                $discntprice="USD $ ".round($discntprice);
            }
            $discnote="";
       }
       else{
           
           if($inv->trneeIsMalaysian==true){
            $discntprice=$inv->courseFee-($inv->courseFee*(float)$inv->discntVal/100);
            $discntprice="RM ".round($discntprice);
            }
        else{
            $discntprice=$inv->courseFeeInt ; //-($inv->courseFee*(float)$inv->discntVal/100);
            $discntprice="USD $ ".round($discntprice);
        }
           $discnote="*Payment must be make before ".Carbon::createFromFormat('Y-m-d', $inv->discntTo)->format('d M Y');
       }

       $emailto=strtolower($inv->trneeEmail);
        $data = [
            'date'=> Carbon::createFromFormat('Y-m-d H:i:s', $inv->invDate, 'UTC')->setTimezone('Asia/Kuala_Lumpur')->format('d/m/Y'),
            'discnote'=>$discnote,
            'from'=> $biller->billerName,
            'fromaddr'=> nl2br($biller->billerAddress),
            'fromtel'=> $biller->billerTel,
            'fromemail'=> $biller->billerEmail,
            'course'=> $inv->courseName,
            'invNo'=>$inv->invNo,
        'to'=>$inv->trneeCmpny,
        'toaddr'=>nl2br($inv->trneeAddr),
        'tel'=>$inv->trneePhNo,
        'trnee'=>$inv->trneeName,
        'email'=>$emailto,
        'price'=>$discntprice,
        'details'=>$inv->trainDetail,
        'paynote'=>$biller->billerNote
        ];

        
        $pdf = PDF::loadView('attchInvoice', $data)->setPaper('a4', 'potrait'); 

        $attch = \App\Models\attachments::where('courseFK', $inv->courseFK)->where('attchType','HRDF')->get();
           
      $emailpic = \App\Models\attachments::where('courseFK', $inv->courseFK)->where('attchType','EMAIL')->first();

      if($emailpic!==null){
          $emailpic = url('/getimg/'.encrypt($emailpic->attchLink));
      }
      else{
        $emailpic = 'http://training.holisticslab.my/wp-content/uploads/2019/12/Mobile-Slider-2020-02.jpg';
      }
     
      $emailvar = [
     'course'=> $inv->courseName,
    'trnee'=>$inv->trneeName,
    'email'=>$emailto,
    'emailpic'=>$emailpic
    ];
    $needhrdf=$inv->isHRDF;
        Mail::send('thanksEmail', $emailvar, function($message)use ($inv,$biller,$email,$emailto,$pdf,$attch,$needhrdf) {

            $message->to($email === null ? $emailto : $email, $inv->trneeName)->subject
                ($biller->billerSubject);
            $message->from('training@holisticslab.my','Holisticslab Training Department')
            ->attachData($pdf->output(), "invoice.pdf");

        });
        
        // if($needhrdf){
        //     for ($i=0; $i < count($attch); $i++) {
        //         $contents = Storage::get($attch[$i]->attchLink);
        //         $ext = pathinfo($attch[$i]->attchLink, PATHINFO_EXTENSION);
        //         $message->attachData($contents,$attch[$i]->attchDesc.'.'.$ext);
        //     }}
        return redirect()->back();
    }
    function getprofomainvoice($id,$userid){
        
        $id=decrypt($id);
        $userid=decrypt($userid);
        
        $biller = \App\Models\billers::where('billerPK',1)->firstOrFail();
        $invraw =  DB::select(DB::raw("
            select IFNULL(e.discntVal, '0') discntVal,e.discntTo,f.courseName,f.courseFee,f.courseFeeInt,d.invNo,d.invDate,a.*,c.trainDetail from trainees a 
                inner join trainee_trainings b on a.trneePK=b.trneeFK 
                inner join trainings c on b.trainFK=c.trainPK
                inner join invoices d on b.tnee_trainPK=d.invTrneeTrainFK and d.invType='P'
                left join discounts e on c.trainPK=e.trainFK and CAST(d.invDate AS DATE) between e.discntFrom and e.discntTo and (e.discntAuto=true or e.discntCode=d.invPromocode)
                inner join courses f on c.courseFK=f.coursePK
                where a.trneePK=:userid and c.trainPK=:id order by discntVal desc
                limit 1") , array(
            'userid' => $userid,'id' => $id
        ));
        $inv=$invraw[0];

      
       if (!$inv->discntVal || $inv->discntVal=== "0" || $inv->discntVal=== "0" ) {
        // Do stuff if it doesn't exist.
        if($inv->trneeIsMalaysian==true){
            $discntprice=$inv->courseFee;
            $discntprice="RM ".round($discntprice);
        }
        else{
            $discntprice=$inv->courseFeeInt;
            $discntprice="USD $ ".round($discntprice);
        }
        $discnote="";
   }
   else{
       
       if($inv->trneeIsMalaysian==true){
        $discntprice=$inv->courseFee-($inv->courseFee*(float)$inv->discntVal/100);
        $discntprice="RM ".round($discntprice);
        }
    else{
        $discntprice=$inv->courseFeeInt;//-($inv->courseFee*(float)$inv->discntVal/100);
        $discntprice="USD $ ".round($discntprice);
    }
       $discnote="*Payment must be make before ".Carbon::createFromFormat('Y-m-d', $inv->discntTo)->format('d M Y');
   }
       
       $emailto=strtolower($inv->trneeEmail);
        $data = [
            'date'=> Carbon::createFromFormat('Y-m-d H:i:s', $inv->invDate, 'UTC')->setTimezone('Asia/Kuala_Lumpur')->format('d/m/Y'),
            'discnote'=>$discnote,
            'from'=> $biller->billerName,
            'fromaddr'=> nl2br($biller->billerAddress),
            'fromtel'=> $biller->billerTel,
            'fromemail'=> $biller->billerEmail,
            'course'=> $inv->courseName,
            'invNo'=>$inv->invNo,
        'to'=>$inv->trneeCmpny,
        'toaddr'=>nl2br($inv->trneeAddr),
        'tel'=>$inv->trneePhNo,
        'trnee'=>$inv->trneeName,
        'email'=>$emailto,
        'price'=>$discntprice,
        'details'=>$inv->trainDetail,
        'paynote'=>$biller->billerNote
        ];

        return view('mail',$data);
    }
    
    function getinvoice($id,$userid){
        
        $id=decrypt($id);
        $userid=decrypt($userid);
        
        $biller = \App\Models\billers::where('billerPK',1)->firstOrFail();
        $invraw =  DB::select(DB::raw("
            select IFNULL(e.discntVal, '0') discntVal,e.discntTo,f.courseName,f.courseFee,f.courseFeeInt,d.invNo,d.invDate,a.*,c.trainDetail from trainees a 
                inner join trainee_trainings b on a.trneePK=b.trneeFK 
                inner join trainings c on b.trainFK=c.trainPK
                inner join invoices d on b.tnee_trainPK=d.invTrneeTrainFK and d.invType='I'
                left join discounts e on c.trainPK=e.trainFK and CAST(d.invDate AS DATE) between e.discntFrom and e.discntTo and (e.discntAuto=true or e.discntCode=d.invPromocode)
                inner join courses f on c.courseFK=f.coursePK
                where a.trneePK=:userid and c.trainPK=:id order by discntVal desc
                limit 1") , array(
            'userid' => $userid,'id' => $id
        ));
        $inv=$invraw[0];

      
       if (!$inv->discntVal || $inv->discntVal=== "0" || $inv->discntVal=== "0") {
        // Do stuff if it doesn't exist.
        if($inv->trneeIsMalaysian==true){
            $discntprice=$inv->courseFee;
            $discntprice="RM ".round($discntprice);
        }
        else{
            $discntprice=$inv->courseFeeInt;
            $discntprice="USD $ ".round($discntprice);
        }
        $discnote="";
   }
   else{
       
       if($inv->trneeIsMalaysian==true){
        $discntprice=$inv->courseFee-($inv->courseFee*(float)$inv->discntVal/100);
        $discntprice="RM ".round($discntprice);
        }
    else{
        $discntprice=$inv->courseFeeInt;//-($inv->courseFee*(float)$inv->discntVal/100);
        $discntprice="USD $ ".round($discntprice);
    }
       $discnote="*Payment must be make before ".Carbon::createFromFormat('Y-m-d', $inv->discntTo)->format('d M Y');
   }
       
       $emailto=strtolower($inv->trneeEmail);
        $data = [
            'date'=> Carbon::createFromFormat('Y-m-d H:i:s', $inv->invDate, 'UTC')->setTimezone('Asia/Kuala_Lumpur')->format('d/m/Y'),
            'discnote'=>$discnote,
            'from'=> $biller->billerName,
            'fromaddr'=> nl2br($biller->billerAddress),
            'fromtel'=> $biller->billerTel,
            'fromemail'=> $biller->billerEmail,
            'course'=> $inv->courseName,
            'invNo'=>$inv->invNo,
        'to'=>$inv->trneeCmpny,
        'toaddr'=>nl2br($inv->trneeAddr),
        'tel'=>$inv->trneePhNo,
        'trnee'=>$inv->trneeName,
        'email'=>$emailto,
        'price'=>$discntprice,
        'details'=>$inv->trainDetail,
        'paynote'=>$biller->billerNote
        ];

        return view('invoicemail',$data);
    }
    function createinvoice(Request $request){
        
        $id=($request->trainPK);
        $userid=($request->trneePK);
        

        $realinvoice = \App\Models\invoices::where('invTrneeTrainFK',$request->tnee_trainPK)
        ->where('invType','I')
        ->first();

        if($realinvoice){
        }
        else{
            $profoma = \App\Models\invoices::where('invTrneeTrainFK',$request->tnee_trainPK)
            ->where('invType','P')
            ->first();

            $realinvoice = new \App\Models\invoices;
            $realinvoice->invDate=$request->invDate;
            $realinvoice->invNo=$request->invNo;
            $realinvoice->invPromocode=$profoma->invPromocode;
            $realinvoice->invTrneeTrainFK=$profoma->invTrneeTrainFK;
            $realinvoice->invType="I";
            $realinvoice->save();
        }

        return redirect('getinvoice/'.encrypt($request->trainPK).'/'.encrypt($request->trneePK));
    }
    function createreciept(Request $request){
        
        $id=($request->trainPK);
        $userid=($request->trneePK);
        $invPK=($request->invPK);
        

        $invoice = \App\Models\invoices::where('invPK',$invPK)
        ->first();

        if($invoice){
            $reciepts =new \App\Models\reciepts;
            $reciepts->recptNo=$request->invNo;
            $reciepts->recptDate=$request->invDate;
            $reciepts->recptType=$request->paymentType;
            $reciepts->recptChNo=$request->chqNo === null ? '' : $request->chqNo;
            $reciepts->invFK=$request->invPK;
            $reciepts->save();


            return redirect('getreciept/'.encrypt($reciepts->recptPK));
        }
        else{
            
        return redirect()->back()->with('fail_status', 'Cannot create reciept without invoice');
        }

    }

    function getreciept($id){
        
        $id=decrypt($id);

        $reciepts=\App\Models\reciepts::join('invoices as d', 'd.invPK','=', 'reciepts.invFK')
        ->join('trainee_trainings as b', 'b.tnee_trainPK','=', 'd.invTrneeTrainFK')
        ->join('trainings as c', 'b.trainFK','=', 'c.trainPK')
        ->join('trainees as a', 'b.trneeFK','=', 'a.trneePK')
        ->join('courses as f', 'c.courseFK','=', 'f.coursePK')
        ->leftJoin('discounts  as e', function($join){
            $join->on('e.trainFK', '=', 'c.trainPK')
            ->whereRaw('CAST(d.invDate AS DATE) between e.discntFrom and e.discntTo ')
            ->whereRaw('(e.discntAuto=true or e.discntCode=d.invPromocode)');
        })
        ->where('recptPK',$id)
        ->selectRaw('IFNULL(e.discntVal, "0") as discntVal,e.discntTo,f.courseName,f.courseFee,f.courseFeeInt,d.invNo,d.invDate,a.*,c.trainDetail, reciepts.*')
        ->orderBy('discntVal', 'DESC')->first();

//left join discounts e on c.trainPK=e.trainFK and d.invDate between e.discntFrom and e.discntTo and (e.discntAuto=true or e.discntCode=d.invPromocode)
                
        $biller = \App\Models\billers::where('billerPK',1)->firstOrFail();
        // $invraw =  DB::select(DB::raw("
        //     select IFNULL(e.discntVal, 0) discntVal,e.discntTo,f.courseName,f.courseFee,f.courseFeeInt,d.invNo,d.invDate,a.*,c.trainDetail from trainees a 
        //         inner join trainee_trainings b on a.trneePK=b.trneeFK 
        //         inner join trainings c on b.trainFK=c.trainPK
        //         inner join invoices d on b.tnee_trainPK=d.invTrneeTrainFK and d.invType='I'
        //         left join discounts e on c.trainPK=e.trainFK and d.invDate between e.discntFrom and e.discntTo and (e.discntAuto=true or e.discntCode=d.invPromocode)
        //         inner join courses f on c.courseFK=f.coursePK
        //         where a.trneePK=:userid and c.trainPK=:id order by discntVal desc
        //         limit 1") , array(
        //     'userid' => $userid,'id' => $id
        // ));
        
        $inv=$reciepts;

      
       if (!$inv->discntVal || $inv->discntVal=== "0" || $inv->discntVal=== "0") {
        // Do stuff if it doesn't exist.
        if($inv->trneeIsMalaysian==true){
            $discntprice=$inv->courseFee;
            $discntprice="RM ".number_format((float)round($discntprice), 2);
            $pricetext="RINGGIT MALAYSIA ";
            $pricenumber=$inv->courseFee;
        }
        else{
            $discntprice=$inv->courseFeeInt;
            $discntprice="USD $ ".number_format((float)round($discntprice), 2);
            $pricetext="UNITED STATES DOLLAR ";
            $pricenumber=$inv->courseFeeInt;
        }
        $discnote="";
   }
   else{
       
       if($inv->trneeIsMalaysian==true){
        $discntprice=$inv->courseFee-($inv->courseFee*(float)$inv->discntVal/100);
        $pricetext="RINGGIT MALAYSIA ";
        $pricenumber=$discntprice;
        $discntprice="RM ".number_format((float)round($discntprice), 2);
        }
    else{
        $discntprice=$inv->courseFeeInt;//-($inv->courseFee*(float)$inv->discntVal/100);
        $pricetext="UNITED STATES DOLLAR ";
        $pricenumber=$discntprice;
        $discntprice="USD $ ".number_format((float)round($discntprice), 2);
    }
       $discnote="*Payment must be make before ".Carbon::createFromFormat('Y-m-d', $inv->discntTo)->format('d/m/Y');
   }
       
   
       $emailto=strtolower($inv->trneeEmail);
        $data = [
            'date'=> Carbon::createFromFormat('Y-m-d', $inv->recptDate, 'UTC')->setTimezone('Asia/Kuala_Lumpur')->format('d/m/Y'),
            'invdate'=> Carbon::createFromFormat('Y-m-d H:i:s', $inv->invDate, 'UTC')->setTimezone('Asia/Kuala_Lumpur')->format('d/m/Y'),
            'discnote'=>$discnote,
            'from'=> $biller->billerName,
            'fromaddr'=> nl2br($biller->billerAddress),
            'fromtel'=> $biller->billerTel,
            'fromemail'=> $biller->billerEmail,
            'course'=> $inv->courseName,
            'invNo'=>$inv->invNo,
            'recptNo'=>$inv->recptNo,
            'pricetext'=>$pricetext,
            'pricenumber'=>$pricenumber,
            'type'=>$inv->recptType,
            'chno'=>$inv->recptChNo,
        'to'=>$inv->trneeCmpny,
        'toaddr'=>nl2br($inv->trneeAddr),
        'tel'=>$inv->trneePhNo,
        'trnee'=>$inv->trneeName,
        'email'=>$emailto,
        'price'=>$discntprice,
        'details'=>$inv->trainDetail,
        'paynote'=>$biller->billerNote
        ];

        return view('recieptmail',$data);
    }
    
    function punch(Request $request)
	{
             
        $diff=date_diff(date_create(),date_create());
        $response = (object)['status' => '', 'message' => '' ];
        
        $train = \App\Models\trainee_trainings::leftJoin('trainees', 'trainees.trneePK','=', 'trneeFK')->where('trainFK', $request->trainFK)->where('trneeIcNo', $request->icno)->first();
           
           if(count($train)>0){
               
        $prevattnd =\App\Models\trainingattnds::where('tnee_trainFK', $train->tnee_trainPK)->orderBy('tattndPK', 'DESC')->first();
           
           if($prevattnd){
                     $diff=date_diff(date_create($prevattnd->tattndTimestamp,timezone_open("Asia/Kuala_Lumpur")),date_create());
           }
           
          // dd([date_create($prevattnd->tattndTimestamp,timezone_open("Asia/Kuala_Lumpur")),date_create(),$diff->format("%d"),$diff->format("%s")]);

         if ($diff->format("%d")>0||$diff->format("%h")>0 || $diff->format("%s")==0){
             
            $attnd = new \App\Models\trainingattnds;
        $attnd->tnee_trainFK=$train->tnee_trainPK;
        $attnd->save();
             
              $response->status ="PASS";
            $response->message ="Welcome ".$train->trneeName."\nYou are successfully punch ";
         }
         else {
            $response->status ="FAILED";
             if($diff->format("%i")>0){
                 $response->message ="Hello ".$train->trneeName.",\nYou are  just punch ".$diff->format("%i Minutes")." ago";}
             else{ $response->message ="You already punch in to the system";}
           
         }
               }
           else{
            $response->status ="FAILED";
               $response->message ="user not found";
           }
    
        
          
         
        
        return response()->json($response);
        
    }
    
    function getAttnd(Request $request, $id)
		{
        
        
            $id=decrypt($id);
        
        $train = \App\Models\trainings::where('trainPK', $id)->firstOrFail();
        $train->trainDtStart=date('Y-m-d\TH:i',strtotime( $train->trainDtStart));
        $train->trainDtEnd=date('Y-m-d\TH:i',strtotime( "+1day -1second",strtotime($train->trainDtEnd)));
        $data=[];
          $currdate=strtotime(date('Y-m-d'));
        if (isset($request->currdate))
        {
            
            $currdate=strtotime($request->currdate);
           
          
        }
        if ($currdate > strtotime( "+1day -1second",strtotime($train->trainDtEnd))) {
            $currdate=strtotime($train->trainDtStart);
        }
        
       
        
        $trainee = \App\Models\trainees::leftJoin('trainee_trainings', 'trneePK','=', 'trainee_trainings.trneeFK')->where('trainFK', $id)->get();
        
        
       
             foreach($trainee as $a)
             {
                 $response = (object)['empname' => '', 'in' => '', 'out' => '', 'icno' => '','id'=>$a->tnee_trainPK ];
                 
                 $in =  \App\Models\trainingattnds::where('tnee_trainFK', $a->tnee_trainPK)->where('tattndTimestamp', '>=', date('Y-m-d',$currdate).' 00:00:00')->where('tattndTimestamp', '<=', date('Y-m-d',strtotime("+1day", $currdate)).' 00:00:00')->orderBy('tattndTimestamp', 'ASC')->first();
                 
                 $out =  \App\Models\trainingattnds::where('tnee_trainFK', $a->tnee_trainPK)->where('tattndTimestamp', '>=', date('Y-m-d',$currdate).' 00:00:00')->where('tattndTimestamp', '<=', date('Y-m-d',strtotime("+1day", $currdate)).' 00:00:00')->orderBy('tattndTimestamp', 'DESC')->first();
                 
                 $response->empname = $a->trneeName;
                 
                 $diff=date_diff(date_create(),date_create());
                 
                 if($in){
                 if(strtotime(date('H:i:s',strtotime($in->tattndTimestamp.' UTC')))<strtotime("13:00:00")){
                    $response->in = date('h:i A',strtotime($in->tattndTimestamp.' UTC'));
                    $response->inPK = $in->tattndPK;
                    $response->inData = date('Y-m-d\TH:i',strtotime( $in->tattndTimestamp.' UTC'));
                 }
                 else{
                    $response->in ='';
                 }}
                 if($out){
                 if(strtotime(date('H:i:s',strtotime($out->tattndTimestamp.' UTC')))>strtotime("13:00:00")){
                    $response->out = date('h:i A',strtotime($out->tattndTimestamp.' UTC'));
                    $response->outPK = $out->tattndPK;
                    $response->outData = date('Y-m-d\TH:i', strtotime($out->tattndTimestamp.' UTC'));
                 }
                 else{
                    $response->out ='';
                 }}
					//$response->in = $in->tattndTimestamp;
					//$response->out = $out->tattndTimestamp;
					$response->icno = $a->trneeIcNo;
                 
                 array_push($data, $response);
             }
        
        return view('attendance', ['train' => $train ,'attnd' => $data,'currdate'=>$currdate]);
    }
   
    function getindattnd(Request $request, $id, $ic)
    {
    
    
        $id=decrypt($id);
    
    $train = \App\Models\trainings::where('trainPK', $id)->firstOrFail();
    $data=[];
    
    
    $trainee = \App\Models\trainees::leftJoin('trainee_trainings', 'trneePK','=', 'trainee_trainings.trneeFK')->where('trainFK', $id)->where('trneeIcNo', $ic)->first();
    
    $attnd =  DB::select(DB::raw("SELECT tnee_trainFK,date_format(tattndTimestamp,'%Y-%m-%d')as attnd FROM `trainingattnds` where tnee_trainFK=:tneetrainfk GROUP by tnee_trainFK,attnd order by attnd asc") , array(
        'tneetrainfk' => $trainee->tnee_trainPK
    ));
            
        $newdata=[];
             
        foreach($attnd as $a)
        {
                 $response = (object)['dates'=>'','in' => '', 'out' => '','dayindex'=>'','datediff'=>''];
                  
                 $response->dayindex=date("N",strtotime($a->attnd));
                 $response->dates=$a->attnd;
                 
                 $in =  \App\Models\trainingattnds::where('tnee_trainFK', $trainee->tnee_trainPK)->where('tattndTimestamp', '>=', date('Y-m-d',strtotime($a->attnd)).' 00:00:00')->where('tattndTimestamp', '<=', date('Y-m-d',strtotime("+1day", strtotime($a->attnd))).' 00:00:00')->orderBy('tattndPK', 'ASC')->first();
                 
                 $out =  \App\Models\trainingattnds::where('tnee_trainFK', $trainee->tnee_trainPK)->where('tattndTimestamp', '>=', date('Y-m-d',strtotime($a->attnd)).' 00:00:00')->where('tattndTimestamp', '<=', date('Y-m-d',strtotime("+1day", strtotime($a->attnd))).' 00:00:00')->orderBy('tattndPK', 'DESC')->first();
                 
                 $diff=date_diff(date_create(),date_create());
                 
                 if ($out) {$response->out = date('h:i:s A',strtotime($out->tattndTimestamp));}
                 if ($in) {$response->in = date('h:i:s A',strtotime($in->tattndTimestamp));
                           
                 if($response->in===$response->out ){
                     $response->out ='';
                     $diff=date_diff(date_create($in->tattndTimestamp),date_create());}
                 else{
                     $diff=date_diff(date_create($in->tattndTimestamp),date_create($out->tattndTimestamp));
                 }
                 
                 if (!(date('Y-m-d',strtotime($out->tattndTimestamp))!=date('Y-m-d') && $response->out =='')){
                 if ($diff->format("%h")>0){$response->datediff=$diff->format("%h Hours and %i Minutes");}
                 else if($diff->format("%i")>0){$response->datediff=$diff->format("%i Minutes");}
                 }
                          }
					//$response->in = $in->att_timestamp;
					//$response->out = $out->att_timestamp;
                 array_push($newdata, $response);
             }
        
        return view('trneeattnd', ['attnd' => $newdata,'trnee'=>$trainee,'train'=>$train]);
    }
function gettrainadminlist() {
    
    $data=[];
    

    $trainyear = DB::select(DB::raw("SELECT distinct YEAR(trainDtStart) as year FROM trainings order by trainDtStart asc"));

    foreach($trainyear as $a)
    {
        $trainlist = (object)['year' => $a->year ];
        
        if(auth()->user()->isSuperAdmin){
            $train =   DB::select(DB::raw("
            SELECT COUNT(c.trneePK) trneecnt,a.* FROM trainings a left join trainee_trainings b on a.trainPK=b.trainFK left join trainees c on b.trneeFK=c.trneePK where year(trainDtStart)=:year
            group by a.trainName,a.trainPlace,a.trainDtStart,a.isOfficeUse,a.trainDtEnd,a.trainPK,a.courseFK,a.trainDetail,a.trainAddress,a.trainState,a.isFulltime,a.trainDiscnt,a.istest,a.trainDateExclude,a.trainSessionCnt order by trainDtStart asc"
            ), array(
                'year' => $a->year
            ));
    }
        else{
            $train =   DB::select(DB::raw("
            SELECT COUNT(c.trneePK) trneecnt,a.* FROM trainings a left join trainee_trainings b on a.trainPK=b.trainFK left join trainees c on b.trneeFK=c.trneePK where year(trainDtStart)=:year and a.isOfficeUse=:admin
            group by a.trainName,a.trainPlace,a.trainDtStart,a.trainDtEnd,a.trainPK,a.isOfficeUse,a.courseFK,a.trainDetail,a.trainAddress,a.trainState,a.isFulltime,a.trainDiscnt,a.istest,a.trainDateExclude,a.trainSessionCnt order by trainDtStart asc"
            ), array(
                'year' => $a->year,
                'admin' => 0
            ));
        }
   

        $trainlist->train = $train;
        
        array_push($data, $trainlist);
    }
    

    $post =   DB::select(DB::raw("
    SELECT COUNT(c.trneePK) trneecnt,a.* FROM trainings a left join trainee_trainings b on a.trainPK=b.trainFK left join trainees c on b.trneeFK=c.trneePK
    group by a.trainName,a.trainPlace,a.trainDtStart,a.isOfficeUse,a.trainDtEnd,a.trainPK,a.courseFK,a.trainDetail,a.trainAddress,a.trainState,a.isFulltime,a.trainDiscnt,a.istest,a.trainDateExclude,a.trainSessionCnt order by trainDtStart asc") );
    $state = \App\Models\states::all();
    $courses =  \App\Models\courses::all();
   
      return view('home', ['train' => $post ,'state' => $state ,'course' => $courses,'state2' => $state ,'course2' => $courses,'data'=>$data ,'year'=>$trainyear]);
}
    function gettrainlist(){
        
        $response = (object)['status' => '', 'message' => '' ];
        
                      $post =  \App\Models\trainings::where('trainDtStart', '<=', DB::raw('DATE(NOW())'))->where('trainDtEnd', '>=',DB::raw('DATE(NOW())'))->get();
        return response()->json($post);
    }
     function gettrainlistall(){
        
        $response = (object)['status' => '', 'message' => '' ];
        
                      $post =  \App\Models\trainings::get();
        return response()->json($post);
    }
      function gettrainattnd(Request $request, $id){
          
        $trainee = \App\Models\trainees::leftJoin('trainee_trainings', 'trneePK','=', 'trainee_trainings.trneeFK')->where('trainFK', $id)->get();
          
          $data=[];
            
          $currdate=strtotime(date('Y-m-d'));
        if (isset($request->currdate))
        {
            
            $currdate=strtotime($request->currdate);
            $now = time();
            if ($currdate > $now) {
             $currdate=strtotime(date('Y-m-d'));
            }
        }
       
             foreach($trainee as $a)
             {
                 $response = (object)['name' => '', 'icno' => '', 'attnd' => '' ];
                 
                 $attnd =  \App\Models\trainingattnds::where('tnee_trainFK', $a->tnee_trainPK)->where('tattndTimestamp', '>=', date('Y-m-d',$currdate).' 00:00:00')->where('tattndTimestamp', '<=', date('Y-m-d',strtotime("+1day", $currdate)).' 00:00:00')->orderBy('tattndPK', 'ASC')->get();
                 
                 $response->name = $a->trneeName;
					$response->icno = $a->trneeIcNo;
					$response->attnd = $attnd;
                 
                 array_push($data, $response);
             }
          
        return response()->json($data);
    }

    function getimg($dir){
        $dir=decrypt($dir);
        $file = Storage::get($dir);
        $type =  Storage::mimeType($dir);

       
        return response()->make($file, 200)->header("Content-Type", $type);
    }
 
	}

