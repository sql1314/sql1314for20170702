<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;


// 使用缓存必须引入
use Cache;
class IndexController extends Controller
{
    /**
     * 显示文章列表
     * yhy
     * 923
     */
    public function index()
    {
        $articles = Cache::get('articles',[]);
        if($articles){
            exit('Nothing');
        }
        $html = '<ul>';
        foreach ($articles as $key => $varticle) {
            $html .= '<li><a href='.route('index.show',['post'=>$key]).'>'.$article['title'].'</li>';
        }
        $html .= '</ul>';

        return $html;
    }

    /**
     * 创建新文章表单页面
     * yhy
     * 923
     * http://laravel.io/index/create
     */
    public function create()
    {
        $postUrl = route('index.store');
        $csrf_field = csrf_field();
        $html = <<<CREATE
            <form action="$postUrl" method="POST">
                $csrf_field
                <input type="text" name="title"><br/><br/>
                <textarea name="content" cols="50" rows="5"></textarea><br/><br/>
                <input type="submit" value="提交"/>
            </form>
CREATE;
// CREATE;后面不能加注释
    return $html;

    }

    /**
     * 将新创建的文章存储到存储器
     * yhy
     * 923
     */
    public function store(Request $request)
    {
        $title = $request->input('title');
        $content = $request->input('content');
        $article = [
            'title' => trim($title),
            'content' => trim($content),
        ];

        $articles = Cache::get('articles',[]);
        if(!Cache::get('article_id')){
            Cache::add('article_id',1,60);
        }else{
            Cache::increment('article_id',1);
        }
        $articles[Cache::get('article_id')] = $article;
        Cache::put('articles',$articles,60);
        return redirect()->route('index.show',['article'=>Cache::get('article_id')]);
    }

    /**
     * 显示指定文章
     * yhy
     * 923
     * 访问http://laravel.io/index/create页面，填写表单，点击“提交”，保存成功后，页面跳转到详情页：
     */
    public function show($id)
    {
        $articles = Cache::get('articles',[]);
        if(!$articles || !$articles[$id])  exit('Nothing Found!');
        $article = $articles[$id];

        $editUrl = route('index.edit',['article_id'=>$id]);
        $html = <<<DETAIL
            <h3>{$article['title']}</h3>
            <p>{$article['content']}</p>
            <p>
                <a href="{$editUrl}">编辑</a>
            </p>
DETAIL;
        return $html;

    }

    /**
     * 显示编辑指定文章的表单页面
     * yhy
     * 928
     */
//     public function edit($article_id){
//         $articles = Cache::get('articles',[]);
//         if(!$articles || !$articles[$article_id]){
//             exit('Nothing Found');
//         }
//         $article = $articles[$article_id];
//         // dump($article);
//         $articleUrl = route('index.update',['article'=>$article_id]);
//         $articleUrl = '/index/update/'.$article_id;
//         // dump($articleUrl);die;
//         $csrf_field = csrf_field();
//         $html = <<<UPDATE
//             <form action="$articleUrl" method="POST">
//                 $csrf_field
//                 <input type="hidden" name="_method" value="PUT"/>
//                 <input type="text" name="title" value="{$article['title']}"></br></br>
//                 <textarea name="content" cols="50" rows="5">{$article['content']}</textarea></br></br>
//                 <input type="submit" value="提交"/>
//             </form>
// UPDATE;
//         return $html;


//     }
public function edit($id)
{
    $posts = Cache::get('articles',[]);
    if(!$posts || !$posts[$id])
        exit('Nothing Found！');
    $post = $posts[$id];

    $postUrl = route('index.update',['post'=>$id]);
    $csrf_field = csrf_field();
    $html = <<<UPDATE
        <form action="$postUrl" method="POST">
            $csrf_field
            <input type="hidden" name="_method" value="PUT"/>
            <input type="text" name="title" value="{$post['title']}"><br/><br/>
            <textarea name="content" cols="50" rows="5">{$post['content']}</textarea><br/><br/>
            <input type="submit" value="提交"/>
        </form>
UPDATE;
    return $html;

}


    /**
     * 在存储区中更新指定文章
     * yhy
     * 923
     * @param Request $request
     * @param int $id
     * @return Response
     */
    // public function update(Request $request, $id)
    // {
    //     $articles = Cache::get('articles',[]);
    //     if(!$articles || !$articles[$id]){
    //         exit('Nothing Found');
    //     }
    //     $title = $request->input('title');
    //     $content = $request->input('content');
    //     $articles[$id]['title'] = trim($title);
    //     $articles[$id]['content'] = trim($content);
    //     Cache::put('aticles',$articles,60);
    //     return redirect()->route('index.show',['article'=>Cache::get('article_id')]);

    // }

    public function update(Request $request, $id)
    {
        $articles = Cache::get('articles',[]);
        if(!$articles || !$articles[$id])
            exit('Nothing Found！');

        $title = $request->input('title');
        $content = $request->input('content');

        $articles[$id]['title'] = trim($title);
        $articles[$id]['content'] = trim($content);
        dump($articles);die;
        Cache::put('articles',$articles,60);
        return redirect()->route('index.show',['article'=>Cache::get('article_id')]);
    }


    /**
     * 从存储器中移除指定文章
     * @param int $id
     * @return Response
     * yhy
     */
    public function destory($id)
    {
        $articles = Cache::get('articles',[]);
        if(!$articles || $articles[$id]){
            exit('Nothing Deleted!');
        }
        unset($articles[$id]);
        Cache::decrement('article_id',1);
        return redirect()->route('index.index');

    }
}
