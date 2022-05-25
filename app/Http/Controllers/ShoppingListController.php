<?php
declare(strict_types=1);
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShoppingListRegisterPostRequest;
use App\Models\Shopping_list as Shopping_listModel;
use App\Models\Completed_shopping_list as Completed_shopping_listModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShoppingListController extends Controller
{
    /**
     * トップページ を表示する
     * 
     * @return \Illuminate\View\View
     */
    public function list()
    {
        // 1Page辺りの表示アイテム数を設定
        $per_page = 15;
        
        // 一覧の取得
        $list = Shopping_listModel::where('user_id', Auth::id())
                                  ->orderBy('name', 'ASC')
                                  ->paginate($per_page);
                                  //->get();
/*
  $sql = Shopping_listModel::where('user_id', Auth::id())
                           ->orderBy('name', 'DESC')
                           ->toSql();
//echo "<pre>\n"; var_dump($sql, $list); exit;
*/
        //
        return view('shopping_list.list',['list' => $list]);
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
    
    /**
     * 「単一のタスク」Modelの取得
     */
    protected function getShopping_listModel($task_id)
    {
        // task_idのレコードを取得する
        $task = Shopping_listModel::find($task_id);
        if ($task === null) {
            return redirect('/shopping_list/list');
        }
        // 本人以外のタスクならNGとする
        if ($task->user_id !== Auth::id()) {
            return redirect('/shopping_list/list');
        }
        
        //
        return $task;
    }
    
    /**
     * 削除処理
     */
    public function delete(Request $request, $task_id)
    {
        // task_idのレコードを取得する
        $task = $this->getShopping_listModel($task_id);

        // タスクを削除する
        if ($task !== null) {
            $task->delete();
            $request->session()->flash('front.ShoppingList_delete_success', true);
        }

        // 一覧に遷移する
        return redirect('/shopping_list/list');
    }
    
    /**
     * 購入の完了
     */
    public function complete(Request $request, $task_id)
    {
        /* 買うものを完了テーブルに移動させる */
        try {
            // トランザクション開始
            DB::beginTransaction();

            // task_idのレコードを取得する
            $task = $this->getShopping_listModel($task_id);
            if ($task === null) {
                // task_idが不正なのでトランザクション終了
                throw new \Exception('');
            }

            // ShoppingList側を削除する
            $task->delete();
//var_dump($task->toArray()); exit;

            // completed_ShoppingList側にinsertする
            $dask_datum = $task->toArray();
            unset($dask_datum['created_at']);
            unset($dask_datum['updated_at']);
            $r = Completed_shopping_listModel::create($dask_datum);
            if ($r === null) {
                // insertで失敗したのでトランザクション終了
                throw new \Exception('');
            }
//echo '処理成功'; exit;

            // トランザクション終了
            DB::commit();
            // 完了メッセージ出力
            $request->session()->flash('front.ShoppingList_completed_success', true);
        } catch(\Throwable $e) {
//var_dump($e->getMessage()); exit;
            // トランザクション異常終了
            DB::rollBack();
            // 完了失敗メッセージ出力
            $request->session()->flash('front.ShoppingList_completed_failure', true);
        }
        
        // 一覧に遷移する
        return redirect('/shopping_list/list');
    }
}