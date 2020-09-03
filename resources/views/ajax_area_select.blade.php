<!-- 國家 -->
<select id='country' name="country" class="form-control custom_form_control">
    @foreach( $countrys as $countryk => $country)
        @if( $df_country)
        <option @if( $country['region_id'] == $df_country ) SELECTED @endif value="{{$country['region_id']}}">{{$country['region_name']}}</option>
        @else
        <option @if( $country['region_id'] == 1 ) SELECTED @endif value="{{$country['region_id']}}">{{$country['region_name']}}</option>
        @endif
    @endforeach
</select>
<!-- 國家結束 -->
                
<!-- 州 -->
@if( $provinces != false )
<select id='province' name="province" class="form-control custom_form_control">
@foreach( $provinces as $provincek => $province)
    @if($df_province)
        <option @if( $province['region_id'] == $df_province ) SELECTED @endif value="{{$province['region_id']}}" >{{$province['region_name']}}</option>
    @else
        <option @if( $province['region_id'] == 1 ) SELECTED @endif value="{{$province['region_id']}}">{{$province['region_name']}}</option>
    @endif
@endforeach
</select>
@endif
<!-- 州結束 -->
                
            <!-- 縣市 -->
            @if( $citys != false )
            <select id='city' name="city" class="form-control custom_form_control">
            @foreach( $citys as $cityk => $city)

                @if($df_city)
                    <option @if( $city['region_id'] == $df_city ) SELECTED @endif value="{{$city['region_id']}}" >{{$city['region_name']}}</option>
                @else
                    <option @if( $city['region_id'] == 1 ) SELECTED @endif value="{{$city['region_id']}}">{{$city['region_name']}}</option>
                @endif  

            @endforeach                    
            </select>
            @endif
            <!-- 縣市結束 --> 