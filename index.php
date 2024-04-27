<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="料理研究家とマッチングすることによって、あなたらしい食生活を送るためのキュレーションサイトです。">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="img/favicon.ico">
    <link rel="stylesheet" href="https://unpkg.com/ress/dist/ress.min.css">
    <link rel="stylesheet" href="css/style.css">
    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Zen+Old+Mincho&display=swap" rel="stylesheet">

    <title>紡くっく | 食生活にわたしらしさを紡いでいく</title>
</head>

<body>
    <header id="header">
        <div class="inner wrapper">
            <h1 class="logo">
                <a href="index.php"><img src="./hp_img/logo.png" alt="紡くっくのロゴ"></a>
            </h1>
            <nav>
                <ul>
                    <li><a href="">わたしの料理研究家さんミッケ！</a></li>
                    <li><a href="">#夕飯なに食べる?</a></li>
                    <li><a href="">新着レシピ</a></li>
                    <li><a href="">人気レシピ</a></li>
                </ul>
            </nav>
        </div>
        <a class="contact" href="login_form.php">login</a>
    </header>

    <main>
        <section id="searchbox">
            <form method="post" action="searchbox.php">
                <div class="mainSearch">
                    <label for="word">
                        <div class="label-title">
                            <p>レシピ検索</p>
                        </div>
                        <input type="text" name="word" value="" placeholder="材料 × ジャンル × 調理時間 × キーワード">
                    </label>
                </div>
                <div class="subSearch">
                    <div class="element">
                        <label for="genre">
                            <div class="label-title">
                                <p>ジャンル</p>
                            </div>
                            <select name="genre" id="genre-select">
                                <option value="">選択しない</option>
                                <option value="ごはん">ごはん</option>
                                <option value="汁もの">汁もの</option>
                                <option value="おかず">おかず</option>
                                <option value="時短やつくりおき">時短やつくりおき</option>
                                <option value="ごはんのお供や保存食">ごはんのお供や保存食</option>
                                <option value="おやつ">おやつ</option>
                            </select>
                        </label>
                    </div>
                    <div class="element">
                        <label for="preference">
                            <div class="label-title">
                                <p>こだわり</p>
                            </div>
                            <select name="preference" id="preference-select">
                                <option value="">選択しない</option>
                                <option value="米粉">米粉</option>
                                <option value="発酵調味料">発酵調味料</option>
                                <option value="ヴィーガン">ヴィーガン</option>
                                <option value="乳製品不使用">乳製品不使用</option>
                                <option value="砂糖不使用">砂糖不使用</option>
                                <option value="卵不使用">卵不使用</option>
                            </select>
                        </label>
                    </div>
                    <div class="element">
                        <label for="time">
                            <div class="label-title">
                                <p>調理時間</p>
                            </div>
                            <select name="time" id="time">
                                <option value="">選択しない</option>
                                <option value="5分">5分</option>
                                <option value="10分">10分</option>
                                <option value="20分">20分</option>
                                <option value="30分">30分</option>
                                <option value="30~60分">30~60分</option>
                                <option value="60分以上">60分以上</option>
                            </select>
                        </label>
                    </div>
                </div>
            </form>
        </section>

        <section id="news" class="wrapper">
            <h2 class="section-title">
                <span class="en">Pick Up Episode</span>
                <span class="ja">ピックアップ　エピソード</span>
            </h2>

            <ul class="list">
                <li>
                    <div class="data-area">
                        <!-- timeタグは、システム開発においても有効な使い方ができるものですが、SEOにおいて重要なのは、そのページの内容がいつの時点の内容であるかを検索エンジンに示すということです。 -->
                        <!-- そのため、datetime属性の値は年月日の形式（例：2023-09-23）を使うと良いでしょう。 -->
                        <!-- Googleは、情報の鮮度が重要な内容については、検索順位を決める要素の一つとして、情報の鮮度を見ていますので、記事の最終更新日時を示すというというのは大事なことです。 -->
                        <time datetime="2021-01-01">2021.01.01</time>
                        <span>news</span>
                    </div>
                    <p>タイトルタイトルタイトル</p>
                </li>
                <li>
                    <div class="data-area">
                        <!-- timeタグは、システム開発においても有効な使い方ができるものですが、SEOにおいて重要なのは、そのページの内容がいつの時点の内容であるかを検索エンジンに示すということです。 -->
                        <!-- そのため、datetime属性の値は年月日の形式（例：2023-09-23）を使うと良いでしょう。 -->
                        <!-- Googleは、情報の鮮度が重要な内容については、検索順位を決める要素の一つとして、情報の鮮度を見ていますので、記事の最終更新日時を示すというというのは大事なことです。 -->
                        <time datetime="2021-01-01">2021.01.01</time>
                        <span>PRESS</span>
                    </div>
                    <p>タイトルタイトルタイトル</p>
                </li>
                <li>
                    <div class="data-area">
                        <!-- timeタグは、システム開発においても有効な使い方ができるものですが、SEOにおいて重要なのは、そのページの内容がいつの時点の内容であるかを検索エンジンに示すということです。 -->
                        <!-- そのため、datetime属性の値は年月日の形式（例：2023-09-23）を使うと良いでしょう。 -->
                        <!-- Googleは、情報の鮮度が重要な内容については、検索順位を決める要素の一つとして、情報の鮮度を見ていますので、記事の最終更新日時を示すというというのは大事なことです。 -->
                        <time datetime="2021-01-01">2021.01.01</time>
                        <span>news</span>
                    </div>
                    <p>タイトルタイトルタイトル</p>
                </li>
            </ul>
        </section>

        <section id="about">
            <div class="img">
                <img src="./hp_img/about.jpg" alt="">
            </div>

            <div class="text">
                <h2 class="section-title">
                    <span class="en">ABOUT</span>
                    <span class="ja">私たちについて</span>
                </h2>
                <p>
                    テキストテキストテキストテキストテキスト
                    テキストテキストテキストテキストテキスト
                </p>
            </div>
        </section>

        <section id="business" class="wrapper">
            <h2 class="section-title">
                <span class="en">BUSINESS</span>
                <span class="ja">事業内容</span>
            </h2>

            <div class="flex">
                <div class="left">
                    <div class="item">
                        <p class="title">事業1</p>
                        <img src="img/business1.jpg" alt="business1">
                    </div>
                </div>

                <div class="right">
                    <div class="item">
                        <p class="title">事業2</p>
                        <img src="img/business3.jpg" alt="business3">
                    </div>
                </div>
            </div>
        </section>

        <section id="company" class="wrapper">
            <div class="text">
                <h2 class="section-title">
                    <span class="en">COMPANY</span>
                    <span class="ja">会社情報</span>
                </h2>

                <dl class="info">
                    <dt>会社名</dt>
                    <dd>紡くっく株式会社</dd>
                    <dt>所在地</dt>
                    <dd>東京都渋谷区桜丘町99-9 West Building 3F</dd>
                    <dt>代表</dt>
                    <dd>十時紗代子</dd>
                    <dt>設立</dt>
                    <dd>2025年4月1日</dd>
                    <dt>資本金</dt>
                    <dd>1,000,000円</dd>
                    <dt>事業内容</dt>
                    <dd>料理研究家マッチング</dd>
                    <dd>おうちごはんのキュレーション</dd>
                </dl>
            </div>

            <div class="img">
                <img src="./hp_img/company.png" alt="company">
            </div>
        </section>
    </main>

    <footer id="footer">
        <div class="wrapper">
            <div class="flex">
                <div class="logo">
                    <img src="./hp_img/logo.png" alt="紡くっくのロゴ">
                </div>
                <div class="info">
                    <p>
                        TsumuCook Inc.<br>
                        West Building 3F<br>
                        9-99 Sakuragaokacho Shibuya-ku<br>
                        Tokyo, Japan 150-0031
                    </p>
                    <p>T/99-9999-9999</p>
                </div>
            </div>
            <p class="copyright">&copy; TsumuCook Inc.</p>
        </div>
    </footer>
</body>

</html>