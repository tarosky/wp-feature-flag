# WP Feature Flag

フィーチャーフラグを使用して機能のオン・オフを切り替えるWordPressプラグインのサンプルです。

## フィーチャーフラグとは

フィーチャーフラグ（Feature Flag）は、コードを変更せずに機能の有効/無効を切り替える仕組みです。以下のような用途で使用されます：

- **段階的リリース**: 新機能を一部のユーザーから徐々に公開
- **A/Bテスト**: 異なる機能を比較してユーザーの反応を測定
- **緊急停止**: 問題が発生した場合、すぐに機能を無効化
- **権限管理**: 特定のユーザーグループにのみ機能を提供

## セットアップ

### 必要環境

- Node.js 14以上
- npm または yarn
- Docker Desktop

### インストール手順

1. リポジトリをクローン
```bash
git clone https://github.com/fumikito/wp-feature-flag.git
cd wp-feature-flag
```

2. 依存関係をインストール
```bash
npm install
```

3. WordPress環境を起動
```bash
npm start
```

4. ブラウザでアクセス
- サイト: http://localhost:8888
- 管理画面: http://localhost:8888/wp-admin
  - ユーザー名: `admin`
  - パスワード: `password`

## 使い方

1. 管理画面にログイン
2. 「設定」→「フィーチャーフラグ」にアクセス
3. 各機能のチェックボックスでオン・オフを切り替え
4. サイトのフロントエンドで機能の動作を確認

## 実装されている機能

### 1. フローティングボックス
- 右下に表示される通知ボックス
- 閉じるボタン付き
- ローカルストレージで閉じた状態を保存

### 2. プロモーションバナー
- ページ上部に表示される告知バナー
- キャンペーン情報などの表示に利用

### 3. デバッグモード
- HTMLコメントとしてパフォーマンス情報を出力
- メモリ使用量やページ生成時間を確認可能

## コード例

```php
// フィーチャーフラグのチェック
if ( wp_feature_flag_is_enabled( 'floating_box' ) ) {
    // フローティングボックスを表示
    echo '<div>新機能が有効です！</div>';
}
```

## npm スクリプト

```bash
# WordPress環境を起動
npm start

# WordPress環境を停止
npm stop

# WordPress環境を削除
npm run env destroy

# WP-CLIを実行
npm run cli plugin list
```

## ファイル構造

```
wp-feature-flag/
├── assets/              # CSS/JSファイル
│   ├── style.css
│   └── script.js
├── includes/            # PHPファイル
│   └── flag-settings.php  # 設定画面
├── wp-feature-flag.php  # メインプラグインファイル
├── package.json         # npm設定
├── .wp-env.json        # WordPress環境設定
└── README.md           # このファイル
```

## ライセンス

GPL v3 or later
