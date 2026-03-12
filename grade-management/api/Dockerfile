# PHP 8.2 と Apache がセットになった公式イメージを使用
FROM php:8.2-apache

# 1. MySQL 接続用の PHP 拡張ライブラリをインストール
RUN docker-php-ext-install pdo_mysql

# 2. Apache のヘッダー操作モジュールを有効化 (CORS対策で後ほど使用)
RUN a2enmod headers

# 3. Apache のリライトモジュールを有効化 (APIのURLを綺麗にする場合に必要)
RUN a2enmod rewrite

# 作業ディレクトリをコンテナ内のドキュメントルートに設定
WORKDIR /var/www/html
