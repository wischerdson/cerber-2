<?php

namespace App\Console\Commands;

use App\Services\HtmlMetaCrawler\Crawler as HtmlMetaCrawlerCrawler;
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
		ini_set('memory_limit', '2048M');

		$i = 0;
		DB::connection('crawler')->table('sites')->where('completed', 0)->where('locked', 0)->orderBy('id', 'desc')->chunk(100, function ($sites) use ($i) {
			$client = new Client();

			$requests = function ($sites) use ($client) {
				foreach ($sites as $site) {
					DB::connection('crawler')->table('sites')->where('id', $site->id)->update(['locked' => 1]);
					yield fn () => $client->getAsync("http://{$site->site}", ['timeout' => 2]);
				}
			};

			$pool = new Pool($client, $requests($sites), [
				'concurrency' => 50, // Количество одновременных запросов
				'fulfilled' => fn ($response, $i) => $this->handleResponse($response, $sites[$i]),
			]);

			$pool->promise()->wait();

			$i++;
			$a = $i*100;

			$this->info("Обработано {$a} сайтов");
		});
	}

	public function handleResponse(Response $response, $site)
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
			$elements = HtmlMetaCrawlerCrawler::init(html: (string) $response->getBody())->run();
		} catch (\Throwable $th) {
			Log::error("Хуйня: \n{e}", ['e' => (string) $th]);
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
		$handle = fopen(base_path('.runtime/tmp/com_domains_base.txt'), 'r');

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
