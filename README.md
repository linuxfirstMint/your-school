# your-school

ユアスクbyみんなシステムズのカリキュラムを管理する <br>

---

## ディレクトリ構成

課題ごとにディレクトリを分け、開発環境はコンテナで独立させる。

```
your-school/
├── grade-management/
│   ├── docker-compose.yml
│   ├── doc/
│   └── src/
├── next-assignment/
│   ├── docker-compose.yml
│   └── src/
└── README.md
```

## ブランチ運用

- `main` は完成したものだけマージする
- 開発は `dev/課題名` でmainから直接切る
- 完成したらPRでmainにマージ

```
main
 ├── dev/grade-management
 ├── dev/next-assignment
 └── ...
```

## タグ運用

課題にステージング（プロトタイプ → ベータ → リリース等）がある場合、各段階の完成時にタグを打つ。

```
git tag 課題名/v0.1-prototype
git tag 課題名/v0.2-beta
git tag 課題名/v1.0-release
```

タグは機能が全て揃ってmainにマージした時点で打つ。開発途中では打たない。

---

## ユアスク by Blog and SNS

ユアスクブログ:　https://your-school.jp/blog/ <br>
PHPやLaravelの機能やお役立ち情報をアップしています。カリキュラムを進めていく上で参考になる記事を更新しています。

ユアスク公式X: https://x.com/yoursc_minna <br>
最新のポストを発信しています。最新情報を更新しているので、良かったらフォローしてください。

みんなシステムズ公式HP: https://minna-systems.co.jp/ <br>
当スクールの運営会社のHPです。こちらから会社情報やブログ等の情報を更新してます。

みんなシステムズ採用情報: https://minna-systems.co.jp/hr/ <br>
みんなシステムズの採用情報はこちらに掲載しています。

株式会社みんなシステムズ公式X: https://x.com/minnasystems <br>
最新のポストや採用イベントの情報を発信しています。最新情報を更新しているので、良かったらフォローしてください。
