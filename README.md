# Simple DataBase Masker

----
## 概要
Simple DataBase Maskerは、小規模なWebプロジェクトにて本番環境のデータを利用してWebアプリケーションのデバッグを行いたい場合に、ユーザーの個人情報の保護とメールの送信確認を支援するツールです。

仕様の把握も容易でない古いWebアプリケーションをデバッグしなければいけない場合に、予想外の情報流出を恐れないくて済むことは、継続的且つ迅速な価値の提供を助けます。

----
## 動作条件

Webアプリケーション実行環境：PHP5.2以上  
データベース：MySQL:5.5以上  
（5.0や5.1での動作確認は行っておりませんが、動作するつもりで開発しています）

※基本的に、現存する最も古い環境から最も新しい環境まで幅広く動作することを目指しています。

----
## 利用方法目次

[インストール方法](#インストール方法)  

[メールアドレス置き換え + 基本操作](#メールアドレス置き換え_0)  
1. [データベース接続確認](#メールアドレス置き換え_1)  
2. [アカウントパターンとドメインの設定](#メールアドレス置き換え_2)  
3. [置き換えイメージの確認](#メールアドレス置き換え_3)  
4. [置き換え実行](#メールアドレス置き換え_4)  
5. [置き換え確認](#メールアドレス置き換え_5)  

[個人情報難読化](#個人情報難読化_0)
1. [文字列シャフル](#個人情報難読化_1)  
2. [文字コードずらし](#個人情報難読化_2)  
3. [文字列先頭に追加](#個人情報難読化_3)  
4. [文字列末尾に追加](#個人情報難読化_4)  
5. [複数条件で確認](#個人情報難読化_5)  
6. [置き換え実行](#個人情報難読化_6)  
7. [置き換え確認](#個人情報難読化_7)  

----
<a name="インストール方法"></a>
## インストール方法

本番環境ではない開発環境等に置ける任意のサブディレクトリにFTPを用いてアップロードすることにより、インストールの準備が完了します。

1. ブラウザからSimple DataBase Maskerを配置したサブディレクトリにアクセスを行います。  
2. ユーザー名、パスワードを決め、[インストール]をクリックします。  
<img src="https://raw.githubusercontent.com/ip-san/wiki/master/Simple-DataBase-Masker/image/002.png" width="400px">  
3. インストールが完了します。  
<img src="https://raw.githubusercontent.com/ip-san/wiki/master/Simple-DataBase-Masker/image/003.png" width="400px">  

----
<a name="メールアドレス置き換え_0"></a>

## メールアドレス置き換え + 基本操作  

メールアドレス置き換え機能はレンタルサーバー等でテスト用のメールアドレスを連番で作成して、webアプリケーションから送信されたメールを複数のメールアドレスでの受信確認するような利用を想定しています。  

例えば、  
test001@sample.jp  
test002@sample.jp  
test003@sample.jp  
のようなパターンにデータベース上のメールアドレスを置き換えて、  
受信時はレンタルサーバーのメールboxにて受信確認を行います。  

また、メールアドレス置き換えを通して基本操作を紹介します。  

<a name="メールアドレス置き換え_1"></a>
1. データベース接続確認  
<img src="https://raw.githubusercontent.com/ip-san/wiki/master/Simple-DataBase-Masker/image/006.png" width="400px">  

<a name="メールアドレス置き換え_2"></a>
2. アカウントパターンとドメインの設定  

<a name="メールアドレス置き換え_3"></a>
3. 置き換えイメージの確認  
<img src="https://raw.githubusercontent.com/ip-san/wiki/master/Simple-DataBase-Masker/image/007.png" width="400px">  

<a name="メールアドレス置き換え_4"></a>
4. 置き換え実行  
<img src="https://raw.githubusercontent.com/ip-san/wiki/master/Simple-DataBase-Masker/image/008.png" width="400px">  

<a name="メールアドレス置き換え_5"></a>
5. 置き換え確認  
<img src="https://raw.githubusercontent.com/ip-san/wiki/master/Simple-DataBase-Masker/image/009.png" width="400px">  

----
<a name="個人情報難読化_0"></a>

## 個人情報難読化

氏名や住所、会社名等を第3者が意味のある形で読み取りにくいように書き換える方法を複数提供します。    
また、現場でデバッグしている担当者には何となく判別がつくバランスを目指しています。  

例えば、「2.文字コードずらし」を利用すれば
「織田信長」という名前を「繕由俢镸」に置き換えます。

特別保護する必要のない場合も
「3. 文字列先頭に追加 」や「4. 文字列末尾に追加」を利用することで、  
例えば「○×株式会社」を「【テスト】○×株式会社」等、一定の印を追加することができます。  
メールアドレスの置き換えと合わせて利用すると便利です。  

<a name="個人情報難読化_1"></a>
1. 文字列シャフル  
<img src="https://raw.githubusercontent.com/ip-san/wiki/master/Simple-DataBase-Masker/image/010.png" width="400px">  

<a name="個人情報難読化_2"></a>
2. 文字コードずらし  
<img src="https://raw.githubusercontent.com/ip-san/wiki/master/Simple-DataBase-Masker/image/011.png" width="400px">  

<a name="個人情報難読化_3"></a>
3. 文字列先頭に追加  
<img src="https://raw.githubusercontent.com/ip-san/wiki/master/Simple-DataBase-Masker/image/012.png" width="400px">  

<a name="個人情報難読化_4"></a>
4. 文字列末尾に追加  
<img src="https://raw.githubusercontent.com/ip-san/wiki/master/Simple-DataBase-Masker/image/013.png"  width="400px">  

<a name="個人情報難読化_5"></a>
5. 複数条件で確認  
<img src="https://raw.githubusercontent.com/ip-san/wiki/master/Simple-DataBase-Masker/image/014.png"  width="400px">  

<a name="個人情報難読化_6"></a>
6. 置き換え実行  
<img src="https://raw.githubusercontent.com/ip-san/wiki/master/Simple-DataBase-Masker/image/015.png"  width="400px">  

<a name="個人情報難読化_7"></a>
7. 置き換え確認  
<img src="https://raw.githubusercontent.com/ip-san/wiki/master/Simple-DataBase-Masker/image/016.png"  width="400px">  
