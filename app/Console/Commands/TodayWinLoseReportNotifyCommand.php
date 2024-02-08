<?php

namespace App\Console\Commands;

use App\Kravanh\Support\Enums\Currency;
use App\Kravanh\Support\External\Telegram\Telegram;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class TodayWinLoseReportNotifyCommand extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'app:today-win-lose-notify';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Command description';

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Execute the console command.
   *
   * @return int
   */
  public function handle(): int
  {
    //-645616270
    Telegram::send()
      //->allowSendInLocal()
      ->to('-645616270')
      ->textMarkDownV2($this->makeReport());

    return 0;
  }

  protected function makeReport(): string
  {
    $reports = $this->getTodayWinLoseReport();

    $message = <<<EOD
        ```
         TODAY WIN/LOSE:
        EOD;

    $message .= today()->format('Y-d-m');


    foreach ($reports as $report) {
      $betAmount = priceFormat(fromKHRtoCurrency($report->bet_amount, Currency::fromKey($report->currency)), $report->currency);
      $winLose = priceFormat(fromKHRtoCurrency($report->win_lose, Currency::fromKey($report->currency)), $report->currency);
      $message .= "\n\n{$report->name}: ------";
      $message .= "\nBet Amount: {$betAmount}";
      $message .= "\nWin Lose:   {$winLose}";
      $message .= "\n----------------------------";
    }

    $betAmount = collect($reports)?->sum('bet_amount');
    $winLose = collect($reports)?->sum('win_lose');
    $betAmount = priceFormat(fromKHRtoCurrency($betAmount, Currency::fromKey('KHR')), "KHR");
    $winLose = priceFormat(fromKHRtoCurrency($winLose, Currency::fromKey('KHR')), 'KHR');

    $message .= "\n\nTotal: ------";
    $message .= "\nBet Amount: {$betAmount}";
    $message .= "\nWin Lose:   {$winLose}";
    $message .= "\n```";

    return $message;
  }

  protected function getTodayWinLoseReport(): array
  {
    $key = "today:report:" . today()->format('Ymd');
    return Cache::remember($key, now()->addMinutes(25), function () {
      return DB::select(
        'select
  `users`.`id`,
  `users`.`name`,
  `users`.`phone`,
  `users`.`currency`,
  `users`.`type` as `userType`,
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
  ) AS win_lose
from
  `bet_records`
  inner join `users` on `users`.`id` = `bet_records`.`super_senior`
  inner join `matches` on `matches`.`id` = `bet_records`.`match_id`
where
  `bet_records`.`status` = 1
  and `bet_records`.`bet_date` = ?
group by
  `bet_records`.`super_senior`
order by
  `users`.`name` asc',
        [today()->format('Y-m-d')]
      );
    });
  }
}
