<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Mail;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use PDF;

class MailController extends Controller {
   public function basic_email(){
      $data = array('name'=>"Holisticslab Training Department");
   
      Mail::send(['text'=>'mail'], $data, function($message) {
         $message->to('mrahmatharun@gmail.com', 'Tutorials Point')->subject
            ('Laravel Basic Testing Mail');
         $message->from('training@holisticslab.my','Holisticslab Training Department');
      });
      echo "Basic Email Sent. Check your inbox.";
   }
   public function html_email(){

   
      try
      {
          $invoice = \App\Models\invgenerators::where('invYear', Carbon::now()->format('Y'))->firstOrFail();
      }
      // catch(Exception $e) catch any exception
      catch(ModelNotFoundException $e)
      {
          $invoice = new \App\Models\invgenerators;
          $invoice->invYear= Carbon::now()->format('Y');
          $invoice->invLastNo=1;
          $invoice->invCode="HL";
          
          $invoice->save();
  
      }

      
      $invNo=$invoice->invCode.($invoice->invLastNo+1).'/'.$invoice->invYear;
      
      $biller = \App\Models\billers::where('billerPK',1)->firstOrFail();
    $data = [
       
      'invNo'=>$invNo,
      'date'=> Carbon::now()->format('M d, Y'),
      'from'=> $biller->billerName,
      'fromaddr'=> nl2br($biller->billerAddress),
      'fromtel'=> $biller->billerTel,
      'fromemail'=> $biller->billerEmail,
        'course'=> "Profesional Certificate in Halal Executive(PCHE)",
      'to'=>"Farm's Best Food Sdn Bhd",
      'toaddr'=>nl2br("Lot 37 & 38 
      Masjid Tanah Industrial Estate, 
      78300 Masjid Tanah, Melaka"),
      'tel'=>"019 9876543",
      'trnee'=>"Nuraqilah Binte Mohd Nazri",
      'email'=>"aqilah@farmbest.my",
      'price'=>"1000",
      'discnote'=>'',
      'details'=>"<p><strong>Estimate Date:<br /></strong></p> <p>8-11 OCT 2018<br />15-18 OCT 2018</p> <ul> <li>8 days (Fulltime Class)</li> <li>Registration Kit</li> <li>QuikHalal License 3month</li> <li>E-learning Access (hacademy.my)</li> </ul>",
      'paynote'=>$biller->billerNote
   ];

   $pdf = PDF::loadView('attchInvoice', $data)->setPaper('a4', 'potrait');  
      Mail::send('thanksEmail', $data, function($message)use ($pdf) {
         $message->to('akulah91@gmail.com', 'Farah farah')->subject
            ('Thank You for register');
         $message->from('training@holisticslab.my','Holisticslab Training Department')
         ->attachData($pdf->output(), "invoice.pdf");
      });
      echo "HTML Email Sent. Check your inbox.";
   }
   public function view_Template(){
      try
      {
          $invoice = \App\Models\invgenerators::where('invYear', Carbon::now()->format('Y'))->firstOrFail();
      }
      // catch(Exception $e) catch any exception
      catch(ModelNotFoundException $e)
      {
          $invoice = new \App\Models\invgenerators;
          $invoice->invYear= Carbon::now()->format('Y');
          $invoice->invLastNo=1;
          $invoice->invCode="HL";
          
          $invoice->save();
  
      }

      
      $invNo=$invoice->invCode.($invoice->invLastNo+1).'/'.$invoice->invYear;
      
      $biller = \App\Models\billers::where('billerPK',1)->firstOrFail();
      $data = [
       
         'invNo'=>$invNo,
         'date'=> Carbon::now()->format('M d, Y'),
         'from'=> $biller->billerName,
         'fromaddr'=> nl2br($biller->billerAddress),
         'fromtel'=> $biller->billerTel,
         'fromemail'=> $biller->billerEmail,
           'course'=> "Profesional Certificate in Halal Executive(PCHE)",
         'to'=>"Farm's Best Food Sdn Bhd",
         'toaddr'=>nl2br("Lot 37 & 38 
         Masjid Tanah Industrial Estate, 
         78300 Masjid Tanah, Melaka"),
         'tel'=>"019 9876543",
         'trnee'=>"Nuraqilah Binte Mohd Nazri",
         'email'=>"aqilah@farmbest.my",
         'price'=>"1000",
         'discnote'=>'',
         'details'=>"<p><strong>Estimate Date:<br /></strong></p> <p>8-11 OCT 2018<br />15-18 OCT 2018</p> <ul> <li>8 days (Fulltime Class)</li> <li>Registration Kit</li> <li>QuikHalal License 3month</li> <li>E-learning Access (hacademy.my)</li> </ul>",
         'paynote'=>$biller->billerNote
      ];
      return view('attchInvoice',$data);
   }
   public function attachment_email(){



      $data = array('name'=>"Holisticslab Training Department");
      Mail::send(['text'=>'mail'], $data, function($message) {
         $message->to('mrahmatharun@gmail.com', 'Tutorials Point')->subject
            ('Laravel Testing Mail with Attachment');
         $message->from('training@holisticslab.my','Holisticslab Training Department');
      });
      echo "Email Sent with attachment. Check your inbox.";
   }
}