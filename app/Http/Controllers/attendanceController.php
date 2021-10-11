<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use DateTime;
use DB;

class attendanceController extends Controller
{
    //

  
    public function getList($ic)
    {
 
        $user=  \App\Models\trainees::where('trneeIcNo',$ic)->select('trneeName','trneeIcNo','trneePK')->first();
        $post =  \App\Models\trainings::leftJoin('trainee_trainings', 'trainPK','=', 'trainee_trainings.trainFK')
        ->leftJoin('courses', 'courseFK','=', 'courses.coursePK')
        ->leftJoin('trainees', 'trainees.trneePK','=', 'trainee_trainings.trneeFK')
        ->join('certificates', 'certificates.courseFK','=', 'courses.coursePK')
        ->where('trainees.trneeIcNo',$ic)
        ->where('paymentStatus','T')
        ->select('courseName','courseDesc','trainDtEnd','trainDtStart','trainName','certLink','certName')
        ->get();

        foreach($post as $a)
        {
                $a->certLink=url('hpb.getfile/'.encrypt($a->certLink));
        }

        $user->certs=$post;
        return response()->json($user);
        
    }

    function getfile($dir){
        $dir=decrypt($dir);
        $file = Storage::get($dir);
        
        if (!$file) {
            abort(404);
        }
        $type =  Storage::mimeType($dir);
    
        return response()->make($file, 200)->header("Content-Type", $type);
    }

    function getindattnd($id,$hrdf=false)
    {
    
    
        $id=decrypt($id);
    
    $data=[];
    
    
    $trainee = \App\Models\trainees::leftJoin('trainee_trainings', 'trneePK','=', 'trainee_trainings.trneeFK')->where('tnee_trainPK', $id)->first();
    
    $train = \App\Models\trainings::leftJoin('courses', 'coursePK','=', 'trainings.courseFK')->where('trainPK', $trainee->trainFK)->firstOrFail();
    $train->trainDtStart=date('Y-m-d\TH:i:s',strtotime( $train->trainDtStart));
    $train->trainDtEnd=date('Y-m-d\TH:i:s',strtotime( "+1day -1second",strtotime($train->trainDtEnd)));
    $attnd =  DB::select(DB::raw("SELECT tnee_trainFK,date_format(tattndTimestamp,'%Y-%m-%d')as attnd FROM `trainingattnds` where tnee_trainFK=:tneetrainfk GROUP by tnee_trainFK,attnd order by attnd asc") , array(
        'tneetrainfk' => $id
    ));
            
        $newdata=[];
             
        foreach($attnd as $a)
        {
                 $response = (object)['dates'=>'','in' => '', 'out' => '','dayindex'=>'','inPK' => null, 'outPK' => null,'inData' => null, 'outData' => null];
                  
                 $response->dayindex=date("N",strtotime($a->attnd));
                 $response->dates=date('d/m/Y',strtotime( $a->attnd));
                 
                 $in =  \App\Models\trainingattnds::where('tnee_trainFK', $trainee->tnee_trainPK)->where('tattndTimestamp', '>=', date('Y-m-d',strtotime($a->attnd)).' 00:00:00')->where('tattndTimestamp', '<=', date('Y-m-d',strtotime("+1day", strtotime($a->attnd))).' 00:00:00')->orderBy('tattndTimestamp', 'ASC')->first();
                 
                 $out =  \App\Models\trainingattnds::where('tnee_trainFK', $trainee->tnee_trainPK)->where('tattndTimestamp', '>=', date('Y-m-d',strtotime($a->attnd)).' 00:00:00')->where('tattndTimestamp', '<=', date('Y-m-d',strtotime("+1day", strtotime($a->attnd))).' 00:00:00')->orderBy('tattndTimestamp', 'DESC')->first();
                 
                 if($in){
                    if( strtotime(date('H:i:s',strtotime($in->tattndTimestamp.' UTC')))<strtotime("13:00:00")){
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
                 
					//$response->in = $in->att_timestamp;
					//$response->out = $out->att_timestamp;
                 array_push($newdata, $response);
             }
        
        return view('trneeattnd', ['attnd' => $newdata,'trnee'=>$trainee,'train'=>$train,'hrdf'=>$hrdf]);
    }

    function addattnd(Request $request)
	{
        $date = new DateTime("@".strtotime($request->attenddate)); 
             if($request->pk)
            {
                $attnd = \App\Models\trainingattnds::where('tattndPK',$request->pk)->first();
            }
            else
            {$attnd = new \App\Models\trainingattnds;}
            $attnd->tnee_trainFK= $request->id;
            
          //  dd(strtotime($request->attenddate));
            $attnd->tattndTimestamp=$date->format('Y-m-d H:i:s') ;
        $attnd->save();
             
      
    
           return redirect()->back();
        
    }

    function checkin(Request $request)
	{
           $attnd = new \App\Models\trainingattnds;
            $attnd->tnee_trainFK= $request->id;
            $attnd->save();
             
      
    
           return redirect()->back();
        
    }
    function migrate($id)
	{
        // \App\Models\training_attends::truncate();
        
        // $exist = \App\Models\training_attends::pluck('trainFK')->toArray();
        $newdata=[];
        $today = Carbon::today();
        $a = \App\Models\trainings::where('trainPK', $id)->first();
        // dd($trn);
        // foreach($trn as $a)
        // {
            $training=(object)["trainPK"=>$a->trainPK,"trainingAttendance"=>array()];
            // $trnee = \App\Models\trainees::leftJoin('trainee_trainings', 'trneePK','=', 'trainee_trainings.trneeFK')
            // ->leftJoin('invoices', function($join){
            //     $join->on('invoices.invTrneeTrainFK', '=', 'trainee_trainings.tnee_trainPK')
            //     ->where('invoices.invType', 'P');
            // })
            // ->leftJoin('invoices as i', function($join){
            //     $join->on('i.invTrneeTrainFK', '=', 'trainee_trainings.tnee_trainPK')
            //     ->where('i.invType','I');
            // })
            // ->leftJoin('reciepts', 'reciepts.invFK','=', 'i.invPK')
            // ->selectRaw("trainees.*, trainee_trainings.*, invoices.*, i.invPK as realinvoicePK, reciepts.* ")
            // ->where('trainFK', $id)
            // ->where('trainee_trainings.joinStatus',"T")
            // ->get();
            $trnee = \App\Models\trainee_trainings::leftJoin('trainees','trneePK','trneeFK')
            ->where('trainFK',$id)
            ->where('joinStatus',"T")
            ->get();
            // dd($trnee);
            $rowdata=[];
            foreach($trnee as $b)
            {
                $trrneeatt=(object)["userPK"=>$b->trneeFK,"trneeName"=>ucwords(strtolower($b->trneeName)),"attendance"=>array()];
                $begin = new DateTime( "@".strtotime($a->trainDtStart.' UTC') );
                $end   = new DateTime( "@".strtotime($a->trainDtEnd.' UTC') );
                
                for($i = $begin; $i <= $end; $i->modify('+1 day')){
                    
                    $attnd = \App\Models\trainingattnds::select("tattndTimestamp")->whereDate(DB::raw("DATE(tattndTimestamp)"), $i->format("Y-m-d"))->where('tnee_trainFK',$b->tnee_trainPK)->get();
                    
                    $first = "";
                    $last ="";

                    if(count($attnd)){
                    if( strtotime(date('h:i A',strtotime($attnd[0]->tattndTimestamp.' UTC')))<strtotime("13:00:00")){
                        $first=date('h:i A',strtotime($attnd[0]->tattndTimestamp.' UTC'));
                     }

                     if( strtotime(date('h:i A',strtotime($attnd[count($attnd) - 1]->tattndTimestamp.' UTC')))>strtotime("13:00:00")){
                        $last=date('h:i A',strtotime($attnd[count($attnd) - 1]->tattndTimestamp.' UTC'));
                     }
                     }
                    $daily=(object)["date"=>$i->format("Y-m-d"),"record"=>array($first,$last)];
                    array_push($trrneeatt->attendance, $daily);
                }
                array_push($rowdata, $trrneeatt);
            }
            
            // array_push($newdata, $training);

            
           $new_attnd = \App\Models\training_attends::firstOrNew(['trainFK' =>  $a->trainPK]);
           $new_attnd->trainingAttnd= json_encode($rowdata);
             $new_attnd->save();
        // }
           
        return redirect()->back();
          
        
    }
    function migrateAll(Request $request)
	{
        // \App\Models\training_attends::truncate();
        
        $exist = \App\Models\training_attends::pluck('trainFK')->toArray();
        $newdata=[];
        $today = Carbon::today();
        $trn = \App\Models\trainings::whereNotIn('trainPK', $exist)->whereDate('trainDtStart', '<=', $today)->where('trainName','not like',"%Withdraw%")->get();
        // dd($trn);
        foreach($trn as $a)
        {
            $training=(object)["trainPK"=>$a->trainPK,"trainingAttendance"=>array()];
            $trnee = \App\Models\trainee_trainings::leftJoin('trainees','trneePK','trneeFK')
            ->whereNotNull('trneePK')
            ->where('trainFK',$a->trainPK)->get();
            $rowdata=[];
            foreach($trnee as $b)
            {
                $trrneeatt=(object)["userPK"=>$b->trneeFK,"trneeName"=>ucwords(strtolower($b->trneeName)),"attendance"=>array()];
                $begin = new DateTime( "@".strtotime($a->trainDtStart.' UTC') );
                $end   = new DateTime( "@".strtotime($a->trainDtEnd.' UTC') );
                
                for($i = $begin; $i <= $end; $i->modify('+1 day')){
                    
                    $attnd = \App\Models\trainingattnds::select("tattndTimestamp")->whereDate(DB::raw("DATE(tattndTimestamp)"), $i->format("Y-m-d"))->where('tnee_trainFK',$b->tnee_trainPK)->get();
                    
                    $first = "";
                    $last ="";

                    if(count($attnd)){
                    if( strtotime(date('h:i A',strtotime($attnd[0]->tattndTimestamp.' UTC')))<strtotime("13:00:00")){
                        $first=date('h:i A',strtotime($attnd[0]->tattndTimestamp.' UTC'));
                     }

                     if( strtotime(date('h:i A',strtotime($attnd[count($attnd) - 1]->tattndTimestamp.' UTC')))>strtotime("13:00:00")){
                        $last=date('h:i A',strtotime($attnd[count($attnd) - 1]->tattndTimestamp.' UTC'));
                     }
                     }
                    $daily=(object)["date"=>$i->format("Y-m-d"),"record"=>array($first,$last)];
                    array_push($trrneeatt->attendance, $daily);
                }
                array_push($rowdata, $trrneeatt);
            }
            
            // array_push($newdata, $training);

            
           $new_attnd = new \App\Models\training_attends;
           $new_attnd->trainFK= $a->trainPK;
           $new_attnd->trainingAttnd= json_encode($rowdata);
             $new_attnd->save();
        }
           
        return response()->json(['isSuccess' =>true,'message' =>"berjaya"]);
          
        
    }


    function createAttendance($id)
	{
        $a = \App\Models\training_attends::where('trainFK',$id)->first();
        $training =\App\Models\trainings::where('trainPK',$id)->first();
            if($a){
               
            }else{
                $a= new \App\Models\training_attends;
                $a->trainFK= $id;
            
            $trnee = \App\Models\trainee_trainings::leftJoin('trainees','trneePK','trneeFK')
            ->whereNotNull('trneePK')
            ->where('trainFK',$id)->get();

            $rowdata=[];
            foreach($trnee as $b)
            {
                $trrneeatt=(object)["userPK"=>$b->trneeFK,"trneeName"=>ucwords(strtolower($b->trneeName)),"attendance"=>array()];
                $begin = new DateTime( "@".strtotime($training->trainDtStart.' UTC') );
                $end   = new DateTime( "@".strtotime($training->trainDtEnd.' UTC') );
                
                for($i = $begin; $i <= $end; $i->modify('+1 day')){
                    
                   
                    $first = "";
                    $last ="";

                    $daily=(object)["date"=>$i->format("Y-m-d"),"record"=>array($first,$last)];
                    array_push($trrneeatt->attendance, $daily);
                }
                array_push($rowdata, $trrneeatt);
            }
            
            // array_push($newdata, $training);

            
        //    $new_attnd = new \App\Models\training_attends;
           $a->trainingAttnd= json_encode($rowdata);
            $a->save();
            }
    }
    function getAttnd(Request $request, $id)
		{
        
        
            $id=decrypt($id);
        
        $train = \App\Models\training_attends::leftJoin('trainings','trainFK','trainPK')
        ->leftJoin('courses','coursePK','courseFK')
        ->select('trainPK','trainDateExclude','trainSessionCnt',
        'trainName','trainDtStart','trainDtEnd','courseName','trainingAttnd','attndNote')
        ->where('trainFK', $id)->first();

        if($train){
            
        }
        else{
            $this->createAttendance($id);

            $train = \App\Models\training_attends::leftJoin('trainings','trainFK','trainPK')
        ->leftJoin('courses','coursePK','courseFK')
        ->select('trainPK','trainDateExclude','trainSessionCnt',
        'trainName','trainDtStart','trainDtEnd','courseName','trainingAttnd','attndNote')
        ->where('trainFK', $id)->first();
        }

        $train->trainingAttnd=json_decode($train->trainingAttnd);
        $train->attndNote=json_decode($train->attndNote);
        $train->trainDateExclude=is_null($train->trainDateExclude)?array():json_decode($train->trainDateExclude);

        // dd($train);
        return view('attendance_new', ['train' => $train]);
    }
    
    function addNotes(Request $request)
        {
            $newdata=[];
            $a = \App\Models\training_attends::where('trainFK',$request->id)->first();
            if($a){
                $notes=json_decode($a->attndNote);
                array_push($notes,(object)["code"=>$request->code,"desc"=>$request->desc]);
                $a->attndNote=json_encode($notes);
                $a->save();
            }
            return redirect()->back();
        }
        function deleteNotes(Request $request)
        {
            $newdata=[];
            $a = \App\Models\training_attends::where('trainFK',$request->pk)->first();
            if($a){
                $notes=json_decode($a->attndNote);
                $key = array_search($request->code, array_column($notes, 'code'));
                array_splice($notes,$key,1);
                $a->attndNote=json_encode($notes);
                $a->save();
            }
             return redirect()->back();
        }
    function addnewattnd(Request $request)
		{
            $regex = '/^([01][0-9]|2[0-3]):([0-5][0-9])$/';
            $new_time;
            if (preg_match($regex,
                $request->time))
                {
                    $new_time  = date("h:i A", strtotime($request->time));
                }
                else
                {
                    $new_time  = $request->time;
                    
                }
                

            $newdata=[];
            $a = \App\Models\training_attends::leftJoin('trainings','trainFK','trainPK')
            ->where('trainFK',$request->id)->first();
            
            $b = \App\Models\trainees::where('trneePK',$request->pk)->first();
            if($a){
                if($a->trainingAttnd){
                    $newdata=json_decode($a->trainingAttnd);
                    $key = array_search($request->pk, array_column($newdata, 'userPK'));
                    if($key !== false){
                        $key2 = array_search($request->date, array_column($newdata[$key]->attendance, 'date'));
                        if($key2 !== false){
                            if (array_key_exists($request->post,$newdata[$key]->attendance[$key2]->record))
                            {
                                $newdata[$key]->attendance[$key2]->record[$request->post]=$new_time;
                            }
                            else{
                                
                                $original = $newdata[$key]->attendance[$key2]->record;

                                for ($i = count($original)-1; $i < $a->trainSessionCnt; $i++){
                                    array_push($original, "");
                                }

                                $inserted = $new_time; 
                                array_splice( $original, $request->post, 1, $inserted );
                                $newdata[$key]->attendance[$key2]->record=$original;
                            }
                        }
                        else{
                            
                            $new_array=[];
                            for ($i = 0; $i < $a->trainSessionCnt; $i++){
                                array_push($new_array, $i==$request->post?$new_time:"");
                            }
                            $daily=(object)["date"=>$request->date,"record"=>$new_array];

                            $begin = new DateTime( "@".strtotime($a->trainDtStart.' UTC') );
                            $end   = new DateTime( "@".strtotime($a->trainDtEnd.' UTC') );

                            $inputDate  = new DateTime( "@".strtotime($request->date) );

                            $original=$newdata[$key]->attendance;
                            $datekey=0;
                            for($i = $begin; $i <= $end; $i->modify('+1 day')){
                                if($i==$inputDate){
                                    array_splice( $original, $datekey, 0, $daily );
                                    $newdata[$key]->attendance=$original;
                                }
                                $datekey++;
                            }
                        }

                    }
                    else{
                            $trrneeatt=(object)["userPK"=>$b->trneeFK,"trneeName"=>ucwords(strtolower($b->trneeName)),"attendance"=>array()];
                            $begin = new DateTime( "@".strtotime($a->trainDtStart.' UTC') );
                            $end   = new DateTime( "@".strtotime($a->trainDtEnd.' UTC') );

                            for($i = $begin; $i <= $end; $i->modify('+1 day')){
                                $daily;
                                $new_array=[];
                                
                                if($i->format("Y-m-d")==$request->date){
                                    for ($i = 0; $i < $a->trainSessionCnt; $i++){
                                        array_push($new_array, $i==$request->post?$new_time:"");
                                    }
                                }
                                else{
                                    for ($i = 0; $i < $a->trainSessionCnt; $i++){
                                        array_push($new_array, "");
                                    }
                                }
                                $daily=(object)["date"=>$i->format("Y-m-d"),"record"=>$new_array];
                                array_push($trrneeatt->attendance, $daily);
                            }
                            
                    array_push($newdata, $trrneeatt);
                    }
                    $a->trainingAttnd= json_encode($newdata);
                    $a->save();
                    return redirect()->back();
                }
                else{
                    $new_attnd = new \App\Models\training_attends;
                    $new_attnd->trainFK= $request->id;
                    //$new_attnd->trainingAttnd= json_encode($rowdata);
                    //$new_attnd->save();
                }
                
            }
            

            

        
    }
    function excludetraindate(Request $request){
        
    $train = \App\Models\trainings::where('trainPK', $request->pk)->firstOrFail();
    $trainDateExclude=is_null($train->trainDateExclude)?array():json_decode($train->trainDateExclude);
    if(!in_array($request->date, $trainDateExclude)){
        array_push($trainDateExclude, $request->date);
        
        $train->trainDateExclude= json_encode($trainDateExclude);
        $train->save();
    }
    
    return redirect()->back();
    }
    
}
