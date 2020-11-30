<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use App\Cus_lib\Lib_common;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
/*
|--------------------------------------------------------------------------
|
|--------------------------------------------------------------------------
|
*/
class LeagueArticleController extends Controller
{
    
    // 列表
    public function articleList( Request $request ){

        $LeagueId = $request->session()->get('user_id');

        $articles = self::get_articles($LeagueId);

        return view('/league_article_list',['articles'=>$articles]);
    }




    // 編輯
    public function league_article_edit ( Request $request ){
        
        $LeagueId = $request->session()->get('user_id');  
        
        $passArray = [];

        if( empty($request->id) )
        {   
            $PageTitle = '新增文章';
            $act = "new";
            $tags = '';
        }
        else
        {   
            // 如果文章不屬於目前加盟商，就直接跳出不執行
            if( !self::_chkArticleHolder( $request->id , $LeagueId ) )
            {
                
                $league_message =   [ '0',
                                      "文章編號不屬於此帳號，請勿嘗試非法操作",
                                      [ ['operate_text'=>'回文章列表','operate_path'=>'/league_article'] ],
                                      3
                                ];

                $request->session()->put('league_message', $league_message);            
        
                return redirect('/league_message');                
            }

            $article = DB::table('xyzs_league_article as a')
                       ->where('a.id',$request->id)->where('a.league_id',$LeagueId)
                       /*
                       ->leftJoin('xyzs_league_article_hash as ats', 'a.id', '=', 'ats.article_id')
                       ->leftJoin('xyzs_league_hash as ls', 'ats.hash_id', '=', 'ls.id')
                       */
                       ->first();
            
            $article = (array)$article;
            
            // 取出tag 
            $tags = DB::table('xyzs_league_article_hash as ah')
                  ->leftjoin('xyzs_league_hash as h', 'ah.hash_id','=','h.id')
                  ->where('ah.article_id',$request->id)
                  ->get();

            $tags = json_decode( $tags , true );
                        
            $tagsArray = [];

            foreach ( $tags as $tagk => $tag ) {
                
                array_push( $tagsArray , $tag['hashtag'] );
            }
            
            $tags = implode( ',' , $tagsArray );
             
            $passArray['article'] = $article;

        	$PageTitle = '編輯文章';

        	$act = "edit";

        }
        
        $passArray['PageTitle'] = $PageTitle;
        $passArray['act']       = $act; 
        $passArray['tags']      = $tags;
        $passArray['id']        = $request->id;


        return view('/league_article_edit' , $passArray );
    }




    // 編輯實作
    public function league_article_edit_act( Request $request ){
        
        // 拆解標籤
        $hashTags = explode(',' , $request->hashtags );
        
        // 如果標籤不存在標籤資料表 (xyzs_league_hash) 中 , 就立即新增
        foreach ($hashTags as $hashTagk => $hashTag ) {

            if( DB::table('xyzs_league_hash')->where('hashtag', '=', $hashTag)->count() == 0 )
            {
                DB::table('xyzs_league_hash')->insert(['hashtag' => $hashTag]);
            }
        }
        
        // 取出所有 hash tag 的 id
        $hashIds = DB::table('xyzs_league_hash')->whereIn('hashtag' , $hashTags)->select('id')->get();
        
        // 本次關聯文章的hashtag
        $hashIds = json_decode($hashIds,true);

        // 會員id
        $LeagueId = $request->session()->get('user_id');
        
        // 新增
        if( !isset( $request->id ) )
        {       
        	    //Validator::setAttributeNames(['articletitle'=>'化吉丸']);
                Validator::make($request->all(), [
                    'articletitle' => 'required',
                    'article'      => 'required',
                    'sort'         => 'required|integer|max:99|min:0'
                ],
                [
                    'required' => ':attribute 為必填欄位 ， 請確認填寫 。',
                    'integer'  => ':attribute 必須為整數',
                    'max'      => ':attribute 不可大於 :max',
                    'min'      => ':attribute 不可小於 :min',
                ],
                [
                    'articletitle'=>'文章標題',
                    'article'     =>'文章內容',
                    'sort'        =>'文章排序'
                ]
                )->validate();

                

                DB::beginTransaction();
                
                try { 
                    
                    $articleId = DB::table('xyzs_league_article')
                                 ->insertGetId(
                                 ['league_id' => $LeagueId, 
                                  'title'     => $request->articletitle,
                                  'article'   => $request->article,
                                  'sort'      => $request->sort,
                                  'edit_time' => Lib_common::_GetGMTTime()
                                 ]);
                    

                    DB::table('xyzs_league_article_hash')->where('article_id', '=', $articleId )->delete();

                    foreach ($hashIds as $hashIdk => $hashId) {
                        
                        DB::table('xyzs_league_article_hash')->insert(['article_id' => $articleId, 'hash_id' => $hashId['id'] ]);
                    }

                    DB::commit();

                    $league_message =   [ '1',
                                          "文章新增成功",
                                          [ ['operate_text'=>'回文章列表','operate_path'=>"/league_article"] ],
                                          3
                                        ];
   
                    $request->session()->put('league_message', $league_message);  

                } catch (\Exception $e) {

                    DB::rollback();
            
                    $league_message =   [ '0',
                                          "文章新增失敗",
                                          [ ['operate_text'=>'回文章列表','operate_path'=>'/league_sort_center'] ],
                                          3
                                        ];

                    $request->session()->put('league_message', $league_message);            
             

                }
        }   
        // 編輯
        else
        {     
            Validator::make($request->all(), [
                'articletitle' => 'required',
                'article'      => 'required',
                'sort'         => 'required|integer|max:99|min:0'
            ],
            [
                'required' => ':attribute 為必填欄位 ， 請確認填寫 。',
                'integer'  => ':attribute 必須為整數',
                'max'      => ':attribute 不可大於 :max',
                'min'      => ':attribute 不可小於 :min',
            ],
            [
                'articletitle'=>'文章標題',
                'article'     =>'文章內容',
                'sort'        =>'文章排序'
            ]
            )->validate();     


            DB::beginTransaction();
                
            try 
            {   
                // 更新文章內容          
                DB::table('xyzs_league_article')
                ->where('id', $request->id)
                ->where('league_id' , $LeagueId )
                ->update(
                ['league_id' => $LeagueId, 
                 'title'     => $request->articletitle,
                 'article'   => $request->article,
                 'sort'      => $request->sort,
                 'edit_time' => Lib_common::_GetGMTTime()
                ]
                );
                
                // 移除全部關聯標籤 
                DB::table('xyzs_league_article_hash')->where('article_id', '=', $request->id )->delete();
                
                // 重新產生關聯標籤
                foreach ($hashIds as $hashIdk => $hashId) {
                    DB::table('xyzs_league_article_hash')->insert(
                    ['article_id' => $request->id ,
                     'hash_id' => $hashId['id'] 
                    ]);
                
                }

                DB::commit();
                
                $league_message =   [ "1",
                                      "文章編輯成功",
                                      [ ['operate_text'=>'回文章列表','operate_path'=>"/league_article"] ],
                                          3
                                    ];
   
                $request->session()->put('league_message', $league_message);                  

            } 
            catch (\Exception $e) 
            {
                DB::rollback();
            
                $league_message =   [ '0',
                                      "文章編輯失敗",
                                      [ ['operate_text'=>'回文章列表','operate_path'=>'/league_sort_center'] ],
                                      3
                                    ];

                $request->session()->put('league_message', $league_message);  
            }

        }

        return redirect('/league_message');  
    }
    



    // 加盟會員文章刪除
    public function league_article_del( Request $request )
    {
       // 當下加盟會員id
        $LeagueId = $request->session()->get('user_id');
        
        // 判斷類別推薦是否屬於當下加盟會員
        $validator = Validator::make($request->all(), 

        [ 'article_del_id'=> 
          ['required',
            Rule::exists('xyzs_league_article','id')->where(function ($query) use ($LeagueId) {
                $query->where('league_id', $LeagueId);
            }),
          ],                       
        ]
        ,
        ['article_del_id.required'=>'移除過程有誤，請重新整理後再嘗試',
         'article_del_id.exists'  =>'此文章不存在，或者無權限刪除',
        ]
        );

        // 驗證成功,可執行刪除
        if ($validator->passes())
        {
            if( DB::table('xyzs_league_article')->where('id', '=', $request->article_del_id)->where('league_id','=',$LeagueId)->delete() )
            {
                return response()->json(['success'=>'刪除成功']);
            }
            else
            {
                return response()->json(['error'=>['移除過程有誤，請重新整理後再嘗試']]);
            }
        }
        // 驗證失敗，回傳錯誤訊息
        else
        {   
            return response()->json(['error'=>$validator->errors()->all()]);
        }
    }




    private static function get_articles( $league_id = '')
    {   
        if( !empty($league_id) )
        {
            $retrunDatas = DB::table('xyzs_league_article')->select('id','title','sort','edit_time')->get();

            $retrunDatas = json_decode( $retrunDatas , true );
        }
        else
        {
            $retrunDatas = [];
        }

        return $retrunDatas;
    }



    /*
    |--------------------------------------------------------------------------
    | ajax 
    |--------------------------------------------------------------------------
    |
    */
    public function league_article_tag( Request $request )
    {
        //var_dump($request->all());
        //echo json_encode( ["four", "five", "six"] );
        header('Content-Type: application/json');

        
        $returnDatas = [] ;

        if( !empty( $request->term ) )
        {
            $tagDatas = 
                DB::table('xyzs_league_hash')
                ->where('hashtag', 'like', "%{$request->term}%")
                ->get();

            $tagDatas = json_decode( $tagDatas , true );
            
            //var_dump($tagDatas);

            if( COUNT($tagDatas) > 0 )
            {   
                foreach ($tagDatas as $tagDatak => $tagData) {
                    array_push( $returnDatas , $tagData['hashtag'] );
                }
                
            }


        }

        echo json_encode( ['suggestions'=>$returnDatas] );
        //echo json_encode( ['suggestions'=>['Indaaaia', 'Pakistan', 'Nepal', 'UAE', 'Iran', 'Bangladesh']] );

        //return new JsonResponse(['India', 'Pakistan', 'Nepal', 'UAE', 'Iran', 'Bangladesh']);

    }




    /*
    |-------------------------------------------------------------------------- 
    | 檢測文章是否屬於該加盟會員
    |--------------------------------------------------------------------------
    |
    |
    */
    public function _chkArticleHolder( $_articleId , $_leagueId )
    {   
        if( DB::table('xyzs_league_article')->where('id',$_articleId)->where('league_id',$_leagueId)->exists() )
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }
}
