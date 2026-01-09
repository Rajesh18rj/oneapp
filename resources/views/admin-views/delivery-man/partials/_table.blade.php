@foreach($delivery_men as $key=>$dm)
    <tr>
     <td><input type="checkbox" class="row-checkbox"></td>
        <td>{{$key+1}}</td>
        <td>
            <a class="media align-items-center" href="{{route('admin.delivery-man.preview',[$dm['id']])}}">
                <img class="avatar avatar-lg mr-3" onerror="this.src='{{asset('public/assets/admin/img/160x160/img1.jpg')}}'"
                        src="{{asset('storage/app/public/delivery-man')}}/{{$dm['image']}}" alt="{{$dm['f_name']}} {{$dm['l_name']}}">
                <div class="media-body">
                    <h5 class="text-hover-primary mb-0">{{$dm['f_name'].' '.$dm['l_name']}}</h5>
                </div>
            </a>
        </td>
        <td>
            @if($dm->zone)
            <label class="badge badge-soft-info">{{$dm->zone->name}}</label>
            @else
            <label class="badge badge-soft-warning">{{translate('messages.zone').' '.translate('messages.deleted')}}</label>
            @endif
            {{--<span class="d-block font-size-sm">{{$banner['image']}}</span>--}}
        </td>
        <td class="text-center">
            @if($dm->application_status == 'approved')
                @if($dm->active)
                <label class="badge badge-soft-primary">{{translate('messages.online')}}</label>
                @else
                <label class="badge badge-soft-secondary">{{translate('messages.offline')}}</label>
                @endif
            @elseif ($dm->application_status == 'denied')
                <label class="badge badge-soft-danger">{{translate('messages.denied')}}</label>
            @else
                <label class="badge badge-soft-info">{{translate('messages.pending')}}</label>
            @endif
        </td>
         <?php
	if($dm['delivery_type'] == 'individual'){
                                          
	?>
         <td class="text-center">
         
                                            @if ($dm->approve_status == 'approve')

                                                    <label
                                                        class="badge badge-soft-primary">{{ translate('messages.Approve') }}</label>
                                               
                                            @elseif ($dm->approve_status == 'reject')
                                                <label
                                                    class="badge badge-soft-danger">{{ translate('messages.reject') }}</label>
                                            @elseif ($dm->approve_status == 'pending')
                                                <label
                                                    class="badge badge-soft-info">{{ translate('messages.pending') }}</label>
                                          
                                            
                                            @else
                                                <label class="badge badge-soft-info">N/A</label>
                                            @endif
                                        </td>
                                         <?php } ?>
                                        <?php
	if($dm['delivery_type'] == 'agent'){
                                          
	?>
                                        <td class="text-center">
                                            @if ($dm->employee_status == 'approve')

                                                    <label
                                                        class="badge badge-soft-primary">{{ translate('messages.Approve') }}</label>
                                               
                                            @elseif ($dm->employee_status == 'reject')
                                                <label
                                                    class="badge badge-soft-danger">{{ translate('messages.reject') }}</label>
                                            @elseif ($dm->employee_status == 'pending')
                                                <label
                                                    class="badge badge-soft-info">{{ translate('messages.pending') }}</label>
                                          
                                            
                                            @else
                                                <label class="badge badge-soft-info">N/A</label>
                                            @endif
                                        </td>
                                          <?php } ?>
        <td>
            <a class="deco-none" href="tel:{{$dm['phone']}}">{{$dm['phone']}}</a>
        </td>
         <?php
	if($dm['delivery_type'] == 'individual'){
                                          
	?>
        <td>
                                        <?php
                                        
                                           $deliveryAgentData = \App\Models\DeliveryMan::find($dm['added_by']);
                                           if($deliveryAgentData){
                                        ?>
                                          {{ $deliveryAgentData->f_name }} {{ $deliveryAgentData->l_name }}
                                        
                                        <?php } else { ?>
                                          N/A  	
                                        <?php } ?>
                                        </td>
                                        <?php } ?>
        <td>
        
        
            <a class="btn btn-sm btn-white" href="{{route('admin.delivery-man.edit',[$dm['id']])}}" title="{{translate('messages.edit')}}"><i class="tio-edit"></i>
            </a>
              <?php
                                              if($dm['added_by'] != ''){
                                            
                                            ?>
                                            
                                            <a class="btn btn-sm btn-white text-danger" href="javascript:"
                                                onclick="form_alert('approve-delivery-man-{{ $dm['id'] }}','Want to approve this deliveryman ?')"
                                                title="{{ translate('messages.Approve') }}">
                                                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
 					          <path d="M9 16.17l-3.5-3.5 1.17-1.17L9 13.83l6.33-6.33L16.5 9 9 16.17z"/>
					         </svg>

                                            </a>
                                            
                                  
                                            
                                          
                                             <a class="btn btn-sm btn-white"
                                               onclick="form_alert('reject-delivery-man-{{ $dm['id'] }}','Want to reject this deliveryman ?')"
                                                title="{{ translate('messages.Reject') }}">
                                                <span style="font-size:20px;">&times;</span>

                                            </a>
                                          
                                            <?php } ?>
            <a class="btn btn-sm btn-white text-danger" href="javascript:" onclick="form_alert('delivery-man-{{$dm['id']}}','Want to remove this deliveryman ?')" title="{{translate('messages.delete')}}"><i class="tio-delete-outlined"></i>
            </a>
            <form action="{{route('admin.delivery-man.delete',[$dm['id']])}}" method="post" id="delivery-man-{{$dm['id']}}">
                @csrf @method('delete')
            </form>
        </td>
    </tr>
@endforeach
