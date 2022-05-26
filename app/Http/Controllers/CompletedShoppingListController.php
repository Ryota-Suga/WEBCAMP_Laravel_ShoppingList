<?php
declare(strict_types=1);
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Completed_shopping_list as Completed_shopping_listModel;
use Illuminate\Support\Facades\Auth;

class CompletedShoppingListController extends Controller
{
    /**
     * 一覧用の Illuminate\Database\Eloquent\Builder インスタンスの取得
     */
    protected function getListBuilder()
    {
        return Completed_shopping_listModel::where('user_id', Auth::id())
                     ->orderBy('name', 'ASC')
                     ->orderBy('created_at');
    }

    /**
     * 購入済み「買うもの」一覧を表示する
     * 
     * @return \Illuminate\View\View
     */
    public function list()
    {
        // 1Page辺りの表示アイテム数を設定
        $per_page = 15;

        // 一覧の取得
        $list = $this->getListBuilder()
                     ->paginate($per_page);
/*
$sql = $this->getListBuilder()
            ->toSql();
//echo "<pre>\n"; var_dump($sql, $list); exit;
var_dump($sql);
*/
        //
        return view('shopping_list.completed_list', ['list' => $list]);
    }
}