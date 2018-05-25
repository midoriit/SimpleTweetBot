# SimpleTweetBot

## 概要
Twitter用の簡単なBotです。

## 内容
#### createtable.sql
MySQLにテーブルを作成するスクリプトです。
ID、ツイート本文、ツイート回数、最後にツイートした日付を格納します。

#### SimpleTweetBot.php
MySQLから取得したツイート本文をTwitterに1件ツイートするPHPスクリプトです。cron等を使用して定期的に実行します。別途、[TwitterOAuth](https://github.com/abraham/twitteroauth)が必要です。
ツイート本文は、ツイート回数が少ないものからランダムに取得します。1日に複数回、同じIDのツイートはしません。

## 利用例
### [山頭火365](https://twitter.com/santoka365)
種田山頭火の日記に書かれた自由律俳句を、日記の月日に合わせてTwitterでつぶやくbot
