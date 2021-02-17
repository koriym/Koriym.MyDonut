# koriym/my-donut

Cache management framework for content with embed content

## Getting Started

キャッシュ管理される特定のコンテンツをDonutと呼びます。
Donutは名前とVaryを指定して作成します。Varyは単数または複数の引数でDonutを特定します。

```php
// id=1のpostドーナツを作成
$postDount = new Donut('post', ['id' => 1]);
```

次に例としてpost?id=1はcomment?id=10を含み、comment?id=10はstar?id=100を含んでいるDonutを作成します。

```php
// ドーナツ設定
$postDonut = new Donut('post', new Vary(['id' => 1])); // id=1のpost
$commentDonut = new Donut('comment', new Donut(['id' => 10])); // id=10のcomment
$postDonut->addHole($commentDonut); // post?id=1にcomment?id=10を埋め込む
$starDonut = new Donut('star', new Vary(['id' => 100])); // id=100のスター
$commentDonut->addHole($starDonut);// comment?id=10にstart?id=100を埋め込む
```

このDonutを管理します。

```php
// post?id=1のドーナツを登録
$myDonut = new MyDonut; // Donutリポジトリ＆マネージャー
$myDonut->add($postDonut);
unset($myDonut); // $myDonutはDBで永続化されます。
```

`isModified()`はDonutに変更がないかを返します。

```php
$postTag0 = $postDonut->etag(); // post?id=1のetag
$myDonut->isModified($postTag0); // false 変更なし
```

登録した依存関係に従い、Donutが更新された時はそれを含む親のDonutのEtagも再生成します。
更新されたDonutは`stale()`メソッドで古くなった事を伝えます。

```php
// post?id=1のドーナツを更新
$postTag1 = $myDonut->stale($postDonut); //　$postDonutが古くなった
assert($myDonut->isModified($postTag0)); // 古いetagは無効に
assert(!$myDonut->isModified($postTag1)); // 新しいetagは有効

//　comment?id=10のドーナツを更新
$postTag2 = $myDonut->stale($commentDonut);
assert($myDonut->isModified($postTag1)); // comment?id=10の更新によりpost?id=1のetagも更新されている

//　star?id=100のドーナツを更新
$myDonut->stale($starDonut); //　start?id=100のドーナツの更新により、comment?id=10とpost?id=1のetagも更新される
assert(!$myDonut->isModified($postTag2));
```

TTLのあるDonutは`TtlDonut`で生成します。

60秒の生存期間を持つ`ad` Donutを `$postDonut`に埋め込みます。`

```php
$adDonut = new TtlDonut('ad', new Vary(['id' => 1]), 60);
$postDonut->embed($adDonut);
```

時刻をセットすると、生存期限の切れたDonutのEtagは無効になります。
生存期限はDonutツリーを辿って最も短いものが適用されます。

```php
$myDonut->setClock(new DateTime);
$myDonut->isModified($postTag2); // true or false
```

キャッシュのできないDonutを埋め込まれると、埋め込まれたDonutのEtagはいつも空です。
`isModified('')`はtrueになります。

```php
$adDonut = new TtlDonut('ad', new Vary(['id' => 1]), 0);
$postDonut->embed($adDonut);
$postDonut->hasEtag() // false
```

Donutはコンテンツも加えることができます。

```php
new Donut('post', new Vary(['id' => 1]), $donutContent);
```

Donutのコンテントは依存コンテントを文字列評価可能なオブジェクトとして埋め込ています。

```php
// テンプレートイメージ
$donutContentTemplate = 'own content {$embedRequest1} {$embedRequest2}';
```
（この部分はBEARのResourceObjectと密結合してます）
全体を文字列評価して最終的な文字列コンテントを得ます。

```php
(string) $donutContent; // 'own content rev0 {ebmed1文字列} {$ebmed2文字列}';
// sprintf('own content {%s} {%s}', (string) $embedRequest1, (string) $embedRequest2);
```

埋め込まれた要素(ebmed1, embed2)のデータがstaleになればこのコンテントも再評価します。

```php
$donutContent = 'own content rev0 {$ebmed1} {$ebmed2}'; 
$donutContent = 'own content rev1 {$ebmed1} {$ebmed2}';
```

コンテンツを取得するにはgetで`Vary`を指定します。
キャッシュのメタ情報をコンテンツが取得できます。

```php
$cachedDonut = $myDonut->get(new Donut('post', new Vary(['id' => 1]));
$cachedDonut->heaedrs // ['max-age' => 103];
$cachedDonut->body // キャッシュ
```

`max-age`は生存期間です。１以上ならキャッシュがヒットしていることが分かります。
その他ユニットテストに必要なキャッシュ情報が取得できます

## 実装コンセプト

* EtagはTTLを持てない
* Etagを求めるときは常に子のEtagの生存確認
* 子は親を知らない

Etag
```
key: ‘parent_etag1’, value [‘child_etag1’, ‘child_etag2’ …]
```