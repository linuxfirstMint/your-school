# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## プロジェクト概要

ユアスク by みんなシステムズのカリキュラム課題を管理するモノレポ。
現在進行中の課題は `hotel-reservation/`（ホテル予約システム）。

## リポジトリ構成

```
your-school/
├── hotel-reservation/
│   ├── .vscode/launch.json   # VSCode xdebug設定（hotel-reservationをワークスペースルートとして開くこと）
│   ├── doc/                  # 設計ドキュメント（ER図・画面遷移図・テーブル定義書）
│   ├── specification.md      # 仕様書
│   └── src/                  # Laravelアプリ本体
├── grade-management/         # 別課題（成績管理システム）
├── .github/workflows/ci.yml  # GitHub Actions CI
└── README.md
```

## 技術スタック（hotel-reservation）

- **PHP ^8.3**（実行環境: 8.5）/ **Laravel 13**
- **MySQL 8.4**
- **Laravel Sail**（Docker ベース開発環境）
- **Larastan** level 6（静的解析）
- **PHP CS Fixer** PSR-12（フォーマット）
- **CaptainHook**（pre-commit フック）
- **PHPUnit 12**（テスト）
- **Xdebug 3**（ステップデバッグ）
- **Laravel Debugbar**（ブラウザ内デバッグツールバー）

## 開発環境の特殊事情

- **VirtualBox NAT 環境**で動作。ホストからは `http://localhost:8888` でアクセス（ポートフォワード 8888→80）
- Sail コンテナは `hotel-reservation/src/` のみをマウントするため、リポジトリルートの `.git` はコンテナ内から見えない
- `captainhook install` は使えないため、`.git/hooks/pre-commit` は手動で管理する

## 主要コマンド

すべて `hotel-reservation/src/` で実行する。

```bash
# コンテナ起動・停止
./vendor/bin/sail up -d
./vendor/bin/sail down

# マイグレーション
./vendor/bin/sail artisan migrate

# テスト実行
./vendor/bin/sail artisan test
./vendor/bin/sail artisan test --filter=TestClassName

# 静的解析
./vendor/bin/sail exec laravel.test php vendor/bin/phpstan analyse

# コードフォーマット確認（dry-run）
./vendor/bin/sail exec laravel.test php vendor/bin/php-cs-fixer fix --dry-run --diff

# コードフォーマット適用
./vendor/bin/sail exec laravel.test php vendor/bin/php-cs-fixer fix
```

## ブランチ運用

```
main                        # 課題完成後にのみマージ
└── dev/hotel-reservation   # 開発ベースブランチ（長寿命）
    ├── feature/issue-XX    # 機能追加
    ├── fix/issue-XX        # バグ修正
    ├── chore/issue-XX      # 開発ツール・依存関係の管理（Larastan, PHP CS Fixer など）
    ├── ci/issue-XX         # CI/CD パイプラインの変更（GitHub Actions, pre-commit フックなど）
    ├── test/issue-XX       # テストの追加・修正
    └── docs/issue-XX       # ドキュメントのみの変更
```

- 作業ブランチは Issue 番号をつける
- `dev/hotel-reservation` へ通常マージ（スカッシュ不可）
- マージ後、Issue は手動 Close（コミット URL をコメントに貼る）
- **PR マージのたびに CLAUDE.md の「現在の作業状況」を更新すること**（完了済みへの移動・残タスクの整理）
- CI は `dev/hotel-reservation` / `main` へのPR・push時に発火

## コーディング規約

- PSR-12 準拠（PHP CS Fixer で自動チェック）
- Larastan level 6 をパスすること
- コミット前に pre-commit フックが自動で両方を実行する

## アーキテクチャ方針（hotel-reservation）

### コントローラー構造

**who-what-whom 構成**でコントローラーを設計する。
コントローラーのディレクトリパス自体が「誰が（Who）・何を（What）・誰に（Whom）」を表すようにする。
1ファイル1ユースケースの `__invoke` シングルアクションコントローラーで実装することでこれを自然に実現できる。

全てサブディレクトリに統一する理由：シングルアクションとリソース型を同一ディレクトリに混在させると「このファイルはどちらのパターン？」がひと目で判断できなくなるため。

```
Http/Controllers/
  User/                        # Who: 宿泊者
    Plan/
      IndexController.php      # 宿泊者がプラン一覧を見る
      ShowController.php       # 宿泊者がプラン詳細を見る
    Reservation/
      ConfirmController.php    # 宿泊者が予約内容を確認する
      StoreController.php      # 宿泊者が予約を確定する
  Admin/                       # Who: 管理者
    Plan/
      IndexController.php
      StoreController.php
      ...
    ReservationSlot/
      IndexController.php
      BulkCreateController.php # 管理者が予約枠を期間一括作成する
      ...
    Reservation/
      CancelController.php     # 管理者が予約をキャンセルする（+ メール送信 + 枠解放）
      ...
```

### 役割分担

| 層 | 責務 |
|---|---|
| Controller | リクエスト受け取り → Service 呼び出し → レスポンス返却のみ（薄く保つ） |
| Service | ビジネスロジック・クエリ（User/Admin 共通処理の置き場所） |
| View | User/Admin で別々に持つ |

### 判断軸

**コントローラーレベルでの共通化はしない。**
継承や引数分岐を持ち込むと who-what-whom の「このファイルを開けばこのユースケースだけ」という明快さが壊れるため。

User/ と Admin/ で似た処理が必要な場合は、コントローラーは別々に作り、共通ロジックを Service に逃がす。
コントローラーが薄ければ、ファイルが増えても1ファイルあたりの複雑さは低く保てる。

## Xdebug 使い方

1. VSCode のデバッグパネルで「Listen for Xdebug (Sail)」を選択して F5
2. ブラウザの Xdebug Helper 拡張を **Debug** モードに切り替える
3. `http://localhost:8888` にアクセス → ブレークポイントで停止

- `start_with_request=default` のため、Xdebug Helper なしの場合は `?XDEBUG_SESSION=1` クエリパラメータが必要
- `.env` の `SAIL_XDEBUG_MODE=develop,debug` で有効化済み

## 現在の作業状況

### 完了済み

| Issue | 内容 | ブランチ |
|---|---|---|
| - | 環境構築、Larastan、PHP CS Fixer、CaptainHook、CI、Xdebug、Debugbar | - |
| #52 | マイグレーション（全8テーブル） | feature/issue-52 |
| #54 | 管理者認証（カスタムガード・ログイン・ログアウト・Seeder） | feature/issue-54 |
| #55 | Model / Factory（全テーブル・リレーション） | feature/issue-55 |
| #59 | pre-commit フックにテスト実行を追加 | ci/issue-59 |
| #56 | ReservationService テスト（TDD）・実装 | feature/issue-56 |
| #62 | status フィールドを PHP enum で型安全にリファクタリング | refactor/issue-62 |
| #65 | 料金未設定時の予約不可テスト追加 | test/issue-65 |
| #57 | Controller 接続・シンプル UI | feature/issue-57 |
| #69 | 予約枠管理（一覧・個別作成・期間一括作成・編集・削除・重複防止・RoomTypeSeeder） | feature/issue-69 |
| #75 | 管理者予約枠管理コントローラーのテスト（18件） | test/issue-75 |
| #76 | 管理者予約管理コントローラーのテスト（6件） | test/issue-76 |
| #77 | 宿泊者予約フローコントローラーのテスト（7件） | test/issue-77 |
| #70 | 宿泊プラン管理（作成・編集・削除・複数画像・部屋タイプ別料金） | feature/issue-70 |
| #83 | 料金フィールドを空欄で送信するとバリデーションエラーになるのを修正 | fix/plan-price-validation |
| #86 | 宿泊者向けプラン一覧・詳細ページ | feature/issue-86 |
| #71 | 空室カレンダー（○/× 表示・プラン詳細からの遷移・予約フォームへの接続） | feature/issue-71 |
| #84 | フォームバリデーション表示の改善（必須マーク・日本語メッセージ） | feature/issue-84 |
| #72 | 空室カレンダー △（残りわずか）表示 | feature/issue-72 |
| #91 | Bootstrap5 のインストールおよび Vite への統合 | feature/issue-91 |
| #92 | 共通レイアウト UI 実装（layouts/app・layouts/admin・全ビュー @extends 移行） | feature/issue-92 |
| #93 | TOP ページの UI 実装（ヒーロー画像・コンセプト・プランへの導線） | feature/issue-93 |
| #94 | アクセス案内ページの UI 実装（Google Maps・交通手段・駐車場） | feature/issue-94 |
| #95 | 客室紹介ページの UI 実装（部屋タイプ別写真・設備・アメニティ） | feature/issue-95 |
| #118 | fix: プラン詳細→空室カレンダーのリンク接続（予約フロー断絶を修正） | fix/issue-118 |
| #119 | feat: 宿泊プラン一覧・詳細ページの UI 改善（Bootstrap・サムネイル・最低料金・Seeder） | feature/issue-119 |
| #120 | feat: 空室カレンダーページの UI 改善（Bootstrap・凡例・月ナビ） | feature/issue-120 |
| #121 | feat: 予約フォーム・確認・完了ページの UI 改善（Bootstrap） | feature/issue-121 |
| #130 | feat: 空室カレンダーに部屋タイプ別タブを追加（#120 補完） | feature/issue-130 |
| #122 | feat: 管理者側各ページの UI 改善（Bootstrap・サイドバーレイアウト） | feature/issue-122 |
| #97 | メール送信基盤の構築（Mailpit 導入・Mailable クラス作成） | feature/issue-97 |
| #98 | 各種システムメールの実装（予約完了・キャンセル完了） | feature/issue-98 |
| #99 | お問い合わせ機能の実装（宿泊者側フォーム・確認ステップ・完了メール） | feature/issue-99 |
| #100 | お問い合わせ管理機能（管理者側一覧・詳細・ステータス管理・3段階ステータス） | feature/issue-100 |
| #101 | 予約リマインドバッチ（reservations:send-reminders・前日 10 時スケジュール） | feature/issue-101 |

### 進行中・残タスク

#### 品質改善（優先度高）

| Issue | 内容 | 優先度 |
|---|---|---|
| #148 | fix: お問い合わせ確認画面の「戻って修正する」でフォームデータが消える | 高 |
| #149 | feat: リマインドバッチに送信件数のログ出力を追加 | 高 |

#### 品質改善（優先度低）

| Issue | 内容 | 優先度 |
|---|---|---|
| #150 | feat: メールテンプレートにスタイルを適用 | 低 |
| #151 | feat: 管理者ダッシュボードの実装 | 低 |

#### フェーズ1：フロントエンド・UI の完成（一旦放置）

| Issue | 内容 | 優先度 |
|---|---|---|
| #131 | feat: カレンダー部屋タイプ切り替えの JS 非同期化（+α） | 低（#130 完了後） |
| #96 | MCP Playwright の導入と宿泊者予約フローの E2E テスト | 中（UI 完成後） |

### MVP 方針

- 宿泊者：未ログインでゲスト予約（宿泊者ログイン機能は実装しない）
- 管理者：カスタムガード（`auth:admin`）でログイン必須
- Breeze は使用しない（管理者はシーダー登録のみ・パスワードリセット不要のため）