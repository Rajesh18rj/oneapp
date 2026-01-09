@foreach($zones as $key=>$zone)
<tr>
<td><input type="checkbox" class="row-checkbox"></td>
    <td>{{$key+1}}</td>
    <td>{{$zone->id}}</td>
    <td>
    <span class="d-block font-size-sm text-body">
        {{$zone['name']}}
    </span>
    </td>
     <td>
		<span class="d-block font-size-sm text-body">
		{{ $zone['sub_zone'] ?? 'N/A' }}
		</span>
	</td>
    <td>{{$zone->stores_count}}</td>
    <td>{{$zone->deliverymen_count}}</td>
    <td>
        <label class="toggle-switch toggle-switch-sm" for="stocksCheckbox{{$zone->id}}">
            <input type="checkbox" onclick="status_form_alert('status-{{$zone['id']}}','Want to change status for this zone ?', event)" class="toggle-switch-input" id="stocksCheckbox{{$zone->id}}" {{$zone->status?'checked':''}}>
            <span class="toggle-switch-label">
                <span class="toggle-switch-indicator"></span>
            </span>
        </label>
        <form action="{{route('delivery-agent.zone.status',[$zone['id'],$zone->status?0:1])}}" method="get" id="status-{{$zone['id']}}">
        </form>
    </td>
</tr>
@endforeach
