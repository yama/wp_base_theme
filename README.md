# wp_base_theme

## これは何？

* WordPressテーマ
* かといって簡単着せ替え目的ではない
* テーマを自作するためのベース
* かといっていわゆるベース用のテーマというわけではない
* viewとルーティング直結の簡易フレームワークのようなもの
* ルール少なめ
* WordPressを使って普通のサイトを今すぐに作ってほしい。という案件に向いてる
* WordPressではお約束のsingle.phpとかheader.phpとかを全く使わない
* いわゆるテンプレート階層ルールを意識しないので、WP REST APIでページをチャチャッと作る感覚に近い
* でもそこそこ複雑で本格的なサイトを作るのにも向いてるはず
* [こういうテンプレート](base/tpl/sample/detail.html)を作ることができる
* index.phpが重要。中身は１行しかないけど。
* URLルーティングでテンプレートファイルと紐付けるため、ページごとに自由にテンプレートを割り当てることができる
* あまり意味ないがWordPressのテンプレートタグをひとつも含まない普通のhtmlファイルを返すことも可
* echoをいったんバッファにスタックするため、出力を自由に加工できる
* is_home()・is_single()など既存の判定処理を使ったルーティングを混在させてもいい
* 既存の静的htmlページとの共存が簡単
* もちろん `.htaccess`はデフォルトのままでok

## 使える関数

https://github.com/yama/wp_base_theme/blob/master/base/functions.inc.php

利用必須ではないが、上記にいくつか関数を用意している。分かりやすい関数名のヘルパーを使って1行か2行書くだけで解決してしまうLaravel感を意識している。

### post($key=null, $default=null)

記事のタイトルや本文などを取得。

### posts($args=array('post_status'=>'publish'))

複数の記事を取得。ほぼWP_Queryのエイリアス。

### route($route)

後述。

### url()

$_SERVER\['REQUEST_URI'\]をたくさん書くと圧迫感を感じるため簡単にした。理由はそれだけ。でもroute()関数を作ったおかげで、今はそれほど恩恵はない。

## 処理の流れ

https://github.com/yama/wp_base_theme/blob/master/base/functions.php

まず functions.phpにリーチするが、主に関数の読み込みと出力のバッファ処理を行なうだけ。

https://github.com/yama/wp_base_theme/blob/master/base/index.php

そしてここが重要。index.phpを処理する。やってることはroutes.phpの結果を表示しているだけで、他には何もしない。single.phpなど、WordPressのテーマを構成する上で意味のあるファイル名を持つファイルがないため、index.phpが全てのリクエストを受け取る。

https://github.com/yama/wp_base_theme/blob/master/base/routes.php

routes.php でURLとテンプレートファイルの紐付けを行なう。route()関数の値の末尾に `*` がついている場合は前方一致でURLを判定する。ついていない場合は完全一致。ただし `/` を指定した場合は `/index.html` かどうかも判定する。

これだけなので、処理は超軽い。けど、ob_set()関数の中身に自前でキャッシュ処理を組み込めばさらに軽量化できる。

必要であれば、普通にWordPressのテンプレートの流儀に則った書き方をしてもいい。
