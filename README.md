# Don't move

Block node_module
Bavix\Wallet\Services\BookkeeperService::7

## Member Type

if member normal it will not an effect by agent VIP
if member null it will an effect by agent VIP
General | Normal | VIP | VIP1 | VIP2

update style

## node

```shell
nvm use v12.16.1
nvm use v14.20.0
```

## telegram ID

-726115644 f88-login-alert

```shell
vapor down staging --secret="f88"
vapor up staging
```

# telegram

f88-table 1 = -678008851

https://flareapp.io/blog/7-how-to-safely-delete-records-in-massive-tables-on-aws-using-laravel

## get ticket already paid

```sql
select json_extract("meta", '$."bet_id"') as "ids"
from "transactions"
where "type" = 'deposit'
  and json_extract("meta", '$."match_id"') = 1
  and json_extract("meta", '$."type"') = 'payout'
```

## Today report

```sql
select `users`.`id`,
       `users`.`name`,
       `users`.`phone`,
       `users`.`currency`,
       `users`.`type`          as `userType`,
       SUM(bet_records.amount) AS bet_amount,
       SUM(
           CASE
               WHEN matches.result = 4 THEN 0
               WHEN matches.result = 3 THEN 0
               WHEN matches.result = 5 THEN 0
               WHEN matches.result = 0 THEN 0
               WHEN bet_records.bet_on = matches.result THEN bet_records.payout
               ELSE -(bet_records.amount)
               END
           )                   AS win_lose
from `bet_records`
         inner join `users` on `users`.`id` = `bet_records`.`super_senior`
         inner join `matches` on `matches`.`id` = `bet_records`.`match_id`
where `bet_records`.`status` = 1
  and `match_id` not in (117956)
  and date(`bet_records`.`bet_date`) = '2022-06-01'
group by `bet_records`.`super_senior`
order by `users`.`name` asc
```

```sql
select `users`.`id`,
       `users`.`name`,
       `users`.`phone`,
       `users`.`currency`,
       `users`.`type`          as `userType`,
       SUM(bet_records.amount) AS bet_amount,
       SUM(
           CASE
               WHEN matches.result = 4 THEN 0
               WHEN matches.result = 3 THEN 0
               WHEN matches.result = 5 THEN 0
               WHEN matches.result = 0 THEN 0
               WHEN bet_records.bet_on = matches.result THEN bet_records.payout
               ELSE -(bet_records.amount)
               END
           )                   AS win_lose
from `bet_records`
         inner join `users` on `users`.`id` = `bet_records`.`super_senior`
         inner join `matches` on `matches`.`id` = `bet_records`.`match_id`
where `bet_records`.`status` = 1
  and `match_id` not in (117956, 118169)
  and `bet_records`.`bet_date` between '2022-05-01' and '2022-05-31'
group by `bet_records`.`super_senior`
order by `users`.`name` asc 26.81s
```

## start profile

```
php artisan migrate:fresh --seed
```

"predis/predis": "^1.1",
> https://www.wallstreetmojo.com/percentage-change-formula/


> https://blog.learningdollars.com/2021/05/09/aws-kinesis-video-stream-with-signed-url/


> https://dev.to/mupati/live-stream-with-webrtc-in-your-laravel-application-2kl3

> https://www.youtube.com/watch?v=GMbdEnK8h3U

```shell
app_id = "1107388"
key = "418c9edc63be3c2aba0e"
secret = "90fa10432b0fa230ae14"
cluster = "ap1"
```

## lambda

https://aws.amazon.com/blogs/aws/aws-lambda-functions-powered-by-aws-graviton2-processor-run-your-functions-on-arm-and-get-up-to-34-better-price-performance/

https://medium.com/swlh/setup-your-own-coturn-server-using-aws-ec2-instance-29303101e7b5

#

https://stackabuse.com/substrings-in-bash/

#

https://github.com/peers/peerjs/issues/470

https://blog.devgenius.io/how-to-create-a-video-chat-application-with-a-custom-stun-and-turn-server-8c6258542324

https://github.com/ant-media/Ant-Media-Server/wiki/WebRTC-Publishing

```php

```

### start test

```bash
php artisan octane:start --watch --env=cypress
npx cypress open
php artisan migrate:fresh --seed --env=cypress
```

### Jet Stream

Jetstream pass data to client vai *ShareInertiaData* middleware

### reference

admin.cf88.live
Agent
User: SBAG
Pass: 123456

cf88.live
Member
User: SBTEST11
Pass: Aa123456

### Factory

https://theraloss.com/laravel-time-travel-factory/

### sweetalert2

https://sweetalert2.github.io/#download
https://codesandbox.io/s/sweetalert2-vue3-jdrjc?file=/src/components/HelloWorld.vue

### RAY

https://freek.dev/1868-introducing-ray-a-debugging-tool-for-pragmatic-developers

https://coderethinked.com/how-to-cancel-a-request-using-axios-in-vue-js/

## Domain

https://laravel-news.com/unlimited-custom-domains-vapor

## Pusher

https://pusher.com/docs/channels/using_channels/connection/#available-states

```shell
php artisan octane:start --host=192.168.31.20
```

## Session extend

https://medium.com/geekculture/ill-add-it-to-the-queue-to-be-published-on-geek-culture-within-the-next-36-hours-81bfa92072aa

# sql reset password

```sql
UPDATE users
SET password = '$2y$10$Qj1B6iI2zaVW9ZhKItHYR.b31TC9mP8agJ7T16lcOHCEjKqDHmTS.'
```

## lambda

https://www.fourtheorem.com/blog/lambda-10gb

```sql
SELECT u.name,
       ts.type,
       ts.amount,
       ts.confirmed,
       json_unquote(json_extract(`meta`, '$."fight_number"')) AS "fight_number",
       json_unquote(json_extract(`meta`, '$."type"'))         AS "type",
       (SELECT `result` from matches WHERE id = 8861)         as match_result,
       ts.created_at
FROM transactions AS ts
         LEFT JOIN users AS u ON ts.payable_id = u.id
WHERE json_unquote(json_extract(`meta`, '$."match_id"')) = 8861
ORDER by ts.created_at DESC
```

# Bot

```
https://api.telegram.org/bot5421803742:AAH1A-vI_9BE2IVz_n9Fo3FaptRuSZhcyA0/sendMessage?chat_id=-937144926&text=hi

https://api.telegram.org/bot5421803742:AAH1A-vI_9BE2IVz_n9Fo3FaptRuSZhcyA0/getUpdates
https://api.telegram.org/bot5421803742:AAH1A-vI_9BE2IVz_n9Fo3FaptRuSZhcyA0/getUpdates
KD: 5128004491
```

## Lambda

https://aws.amazon.com/lambda/pricing/

##                    

https://bestofvue.com/repo/protonemedia-inertia-vue-modal-poc

### Wallet Key

```
Bavix\Wallet\Services\BookkeeperService::1
```
