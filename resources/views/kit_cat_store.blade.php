@if($filter_array)
<select class='fast_cat form-control'>
	<option value=0 >請選擇宅急便站所</option>@foreach( $filter_array as $filter_k => $filter_v )<option value={{$filter_k}} ot='{{$filter_v[1]}}'>{{$filter_v[0]}}</option>@endforeach
</select>
@endif
