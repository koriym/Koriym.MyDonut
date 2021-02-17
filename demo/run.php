<?php

// TTLが0の場合のキャッシュ
use Koriym\MyDonut\MyDonut;

update_cache: {
    // ドーナツ設定
    // post?id=1はcomment?id=10を含み、comment?id=10はstar?id=100を含んでいる
    $postDonut = new MyDonut('post', new DonutId(['id' => 1])); // id=1のpost
    $commentDonut = new MyDonut('comment', new Donut(['id' => 10])); // id=10のcomment
    $postDonut->embed($commentDonut); // post?id=1にcomment?id=10を埋め込む
    $starDonut = new Donut('star', new DonutId(['id' => 100])); // id=100のスター
    $commentDonut->embed($starDonut);// comment?id=10にstart?id=100を埋め込む

    // // post?id=1のドーナツを登録
    $myDonut = new MyDonut;
    $myDonut->add($postDonut);

    $postTag0 = $postDonut->etag(); // post?id=1のetag
    $myDonut->isModified($postTag0); // false 変更なし

    // post?id=1のドーナツを更新
    $postTag1 = $myDonut->update($postDonut); //　新しいetagを取得
    assert($myDonut->isModified($postTag0)); // 更新されたので古いetagは無効
    assert(!$myDonut->isModified($postTag1)); // 新しいetagは有効

    //　comment?id=10のドーナツを更新
    $postTag2 = $myDonut->update($commentDonut);
    assert($myDonut->isModified($postTag1)); // comment?id=10の更新によりpost?id=1のetagも更新されている

    //　star?id=100のドーナツを更新
    $myDonut->update($starDonut); //　start?id=100のドーナツの更新により、comment?id=10とpost?id=1のetagも更新される
    assert(!$myDonut->isModified($postTag2));
}
