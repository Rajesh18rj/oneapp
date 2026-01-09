
<link href="http://netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<link href="{{asset('public/css/custom.css')}}" rel="stylesheet">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

<!------ Include the above in your HEAD tag ----------> 
<style type="text/css"> 
   body{
   width:100%!important;
   font-size:12px;
   font-family: "DejaVu Sans,Helvetica Neue",Helvetica,Arial,sans-serif!important;
   }
   *{
   font-family: "DejaVu Sans,Helvetica Neue",Helvetica,Arial,sans-serif!important;
   }
   .container{
   width: 700px;
   }
   .outer_border{
   border:1px solid #999999!important;
   padding:4%!important;

   
   margin-bottom:2%!important;
   }
   .top_box{
   width:47%; padding:0%
   }
   .table_pad{
   padding:0% 2%;
   } 
   .border{
   border:1px solid #CCCCCC!important;
   }
   .small_text{
   font-size:10px!important;
   }
   .bg_color1{
   background:#3a5082;
   color: #fff;
   }
   .text_color1{outer_border
   color:#3a5082;
   }
   td{
   padding:4px;
   } 
   .pull-right{
    float:right
   }
</style>
<?php ?>
<div class="container">
   <div class="outer_border">
      <div class="row">
         <div  class=" pull-left top_box p-4">


            
            <img src="{{ $logoPath }}" height="100">		   
            <h2 class="text_color1" style="font-size:20px">{{ ($order->store && $order->store->name) ? $order->store->name : 'N/A' }}</h2> 

           {{ ($order->store && $order->store->address) ? ucfirst($order->store->address) : 'N/A' }}
           <br>
           <p>
                Phone : {{ ($order->store && $order->store->phone) ? $order->store->phone : 'N/A' }} <br>
                Email : {{ ($order->store && $order->store->email) ? $order->store->email : 'N/A' }} <br>
                GST Number : {{ ($order->store && $order->store->gst_number) ? $order->store->gst_number : 'N/A' }} <br>
               	@if(isset($order->store) && !empty($order->store))
		        @if($order->store->module_id != '4')
		             FSSAI  Number : {{ ($order->store && $order->store->fssai_number) ? $order->store->fssai_number : 'N/A' }} <br>
		             Drug License Number : {{ ($order->store && $order->store->drug_license_number) ? $order->store->drug_license_number : 'N/A' }} <br>
		        @endif
		  @else
		      FSSAI  Number : N/A <br>
		      Drug License Number : N/A <br>
		@endif
           	
         </p>
         
    
         </div>
         <div style="" class="pull-right top_box p-4" style="width:47%; padding:0%;margin-top:-150px;">
            <h2 style="color:#687cbf;font-weight: bold;font-size:30px; text-align:right; padding-right: 30px;" style="color:#687cbf;font-weight: bold;font-size:30px; text-align:right; padding-right: 30px;" id="invoice">INVOICE</h2>
            <table width="100%" height="70" border="0" class="table_pad" style="margin-top:20px;">
               <tr>
                  <td> Date</td>
                  <td>
                  {{ date('d M Y ' . config('timeformat'), strtotime($order['created_at'])) }}


                  </td>
               </tr>
               <tr>
                  <td width="50%">Invoice </td>
                  <td width="50%">#{{ $order['id'] }}</td> 
               </tr>
               <tr>
                  <td>Customer ID</td>
                  <td>{{ $order->customer['id'] }} </td>
               </tr>
            </table>
         </div>
      </div>
      <div class="row" style="right:15px;position:relative">
         <div class="">
            <table width="100%" border="0">
               <tr>
                  <td colspan="2">
                     <div class="bg_color1" style="text-indent:10px;font-size: 14px;width: 50%;height: 26px;line-height: 18px; ">BILL TO </div>
                     <table width="100%" border="0">
                         <?php
                           $address = json_decode($order->delivery_address, true);     
                         ?>
                        <tr>
                        
                           <td width="18%">Name</td>
                           <td width="82%"> {{ $order->customer['f_name'] . ' ' . $order->customer['l_name'] }}</td>
                        </tr>
                        <tr>
                           <td>Phone</td>
                           <td>{{$order->customer['phone']}}</td>
                        </tr>
                        <tr>
                           <td>Email</td>
                           <td>{{$order->customer['email']}}</td>
                        </tr>
                        <tr>
                           <td> Address</td>
                           <td>{{$address['address']}}  </td>
                        </tr>
                     </table>
                  </td>
               </tr>
               <tr>
                  <td colspan="2"> </td>
               </tr>
            </table>
         </div>
      </div>
      <dd style="clear:both;"></dd>
      <div class="row" style="right:15px;position:relative">
      <div class="bg_color1" style="text-indent:10px;font-size: 14px;width: 50%;height: 26px;line-height: 18px; ">Order Details</div>
         <table height="82"  style="width:100%;margin-top:10px;" border="1" cellpadding="0" cellspacing="0">
            <tr class="bg_color1">
               <td width="18%">S. No.</td>
               <td width="58%" height="12" style="padding-left: 10px;">Name</td>
               <td width="13%">Quantity </td>
               <td width="58%" height="12" style="padding-left: 10px;">Price</td>
               <td width="13%">HSN </td>
               <td width="13%">SAC </td>
               <td width="13%">GST</td>
               <td width="13%">Tax amount</td>
               <td width="13%">Batch Number</td>
               <td width="13%">Expiry Date</td>
               <td width="13%">Discount </td>
               <td width="13%">Subtotal</td>
            </tr>
            
           
            
            @php
         
               $count = 0;
               $total_addon_price = 0;
               $product_price = 0;
               $details = $order->details;
               $deliveryCharge = $order['delivery_charge']; 
               $storeDiscountAmount = 0;
               $total_tax_amount = $order['total_tax_amount'];
               $coupon_discount_amount = $order['coupon_discount_amount'];
               $totalTaxAmount  = 0;
               $productSubTotal = 0;
               @endphp

               <?php
            if(count($details) > 0){
               foreach ($details as $key => $detail){
               
               foreach (json_decode($detail['add_ons'], true) as $key2 => $addon){
                $total_addon_price += $addon['price'] * $addon['quantity'];
               }
                 
            
	
             
                     
                  $amount = $detail['price'] * $detail['quantity'];             
                  $product_price = $amount;
                  $storeDiscountAmount += $detail['discount_on_item'] * $detail['quantity'];
                  
                 
    

                  
                  $productData =  \App\CentralLogics\ProductLogic::get_product($detail->item['id'] ?? '');                 
                  
                  $productCount = (count((array)$productData));
            
            	
		   $count++;
		   $productDiscount = $productData->discount ?? 0;	
            		
                  $productTax = $productData->tax ?? 0;
                  $subTotal = ($detail['price'] * $detail['quantity']) - ($productDiscount* $detail['quantity']) ;
                  $percentAmount = $subTotal * ($productTax/100);
                  $totalTaxAmount += $percentAmount;
                  $taxAmount= $percentAmount;                  
                  $subTotal = $subTotal +  ($subTotal * ($productTax/100));
                  $productSubTotal += $subTotal;
                  
                  $hsn = $productData->hsn ?? '';
		  $sac = $productData->sac ?? '';
		  $batch_number = $productData->batch_number ?? '';
		  $expiry_date = $productData->expiry_date ?? '';	
                   $productPrice =  \App\CentralLogics\Helpers::format_currency($detail['price']);

                   ?>
                   <tr>
                   <td>{{$count }}</td>
                  <td> {{$detail->item['name'] ?? ''}}  </td> 
                  <td>  {{$detail['quantity'] ?? ''}}   </td>
                  <td> {{ \App\CentralLogics\Helpers::format_currency($detail['price']) }}  </td>                  
                  <td> {{ ($hsn) ? ($hsn) : 'N/A' }}</td>
                  <td> {{ ($sac) ? ($sac ?? '') : 'N/A' }}</td>
                  <td> {{ ($productTax) ? $productTax . "%" : 'N/A' }}</td>
                  <td> {{ ($productTax) ?  \App\CentralLogics\Helpers::format_currency($taxAmount) : 'N/A' }}</td>
                  <td> {{ ($batch_number) ? ($batch_number ?? '') : 'N/A' }}</td>
                  <td> {{ ($expiry_date) ? ($expiry_date ?? '') : 'N/A' }}</td>
                  <td>{{ \App\CentralLogics\Helpers::format_currency($productDiscount * $detail['quantity'] ) }}</td> 
                  <td> {{ \App\CentralLogics\Helpers::format_currency($subTotal) }}</td>               
                  
               </tr>
               <?php
                   
               }}

               ?>
           
         </table>
         <table align="right"  border="1" cellpadding="0" cellspacing="0" style="top:10px;position:relative">
         <tr>
           
            <td> <strong> Store Discount :
             </strong>  </td>
               <td align="right"> {{ \App\CentralLogics\Helpers::format_currency($storeDiscountAmount)}}</td>
               </tr>  
               <tr>
               <td> <strong> Delivery Fee : </strong>  </td>
               <td align="right"> {{ \App\CentralLogics\Helpers::format_currency($deliveryCharge)}}</td>
               </tr>  
               <tr>
               <td> <strong>Total Tax Amount :</strong> </td>
               <td align="right">
               {{ \App\CentralLogics\Helpers::format_currency($totalTaxAmount) }}
               </td>
               </tr> 
               <tr>
               <td> <strong> Addon Cost : </strong>  </td>
               <td align="right"> {{ \App\CentralLogics\Helpers::format_currency($total_addon_price)}}</td>
               </tr>  
               <tr>
               <td> <strong>Coupon Discount :</strong> </td>
               <td align="right">
               {{ \App\CentralLogics\Helpers::format_currency($coupon_discount_amount) }}
               </td>
               </tr> 
               <td> <strong>Grand Total :</strong> </td>
               <td align="right">
               @php   
               $grandTotal = $productSubTotal +  $deliveryCharge + $total_addon_price - $coupon_discount_amount;   
               @endphp
               {{ \App\CentralLogics\Helpers::format_currency($grandTotal) }}
               </td>
            </tr>   
            </table>
      </div>
   </div>
</div>
