<style>
  .product-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    .product-table th, .product-table td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }

    .product-image img {
        width: 100%;
        border-radius: 5%;
    }
</style>

 <?php
		$expiredProductColor = "";
		$color = "";
        	$remainingDays = '';
        	if($product['expiry_date'] !=''){
                      $expiryDate = date("Y-m-d",strtotime($product['expiry_date']));
        	       $todayDate = date("Y-m-d");
        	       $remainingDays = floor((strtotime($expiryDate) - strtotime($todayDate)) / (60 * 60 * 24));
        	      
        	        $expiredProductColor = "";
			if ($remainingDays < 15) {
			     $expiredProductColor = "#FF0000"; 
			     $color = "#fff";
			
			}
			
			if ($remainingDays >= 15 && $remainingDays <= 30) {
			     $expiredProductColor = "#ff9900"; 
			     $color = "#fff";
			
			}	
        	       
        	       
        	
        	}
        
        	
        ?>
        
         
                                                
                                   
                            
                    <tr data-id="{{$product->id}}" class="table_row odd" role="row" onclick="quickView('{{$product->id}}')" style="color:{{$color}};cursor:pointer;background:{{$expiredProductColor}}"> 
                    
        
                       
                        <td>
                                 <img src="{{asset('storage/app/public/product')}}/{{$product['image']}}" 
                 onerror="this.src='{{asset('public/assets/admin/img/160x160/img2.jpg')}}'" style="width: 100px;height: 100px;" />
                        </td>
                        
                           <td> 
                                  {{ Str::limit($product['name'], 12, '...') }}
                        </td>
                        
                         <td>
                                {{ $product['hsn'] ?? 'N/A'}}
                        </td>
                     
                        <td>
                        <?php
                           $temp = $product->category;
    
        $sub_category = '';
        $category = '';
        if(isset($temp) && !empty($temp)){
		if($temp->position)
		{
		    $sub_category = $temp;
		    $category = $temp->parent;
		}
	}
		else
		{
		    $category = $temp;
		    $sub_category = null;
		}
		
		   if($category){
		      echo $category['name'];
		   } else {
		   
		     echo "N/A";
		   }
		   
		   if($sub_category){
		      $sub_category_name = $product['category']->name;
		   } else {
		   
		     $sub_category_name = "N/A";
		   }
		
		
		
		?>	
                       
                        </td>
                           <td>
                                {{ $product['sac'] ?? 'N/A'}}
                        </td>
                        <td>
                       {{Str::limit($product['store']?$product['store']->name:translate('messages.store deleted!'), 20, '...')}}
                        </td>
                        <td>
                        
                                                {{\App\CentralLogics\Helpers::format_currency($product['price']-\App\CentralLogics\Helpers::product_discount_calculate($product, $product['price'], $store_data))}}
                        
                        </td>
                        <td>
                              {{ $product['tax'] }}
                        </td>
                        <td>
                             {{ $product['batch_number'] }}
                        </td>
                        <td>{{ $product['expiry_date'] ?? 'N/A'}}</td>
                        <td>{{  $product['description'] }}</td>
                        
                        <td>
                          @foreach(\App\Models\Module::notParcel()->get() as $module)
                                           @if($module->id == $product['module_id'])
                                        {{$module->module_name}}
                                        @endif
                                    @endforeach
                        </td>
                        
                        <td>{{$product['discount_type'] }}</td>
                        
                        <td>{{$product['discount'] }}</td>
                        <td>
                        @foreach (\App\Models\Unit::all() as $unit)
                                        @if($unit->id == $product['unit_id'])
                                        
                                         {{$unit->unit}}
                                         
                                         @endif
                                    @endforeach
                        </td>
                        
                        <td>
                        @if($product['veg']==0 )
                         {{translate('messages.non_veg')}}
                         @else
                         {{translate('messages.veg')}}
                         
                         @endif
                         
                        </td>
                        
                        <td>{{ $sub_category_name }}</td>
                        <td>{{$product['stock']}}</td>
                        <td>{{$product['available_time_starts']}}</td>
                        <td>{{$product['available_time_ends']}}</td>
                        <td>{{$product['video_url'] ?? 'N/A'}}</td>
                   
                    </tr>

              
        



