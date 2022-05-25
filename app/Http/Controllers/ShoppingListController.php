<?php
declare(strict_types=1);
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShoppingListRegisterPostRequest;
use App\Models\Shopping_list as Shopping_listModel;
use Illuminate\Support\Facades\Auth;

class ShoppingListController extends Controller
{
    /**
     * トップページ を表示する
     * 
     * @return \Illuminate\View\View
     */
    public function list()
    {
        return view('shopping_list.list');
    }
    
    /**
     * 買うものの新規登録
     */
    public function register(ShoppingListRegisterPostRequest $request)
    {
        // validate済みのデータの取得
        $datum = $request->validated();
        // user_id の追加
        $datum['user_id'] = Auth::id();
         // テーブルへのINSERT
        try {
            $r = Shopping_listModel::create($datum);
//var_dump($r); exit;
        } catch(\Throwable $e) {
            // XXX 本当はログに書く等の処理をする。今回は一端「出力する」だけ
            echo $e->getMessage();
            exit;
        }
        
        // 登録成功
        $request->session()->flash('front.ShoppingList_register_success', true);
        
        //
        return redirect('/shopping_list/list');
    }
}