@extends('layout')

{{-- タイトル --}}
@section('title')(詳細画面)@endsection

{{-- メインコンテンツ --}}
@section('contets')
        <h1>「買うもの」の登録（未実装）</h1>
            <form action="./top.html" method="post">
                「買うもの」名:<input><br>
                <button>「買うもの」を登録する</button>
            </form>

        <h1>「買うもの」の一覧（未実装）</h1>
        <a href>購入済み「買うもの」一覧（未実装）</a><br>
        <table border="1">
        <tr>
            <th>登録日
            <th>「買うもの」名
        <tr>
            <td>2022/01/01
            <td>豚肉
            <td><form action="./top.html"><button>完了</button></form></a>
            <td><form action="./top.html"><button>削除</button></form></a>
        </table>
        <!-- ページネーション -->
        現在 1 ページ目<br>
        <a href="./top.html">最初のページ（未実装）</a> / 
        <a href="./top.html">前に戻る（未実装）</a> / 
        <a href="./top.html">次に進む（未実装）</a>
        <br>
        <hr>
        <menu label="リンク">
        <a href="./index.html">ログアウト（未実装）</a><br>
        </menu>
@endsection