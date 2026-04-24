

<!-- @import "[TOC]" {cmd="toc" depthFrom=1 depthTo=6 orderedList=false} -->

<!-- code_chunk_output -->

- [reservations（予約）](#reservations予約)
- [reservation_slots(予約枠)](#reservation_slots予約枠)
- [accommodation_plans(宿泊プラン)](#accommodation_plans宿泊プラン)
- [plan_images(宿泊プランイメージ)](#plan_images宿泊プランイメージ)
- [room_types(部屋)](#room_types部屋)
- [plan_room_prices(プラン×部屋タイプによる金額)](#plan_room_pricesプラン部屋タイプによる金額)

<!-- /code_chunk_output -->


## reservations（予約）

| カラム名 | 型 | NULL | デフォルト | 備考 |
|---------|---|------|-----------|------|
| id | BIGINT UNSIGNED | NO | AUTO_INCREMENT | PK |
| reservation_slot_id | BIGINT UNSIGNED | NO | - | FK |
| accommodation_plan_id | BIGINT UNSIGNED | NO | - | FK |
| plan_name | VARCHAR(255) | NO | - |予約時点のプラン|
| price | INT UNSIGNED | NO | - |予約時点の料金|
| last_name | VARCHAR(255) | NO | - ||  
| first_name | VARCHAR(255) | NO | - ||  
| email | VARCHAR(255) | NO | - ||  
| address | VARCHAR(255) | NO | - ||  
| phone | VARCHAR(20) | NO | - ||  
| message | TEXT | YES | NULL ||  
| status | TINYINT | NO | 1 | 1:予約済 2:キャンセル |
| memo | TEXT | YES | NULL ||  
| created_at | DATETIME | NO | - ||  
| updated_at | DATETIME | YES | NULL ||  


## reservation_slots(予約枠)
| カラム名 | 型 | NULL | デフォルト | 備考 |
|---------|---|------|-----------|------|
| id | BIGINT UNSIGNED | NO | AUTO_INCREMENT | PK |
| room_type_id | BIGINT UNSIGNED | NO | - | FK |
| status | TINYINT | NO | 1 | 1:埋まっていない 2:埋まっている |
| start | DATE | NO | - |チェックイン|
| end | DATE | NO | - |チェックアウト|
| created_at | DATETIME | NO | - ||  
| updated_at | DATETIME | YES | NULL ||  


## accommodation_plans(宿泊プラン)
| カラム名 | 型 | NULL | デフォルト | 備考 |
|---------|---|------|-----------|------|
| id | BIGINT UNSIGNED | NO | AUTO_INCREMENT | PK |
| name | VARCHAR(255) | NO | - ||  
| description | TEXT | YES | NULL ||  
| created_at | DATETIME | NO | - ||  
| updated_at | DATETIME | YES | NULL ||  


## plan_images(宿泊プランイメージ)
| カラム名 | 型 | NULL | デフォルト | 備考 |
|---------|---|------|-----------|------|
| id | BIGINT UNSIGNED | NO | AUTO_INCREMENT | PK |
| accommodation_plan_id | BIGINT UNSIGNED | NO | - | FK |
| name | VARCHAR(255) | NO | - ||  
| image_path | VARCHAR(255) | NO | - ||  
| created_at | DATETIME | NO | - ||  


## room_types(部屋)
| カラム名 | 型 | NULL | デフォルト | 備考 |
|---------|---|------|-----------|------|
| id | BIGINT UNSIGNED | NO | AUTO_INCREMENT | PK |
| name | VARCHAR(255) | NO | - ||  
| count | INT UNSIGNED | NO | - | |
| created_at | DATETIME | NO | - ||  
| updated_at | DATETIME | YES | NULL ||  

## plan_room_prices(プラン×部屋タイプによる金額)
| カラム名 | 型 | NULL | デフォルト | 備考 |
|---------|---|------|-----------|------|
| id | BIGINT UNSIGNED | NO | AUTO_INCREMENT | PK |
| room_type_id | BIGINT UNSIGNED | NO | - | FK |
| accommodation_plan_id | BIGINT UNSIGNED | NO | - | FK |
| price | INT UNSIGNED | NO | - ||  
| created_at | DATETIME | NO | - ||  
| updated_at | DATETIME | YES | NULL ||  

 ## inquiries (問い合わせ)
| カラム名 | 型 | NULL | デフォルト | 備考 |
|---------|---|------|-----------|------|
| id | BIGINT UNSIGNED | NO | AUTO_INCREMENT | PK |
| last_name | VARCHAR(255) | NO | - ||  
| first_name | VARCHAR(255) | NO | - ||  
| email | VARCHAR(255) | NO | - ||  
| address | VARCHAR(255) | NO | - ||  
| phone | VARCHAR(20) | NO | - ||  
| message | TEXT | YES | NULL ||  
| status | TINYINT | NO | 1 | 1:問い合わせ中 2:完了 |
| created_at | DATETIME | NO | - ||  
| updated_at | DATETIME | YES | NULL ||  

  ## admins (管理者)
| カラム名 | 型 | NULL | デフォルト | 備考 |
|---------|---|------|-----------|------|
| id | BIGINT UNSIGNED | NO | AUTO_INCREMENT | PK |
| last_name | VARCHAR(255) | NO | - ||  
| first_name | VARCHAR(255) | NO | - ||  
| email | VARCHAR(255) | NO | - | UNIQUE |  
| password | VARCHAR(255) | NO | - ||  
| created_at | DATETIME | NO | - ||  
| updated_at | DATETIME | YES | NULL ||    

