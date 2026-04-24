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
├── .github/workflows/ci.yml  # GitHub Actions CI
└── README.md
```

## 技術スタック（hotel-reservation）

- **PHP 8.5** / **Laravel 13**
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
    └── chore/issue-XX      # 環境整備など
```

- 作業ブランチは Issue 番号をつける
- `dev/hotel-reservation` へ通常マージ（スカッシュ不可）
- マージ後、Issue は手動 Close（コミット URL をコメントに貼る）
- CI は `dev/hotel-reservation` / `main` へのPR・push時に発火

## コーディング規約

- PSR-12 準拠（PHP CS Fixer で自動チェック）
- Larastan level 6 をパスすること
- コミット前に pre-commit フックが自動で両方を実行する

## Xdebug 使い方

1. VSCode のデバッグパネルで「Listen for Xdebug (Sail)」を選択して F5
2. ブラウザの Xdebug Helper 拡張を **Debug** モードに切り替える
3. `http://localhost:8888` にアクセス → ブレークポイントで停止

- `start_with_request=default` のため、Xdebug Helper なしの場合は `?XDEBUG_SESSION=1` クエリパラメータが必要
- `.env` の `SAIL_XDEBUG_MODE=develop,debug` で有効化済み

## 現在の作業状況

- **完了**: 環境構築、Larastan、PHP CS Fixer、CaptainHook、GitHub Actions CI、Xdebug、Laravel Debugbar
- **検討中**: Issue #42 — テスト環境の整備（SQLite in-memory vs MySQL testing DB、ユーザーが方針を決定中）