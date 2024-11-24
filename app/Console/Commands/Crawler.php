<?php

namespace App\Console\Commands;

use App\Services\HtmlMetaCrawler\Crawler as HtmlMetaCrawler;
use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Response;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Crawler extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'crawler';

	/**
	 * Execute the console command.
	 */
	public function handle()
	{
		ini_set('memory_limit', '4096M');

		// $this->writeComSites();
		$this->parse();
	}

	private function parse()
	{
		$i = 0;
		$chunk = 500;
		$client = new Client([
			'headers' => ['Connection' => 'keep-alive'],
			'timeout' => 2,
			'http_errors' => false,
			'allow_redirects' => false,
			'cookies' => false,
		]);

		// update sites set locked = 0 where completed = 0 and locked = 1

		DB::connection('crawler')
			->table('sites')
			->where('completed', 0)
			->where('locked', 0)
			->orderBy('id')
			->chunk($chunk, function ($sites) use ($client, $chunk, &$i) {
				$j = 0;

				DB::connection('crawler')
					->table('sites')
					->whereIn('id', $sites->pluck('id'))
					->update(['locked' => 1]);

				$requests = function ($sites) use ($client) {
					foreach ($sites as $site) {
						yield fn () => $client->getAsync("http://{$site->site}");
					}
				};

				$pool = new Pool($client, $requests($sites), [
					'concurrency' => 15, // Количество одновременных запросов
					'fulfilled' => function ($response, $i) use ($sites, &$j) {
						$j++;
						$this->handleResponse($response, $sites[$i]);
					}
				]);

				$pool->promise()->wait();

				$i++;
				$n = $i*$chunk;

				$this->info("\nОбработано всего {$n} сайтов, из них ответили всего {$j} \n");
				unset($j, $pool, $requests);

				usleep(500000);
			});
	}

	private function handleResponse(Response $response, $site)
	{
		if ($contentType = @$response->getHeader('content-type')[0]) {
			if (!str_contains($contentType, 'text/html')) {
				$this->warn("Сайт {$site->id} не имеет html, пропускаем");
				return;
			}
		} else {
			$this->warn("Сайт {$site->id} не имеет content-type, пропускаем");
			return;
		}

		try {
			$elements = HtmlMetaCrawler::init(html: (string) $response->getBody())->run();
		} catch (\Throwable $th) {
			Log::error("Хуйня ({$site->site}): \n{e}", ['e' => (string) $th]);
			$this->error('Ошибка, чек логи');
			return;
		}

		$this->info("Обработка сайта {$site->site} [{$site->id}]");

		/** @var \App\Services\HtmlMetaCrawler\Element $meta */
		foreach ($elements->meta as $meta) {
			$metaId = DB::connection('crawler')->table('meta')->insertGetId(['site_id' => $site->id]);
			$attrs = [];

			foreach ($meta->attributes as $attrName => $attrValue) {
				$attrs[] = ['meta_id' => $metaId, 'site_id' => $site->id, 'name' => $attrName, 'value' => $attrValue];
			}

			DB::connection('crawler')->table('meta_attrs')->insert($attrs);
			DB::connection('crawler')->table('sites')->where('id', $site->id)->update(['completed' => 1]);
		}
	}

	private function writeComSites()
	{
		$handle = fopen(base_path('.runtime/tmp/com.txt'), 'r');

		$domains = [];
		$i = 0;

		while ($line = fgets($handle)) {
			$domain = mb_strtolower(trim($line));
			$domains[] = ['site' => $domain];

			if (count($domains) >= 1000) {
				$this->info($i*1000);

				DB::connection('crawler')->table('sites')->insertOrIgnore($domains);

				$domains = [];
				$i = $i + 1;
			}
		}

		fclose($handle);
	}
}
