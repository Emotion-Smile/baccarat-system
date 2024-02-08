## SQL

### Total win

```sql
SELECT SUM(br.payout) AS total_win
FROM bet_records AS br
         LEFT JOIN matches AS m ON br.match_id = m.id
WHERE br.bet_date = '2021-11-24'
  AND br.user_id = 12
  AND br.`type` = 1
  AND m.`result` = br.bet_on
```
### total bet per match

```sql
SELECT SUM(amount) as total_bet,
       bet_on
FROM bet_records
WHERE match_id = 17
GROUP by bet_on
```

```sql
SELECT
	SUM(amount) AS total_bet,
	bet_on
FROM
	bet_records
WHERE
	user_id = 12
	AND match_id = 17
GROUP BY
	bet_on;
```

background & 

## check transaction
```sql
SELECT id FROM users WHERE `name`='mega66';

SELECT
    *
FROM
    `transactions`
WHERE
    `created_at` BETWEEN  '2021-12-04 23:45:00' AND '2021-12-05 00:03:00'
  and payable_id = (SELECT id FROM users WHERE `name`='mega66')
ORDER BY
    `id` DESC
LIMIT 30 OFFSET 0
```
