<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>A simple, clean, and responsive HTML invoice template</title>
    
    <style>
    .invoice-box {
        max-width: 800px;
        margin: auto;
        padding: 30px;
        border: 1px solid #eee;
        box-shadow: 0 0 10px rgba(0, 0, 0, .15);
        font-size: 16px;
        line-height: 24px;
        font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        color: #555;
    }
    .tableitem{
        border: 1px solid #eee;
    }
    
    .invoice-box table {
        width: 100%;
        line-height: inherit;
        text-align: left;
    }
    
    .invoice-box table td {
        padding: 5px;
        vertical-align: top;
    }
    
    .invoice-box table tr td:nth-child(2) {
        text-align: right;
    }
    
    .invoice-box table tr.information td {
        padding-bottom: 0.5%;
    }
    
    .invoice-box table tr.information td:nth-child(2),.invoice-box table tr.information .secondcolumn {
        text-align: left;
        padding-left:8%;
        width:50%;
    }
    .invoice-box table tr.information td:nth-child(1),.invoice-box table tr.information .firstcolumn {
        padding-right:8%;
        width:50%;
    }
    
    .invoice-box table tr.heading td {
        background: #eee;
        border-bottom: 1px solid #ddd;
        font-weight: bold;
        padding:1% 5%;
    }
    
    .invoice-box table tr.details td {
        padding-bottom: 20px;   
    }
    
    .invoice-box table tr.item td{
        border-bottom: 1px solid #eee;
        padding:0 5%;
    }
    
    .invoice-box table tr.item.last td {
        border-bottom: none;
    }
    
    .invoice-box table td.total  {
        border-top: 2px solid #eee;
        font-weight: bold;
    }
    
    @media only screen and (max-width: 600px) {
        .invoice-header table td {
            width: 100%;
            display: block;
            text-align: center;
        }
        
        .invoice-box table tr.information  td {
            width: 100%;
            display: block;
            text-align: center;
        }
    
    
    }

     .invoice-header {
        width: 860px;
        margin: auto;
        /* padding: 30px;
        font-size: 16px;
        line-height: 24px; */
        font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        border-spacing:0 ;
        border: 1px solid #eee;
        box-shadow: 0 0 10px rgba(0, 0, 0, .15);
        border-bottom:0;
        /* color: #555; */
    }
            .invoice-header  td {

            border-bottom:solid 10px #699b12;
            padding:0;
            }
            .invoice-header  td.title {
            vertical-align: middle;
            }
            .invoice-header  td.bgGreen strong {
            float:right;
            padding-bottom:0.5%;
            font-size: 2em;
            color: #ffffff;
            }

            .invoice-header  td.bgGreen {
            clear:right;
            color: #ddd;
            background-color: #062730;
            vertical-align: middle;
            padding:3% 3% 3% 0;
            border-top-left-radius: 120px;
            }

             .invoice-header  td.bgGreen div{
            clear:right;
            float:right;
            }

.invoice-footer {
        width: 860px;
        margin: auto;
        /* padding: 30px;
        font-size: 16px;
        line-height: 24px; */
        font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        border-spacing:0 ;
        border: 1px solid #eee;
        box-shadow: 0 0 10px rgba(0, 0, 0, .15);
        /* color: #555; */
        
        background-color:#699b12
    }

     .invoice-footer  td {
         padding:2%;
         color:#eee;
    }
    .invoice-footer  td a {
         color:#eee;
    }
     .invoice-footer .holistics {
         width:53%;
         background-color:#062730;
         padding-right:3%;
         text-align:right;
         border-top-right-radius: 10vh;
         border-bottom-right-radius: 10vh;
    }

    .fab{
	position:fixed;
	width:60px;
	height:60px;
	bottom:40px;
	right:40px;
	background-color:#0C9;
	color:#FFF;
	border-radius:50px;
	text-align:center;
	box-shadow: 2px 2px 3px #999;
}

    @media print {
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
.invoice-header {
    width:100%;
    margin:0;
}
.invoice-box {
    width:calc(100% - 60px);
}
.invoice-footer {
    width:100%;
    margin:0;
}
  
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
    <button class="fab" onclick="window.print()">Print</button>
    <div id="printable">
    <table   class="invoice-header">
        <tr>
            <td class="title" width="40%">
                <img src="http://holisticslab.my/wp-content/uploads/2018/11/Logo-Color-Holistics-Lab-with-Slogan.png" style="width:100%; max-width:300px;">
            </td>
            
            <td class="bgGreen">
            <strong>PROFORMA INVOICE</strong>
            <div >
                Invoice #: {{$invNo}}<br>
                Created: {{$date}}
                </div>
            </td>
        </tr>
    </table>
    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
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
            <table cellpadding="0" cellspacing="0" class="tableitem">
            
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

         <table cellpadding="0" cellspacing="0">
           
            <tr class="information">
                <td colspan="2" >
               <p>{!!$paynote!!}
               {{$discnote}}
               </p>
                </td>
            </tr>
            </table>
    </div>
    <table class="invoice-footer">
        <tr>
            <td class="title holistics">
            www.holisticslab.my
             </td>
            
            <td class="bgGreen">
            Aspiring Halal Excellence
            </td>
        </tr>
    </table>
</div>
</body>
</html>