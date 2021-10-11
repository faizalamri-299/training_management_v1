<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>A simple, clean, and responsive HTML invoice template</title>
    
    <style type="text/css" media="all">
    .invoice-box {
        max-width: 100%;
        padding: 2px 30px;
        border: 0;;
        font-size: 10pt;
        line-height: 10pt;
        font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        color: #555;
    }
    .tableitem{
        border: 1px solid #eee;
    }
    
    table.invoice-box {
        width: 100%;
        text-align: left;
    }
    
    table.invoice-box td {
        padding: 5px;
        vertical-align: top;
    }
    
    table.invoice-box tr td:nth-child(2) {
        text-align: right;
    }
    
    table.invoice-box tr.information td {
        padding-bottom: 0.5%;
    }
    
    table.invoice-box tr.information td:nth-child(2),table.invoice-box tr.information .secondcolumn {
        text-align: left;
        padding-left:8%;
        width:50%;
    }
    table.invoice-box tr.information td:nth-child(1),table.invoice-box tr.information .firstcolumn {
        padding-right:8%;
        width:50%;
    }
    
    table.invoice-box  tr.heading td {
        background-color: #eee;
        border-bottom: 1px solid #ddd;
        font-weight: bold;
        padding:5px 20px;
    }
    
    table.invoice-box tr.details td {
        padding-bottom: 20px;   
    }
    
    table.invoice-box tr.item td{
        border-bottom: 1px solid #eee;
        padding:0 20px;
    }
    
    table.invoice-box tr.item.last td {
        border-bottom: none;
    }
    
    table.invoice-box td.total  {
        border-top: 2px solid #eee;
        font-weight: bold;
    }
    
     .invoice-header {
        /* padding: 30px;*/
        width:100%;
        font-size: 12pt;
        line-height: 12pt; 
        font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        border-spacing:0 ;
        border-bottom:solid 10px #699b12;
        margin-bottom:20px;
        /* color: #555; */
    }
            .invoice-header  td.title {
            vertical-align: middle;
            }
            .invoice-header  td.bgGreen strong {
            float:right;
            padding-bottom:0.5%;
            font-size: 2em;
            line-height: 1em; 
            color: #ffffff;
            }

            .invoice-header  td.bgGreen {
            clear:right;
            color: #ddd;
            background-color: #062730;
            vertical-align: middle;
            padding:10px;
            }

             .invoice-header  td.bgGreen p{
                 margin-left:auto;
                 margin-right:0;
                 width:200px;
                 
            }

.invoice-footer {
     
        font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        border-spacing:0 ;
        border:0;
        /* color: #555; */
    }

     .invoice-footer  td {
         padding:2%;
         color:#eee;
    }
    .invoice-footer  td a {
         color:#eee;
    }
     .invoice-footer .holistics {
         width:50%;
         background-color:#062730;
         padding-right:3%;
         text-align:right;
    }
    .invoice-footer .bgGreen {
         width:50%;
         background-color:#699b12;
         padding-left:3%;
         text-align:left;
    }


        @page {
    size: 7in 9.25in;
    -webkit-print-color-adjust: exact;
     background-color: white;
     margin:0;
     padding:0;
   }
   body {
    size: 7in 9.25in;
   margin: 0;
}

 .breaksection{page-break-before: always;}
  
.fab {display:none; visibility: hidden;}
#printable{
  
  -webkit-print-color-adjust: exact;
  padding: 0;
  
}
.invoice-box {
    width:100%;
}
.invoice-footer {
    width:100%;
    margin:0;
}
  

    
    /** RTL **/
    .rtl {
        direction: rtl;
        font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
    }
    
    .rtl table {
        text-align: right;
    }
    
    .rtl table tr td:nth-child(2) {
        text-align: left;
    }
    </style>
</head>

<body >
    <table   class="invoice-header">
        <tr>
            <td class="title" width="40%">
                <img src="http://holisticslab.my/wp-content/uploads/2018/11/Logo-Color-Holistics-Lab-with-Slogan.png" style="width:300px;">
                
            </td>
            
            <td class="bgGreen">
            <strong>PROFORMA INVOICE</strong>
            <p >
                Invoice #: {{$invNo}}<br>
                Created: {{$date}}
                </p>
            </td>
        </tr>
    </table>
        <table class="invoice-box" cellpadding="0" cellspacing="0">
            <tr class="information">
                <td class="firstcolumn">
                To:<br/>
                <strong>{{ $to }}</strong><br/>
                {!! $toaddr !!}<br/>
                {{ $tel }}<br/>
                {{ $trnee }}<br/>
                {{ $email }}<br/>
                </td>
                <td class="secondcolumn">
                From:<br/>
                <strong>{{ $from }}</strong><br/>
                {!! $fromaddr !!}<br/>
                {{ $fromtel }}<br/>
                {{ $fromemail }}<br/>
                </td>
            </tr>
            <tr class="information">
                <td colspan="2" >
               <p>Thank you very much for your intrest in registering {{$course}} organized by {{$from}}.
               </p>
                </td>
            </tr>
            </table>
    <table cellpadding="0" cellspacing="0" class="invoice-box tableitem">
            
            <tr class="heading">
                <td>
                    Item
                </td>
                
                <td>
                    Price
                </td>
            </tr>
            <tr class="item last">
                <td>
                <p><strong>{{$course}} </strong></p> 
                    {!! $details !!}
                </td>
                
                <td>
                  <p>  {{$price}} </p>
                </td>
            </tr>
            
            <tr >
                <td></td>
                
                <td class="total">
                   Total: {{$price}}
                </td>
            </tr>
        </table>

         <table class="invoice-box" cellpadding="0" cellspacing="0">
           
            <tr class="information">
                <td colspan="2" >
               <p>{!!$paynote!!}
               {{$discnote}}
               </p>
                </td>
            </tr>
            </table>
    <table class="invoice-footer" cellpadding="0" cellspacing="0">
        <tr>
            <td class="title holistics">
            www.holisticslab.my
             </td>
            
            <td class="bgGreen">
            Aspiring Halal Excellence
            </td>
        </tr>
    </table>
</body>
</html>