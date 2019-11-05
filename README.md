# wp_base_theme

## これは何？

* WordPressテーマ
* かといって簡単着せ替え目的ではない
* テーマを自作するためのベース
* かといっていわゆるベース用のテーマというわけではない
* viewとルーティング直結の簡易フレームワークのようなもの
* ルール少なめ
* WordPressを使って普通のサイトを今すぐに作ってほしい。という案件に向いてる
* WordPressではお約束のsingle.phpとかcategory.phpとかを全く使わない
* 当テーマを使う意味は薄れるがsingle.phpとかcategory.phpとかを使ってもよい
* いわゆるテンプレート階層ルールを意識しないので、WP REST APIでページをチャチャッと作る感覚に近い
* でもWP REST APIとは違い、そこそこ複雑で本格的な構成のサイトを作るのにも向いてるはず
* [こういうテンプレート](base/tpl/sample)を作ることができる
* WordPressのテンプレート階層ファイルとして利用されることが少ないindex.phpが当テーマでは重要。中身は１行しかないけど
* URLルーティングでテンプレートファイルと紐付けるため、ページごとに自由にテンプレートを割り当てることができる
* あまり意味ないがWordPressのテンプレートタグをひとつも含まない普通のhtmlファイルを返すことも可
* echoをバッファにスタックして最後にまとめて出力するため、ブラウザに表示する直前に出力を自由に加工できる
* is_home()・is_single()など既存の判定処理を使ったルーティングを混在させてもいい
* 既存の静的htmlページとの共存が簡単
* もちろん `.htaccess`はデフォルトのままでok
* よく観察すると分かっていただけると思うが、当テーマ自体には高度な仕込みはない。「WordPressをこんなふうに使ってもいいのではないか？」を、実際に動作するスクリプトで表現するとこうなった
* 名前を募集中。名前を考えるのが苦手なので

## テンプレート実例

```html
<!doctype html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title><?php echo post('post_title'); ?></title>
    <meta name="description" content="<?php echo post('post_excerpt'); ?>">
</head>
<body>
    <?php echo do_shortcode(post('post_content')); ?>
</body>
</html>
```

## 使える関数

https://github.com/yama/wp_base_theme/blob/master/base/functions.inc.php

利用必須ではないが、上記にいくつか関数を用意している。分かりやすい関数名のヘルパーを使って1行か2行書くだけで解決してしまうLaravel感を意識している。

### post($key=null, $default=null)

記事のタイトルや本文などを取得。

### posts($args=array('post_status'=>'publish'))

複数の記事を取得。ほぼWP_Queryのエイリアス。

### datef($format, $datetime=null, $default='')

投稿日時を任意のフォーマットで出力。フォーマットはdate()方式・strftime()方式どちらも使えて、フォーマット文字列に `%` が含まれるかどうかで判定する。値がない場合に1970-01-01にならないよう、デフォルト値を指定できる。

### route($route)

後述。

### url()

ルーティング記述で$_SERVER\['REQUEST_URI'\]をたくさん書くと圧迫感を感じるため簡単にした。理由はそれだけ。でもroute()関数を作ったおかげで、今はそれほど恩恵はない。

## 処理の流れ

https://github.com/yama/wp_base_theme/blob/master/base/functions.php

まず functions.phpにリーチするが、主に関数の読み込みと出力のバッファ処理を行なうだけ。バッファ処理も必須ではないので、ほぼ何もしてない。

https://github.com/yama/wp_base_theme/blob/master/base/index.php

routes.phpが返す結果を出力するだけで、他には何もしない。single.phpなど、WordPressのテンプレート階層を構成する上で意味のあるファイル名を持つファイルがないため、上記のindex.phpが全てのリクエストを受け取る。

https://github.com/yama/wp_base_theme/blob/master/base/routes.php

routes.php でURLとテンプレートファイルの紐付けを行なう。route()関数の値の末尾に `*` がついている場合は前方一致でURLを判定する。ついていない場合は完全一致。ただし `/` を指定した場合は `/index.html` かどうかも判定する。

これだけなので、処理は超軽い。けど、ob_set()関数の中身に自前でキャッシュ処理を組み込めばさらに軽量化できる。

ob_set()関数は、BladeやSmartyなどのテンプレートエンジンを組み込むのにも向いている。

当テーマ同梱のサンプルのような書き方に慣れない場合は、普通にWordPressのテンプレート流儀に則った書き方をしてもいい。

## Tips

静的htmlファイルを持つ既存のディレクトリのトップ(index.html相当)をWordPress化して記事の一覧を表示したい場合は、

```php
<?php
include('../index.php');
```

上記内容の `index.php` を作成して任意のサブディレクトリに配置すると、WordPressのエンドポイントとして動作する。既存のindex.htmlはテーマフォルダ内に移動し一部を動的化した上でテンプレートとして使う。こうすることで、静的htmlファイルで構成されたサイトの一部を必要に応じていくつでもWordPress化できる。

## 注意点

当テーマ自体の問題ではないが、当テーマを使うと複雑な構成のサイトを作りやすいため、陥りやすい罠がある。

[https://ja.wordpress.org/support/topic/カテゴリーの重複スラッグ設定](https://ja.wordpress.org/support/topic/%E3%82%AB%E3%83%86%E3%82%B4%E3%83%AA%E3%83%BC%E3%81%AE%E9%87%8D%E8%A4%87%E3%82%B9%E3%83%A9%E3%83%83%E3%82%B0%E8%A8%AD%E5%AE%9A/)

WordPressは基本的に、同じ名前のカテゴリーを複数作ることができない。親カテゴリーが違っていても重複不可。処理面をさんざん作り込んでから気づきがちだが、場合によっては素直にマルチサイト設定で運用する。
