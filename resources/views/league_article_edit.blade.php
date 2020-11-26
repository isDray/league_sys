@extends('league_admin')

@section('selfcss')
<link href="{{url('/toastr-master/build/toastr.min.css')}}" rel="stylesheet"/>
<link rel="stylesheet" href="{{url('/css/league_module_stack.css')}}">
<link rel="stylesheet" href="{{url('/suggestags/css/amsify.suggestags.css')}}">
<style type="text/css">
.color_label{
    width: 20px;
    height: 20px;
    border: 1px solid #4c4c4c;
    margin-bottom: 0px;
    border-radius: 4px;
}
</style>
@endsection


@section('content')

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class='row custom_row'>
    
    <div id='recommend_stack_box' class='col-md-12 col-sm-12 col-xs-12'>
	    
	    <div class='box box-primary'>
            
            <div class="box-header with-border">
              
              <h3 class="box-title">@if( empty($id))新增@else編輯@endif文章</h3>

            </div>

	        <form role="form" action="{{url('/league_article_edit_act')}}" method="post">
            {{ csrf_field() }}

            <div class="box-body ">

                <div class='col-md-7 col-sm-12 col-xs-12'>
                    <label for="articletitle">文章標題</label>
                    <input type="text" class="form-control" id="articletitle" name="articletitle" placeholder="請輸入文章標題" value="@if(isset($article)) {{$article['title']}}@endif" >
                </div>

                <div class='col-md-7 col-sm-12 col-xs-12'>
                    <label for="articletitle">文章標籤</label>
                    <input type="text" class="form-control" name="hashtags" value="{{$tags}}"/>

                </div>                

                <div class='col-md-7 col-sm-12 col-xs-12'>
                    <label for="article">文章內容</label>
                    <textarea name='article' id='article' class='article' >@if( isset($article) ) {{$article['article']}} @endif</textarea>
                </div>
                
                <div class='col-md-7 col-sm-12 col-xs-12'>
                    <label for="article">文章排序(由小到大)</label>
                    <input type="number" class="form-control" id="sort" name="sort" value="@if(isset($article)){{$article['sort']}}@else 0 @endif" min="0">
                </div>                

                <div class='col-md-12 col-sm-12 col-xs-12'>

                    @if( !empty($id) )
                    <input type='hidden' value='{{$id}}' name='id'>
                    @endif
                    <input type='submit' value='確定' class='btn btn-primary'>
                </div>
            </div>

            </form>

	    </div>

    </div>
</div>
@endsection

@section('selfjs')
<script src="{{url('/vendor/unisharp/laravel-ckeditor/ckeditor.js')}}"></script>
<script src="{{url('/suggestags/js/jquery.amsify.suggestags.js')}}"></script>
<script>
    $(function(){
        
        CKEDITOR.replace( 'article' );
        
        $('input[name="country"]').amsifySuggestags({
            suggestionsAction : 
            {
                url: "{{url('/league_article_tag')}}",


                success: function(data) {
                    console.info('success');
                },
            },
            
        });
        /*
        $('input[name="hashtags"]').amsifySuggestags({
             suggestions: ['Black', 'White', 'Red', 'Blue', 'Green', 'Orange']
        });
*/
        /*
        $('input[name="hashtags"]').amsifySuggestags({

            suggestionsAction : {
                
                suggestions: ['India', 'Pakistan', 'Nepal', 'UAE', 'Iran', 'Bangladesh'],


           /* timeout: -1,
            minChars: 2,
            minChange: -1,
            /*type: 'GET',
            url: "{{url('/league_article_tag')}}",
            term: "something",
            beforeSend : function() {
                //console.info('beforeSend');
            },
            success: function(data) {
                //console.log(data);
                //console.info('success');
            },
            error: function() {
                //console.info('error');
            },
            complete: function(data) {
                //console.info('complete');
            }
            }
        });*/        
        
            $('input[name="hashtags"]').amsifySuggestags({

                suggestionsAction : {
                    timeout: -1,
                    minChars: 1,
                    minChange: -1,
                    delay: 500,
                    type: 'GET',
                    //term: 'ABC',

                    url: "{{url('/league_article_tag')}}",
                    beforeSend : function() {
                        console.info('beforeSend');
                    },
                    success: function(data) {
                       
                        console.log( data );

                        /*suggestions  =  ["four", "five", "six"]; 
                      
                        $('input[name="hashtags"]').amsifySuggestags(suggestions, 'refresh');*/

                        //console.log( data );
                        //console.info('success');
                        //suggestions: ['Black', 'White', 'Red', 'Blue', 'Green', 'Orange'];
                        //suggestions : JSON.parse(data);

                        //console.log( data );

                        /*
                        newData = JSON.parse( data );
                        consoloe.log( newData );
                        */
                        //$('input[name="hashtags"]').amsifySuggestags(), 'refresh');
                        //amsifySuggestags.refresh();
                    },
                    error: function() {
                        console.info('error');
                    },
                    complete: function(datas) {
                         //suggestions : JSON.parse(data);
                        //console.info('complete');
             //suggestions: ['Black', 'White', 'Red', 'Blue', 'Green', 'Orange'];
                    }
                }
            
            });
         
    });
    
</script>
@endsection