# フリマアプリ

## 環境構築
**Dockerビルド**
1. `git clone git@github.com:urbexsaku/flea-market-app.git`
2. DockerDesktopアプリを立ち上げる
3. `docker-compose up -d --build`


**Laravel環境構築**
1. `docker-compose exec php bash`
2. `composer install`
3. 「.env.example」ファイルを 「.env」ファイルに命名を変更。または、新しく.envファイルを作成
```
cp .env.example .env
```

**Laravel環境構築**
1. `docker-compose exec php bash`
2. `composer require --dev laravel/dusk:^6.0`
3. `php artisan dusk:install`

4. .envに以下の環境変数を追加
### DB設定
``` text
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass
```
### メール設定(MailHog)
``` text
MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=test@example.com
MAIL_FROM_NAME="${APP_NAME}"
```
### Stripe設定
StripeのAPIキーを取得し、.envに設定して下さい。

https://dashboard.stripe.com/test/apikeys
``` text
STRIPE_KEY=取得した公開キー（pk_test_XXXXXXX）
STRIPE_SECRET=取得したシークレットキー（sk_test_XXXXXXX）
```
5. アプリケーションキーの作成
``` bash
php artisan key:generate
```

6. マイグレーションの実行
``` bash
php artisan migrate
```

7. シーディングの実行
``` bash
php artisan db:seed
```

## 使用技術(実行環境)
- php 8.1
- Laravel 8.75
- mysql 8.0.26
- nginx 1.21.1

## ER図
![ER図](erd.drawio.png)

## URL
- 開発環境：http://localhost/
- phpMyAdmin：http://localhost:8080/
- MailHog（メール認証確認）：http://localhost:8025/

## Stripe決済

カード支払いの確認にはStripeのテスト決済を使用しています。

- テストカード番号：4242 4242 4242 4242
- 有効期限：任意の未来の日付
- セキュリティコード：任意の3桁

## テスト用アカウント

以下のユーザーでログインが可能です。

### 一般ユーザー

| 名前 | メールアドレス | パスワード |
|------|----------------|------------|
| 鈴木一郎 | ichiro@example.com | password |
| 山田太郎 | taro@example.com | password |
| 佐藤花子 | hanako@example.com | password |

- すべて同一パスワードでログインできます。
- 出品商品はランダムでユーザーに割り当てています。