@extends('league_admin')

@section('selfcss')
<link rel="stylesheet" href="{{url('/css/recommend.css')}}">
@endsection

@section('content')
<div class='row custom_row'>
    
    <div id='recommend_hot_box' class='col-md-12 col-sm-12 col-xs-12'>
        
        <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">推薦商品表單</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->

  
            <form role="form" action="{{url('/league_module_recommend_recommend_act')}}" method="post">

                {{ csrf_field() }}

                <div class="box-body ">
                    <div class="form-group ">
                        <label for="custom_hot">自訂推薦商品</label>
                        <textarea class="form-control" rows="5" placeholder="自訂推薦商品,請在此輸入商品貨號,一個貨號一行" id='custom_hot' name='custom_hot' >@foreach( $HotSet['custom_sets'] as $custom_setk => $custom_set ){!!$custom_set."\n"!!}@endforeach</textarea>
                        @if ($errors->has('custom_hot'))
                            @foreach( $errors->get('custom_hot') as $custom_hot_errork => $custom_hot_error)
                            <label id="custom_hot-error" class="form_invalid" for="custom_hot">{{ $custom_hot_error }}</label><br>
                            @endforeach
                        @endif  
                    </div>

                    <div class="form-group">
                        
                        <label id="able_label"> 排除分類 </label>
                        
                        @foreach ($Categorys as $Categoryk => $Category )
                        <div class="col-md-12 col-sm-12 col-xs-12 CategoryGrp" >

                            <input type="checkbox" class="CustomCheckbox1 RootCategory" child="pcat_{{$Category['rcat']}}" name="cats[]" id="cat_{{$Category['rcat']}}" value="{{$Category['rcat']}}"
                            @if( in_array($Category['rcat'] , $HotSet['avoid_cat']) )
                            checked
                            @endif
                            >
                            <label for="cat_{{$Category['rcat']}}" >{{$Category['rcat_name']}}<span></span></label>

                        </div>
                            @foreach ($Category['child'] as $childk => $child )

                            <div class="col-md-2 col-sm-6 col-xs-6">                        
                                <input type="checkbox" class="CustomCheckbox1 pcat_{{$Category['rcat']}} ChildChk" parent_id="pcat_{{$Category['rcat']}}" name="cats[]" id="cat_{{$child['ccat']}}" value="{{$child['ccat']}}"
                                @if( in_array($child['ccat'] , $HotSet['avoid_cat']) )
                                checked
                                @endif
                                >
                                <label for="cat_{{$child['ccat']}}">{{$child['ccat_name']}}<span></span></label>
                            </div>

                            @endforeach
                        @endforeach

            

                </div>                    
                </div>
                

                
                <!-- /.box-body -->

              <div class="box-footer">
                <button type="submit" class="btn btn-primary">確定</button>
              </div>
            </form>
        </div>

    </div>

</div>
@endsection

@section('selfjs')
<script type="text/javascript">

$(function(){

    $(".RootCategory").change(function() {
        
        var AffectClass = $(this).attr('child');
         
        if( $(this).is(':checked') ) {

            $("."+AffectClass).prop("checked", true);
        
        }else{

            $("."+AffectClass).prop("checked", false);
        }

    }); 
    
    $(".ChildChk").change(function(){
        
        var Parent_id = $(this).attr('parent_id');

        var Allleng = $("."+Parent_id).length;

        var Chkleng = $("."+Parent_id+':checked').length;

        if( Allleng == Chkleng ){
            
            $(".RootCategory[child="+Parent_id+"]").prop("checked", true);
        }else{
            $(".RootCategory[child="+Parent_id+"]").prop("checked", false);
        }
    });

})


</script>
@endsection