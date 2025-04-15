<?php

namespace App\Console\Commands;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Symfony\Component\DomCrawler\Crawler;

class Coomer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:coomer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * @var Client
     */
    private Client $client;

    private array $proxy = [
        'http' => 'http://192.168.110.217:7777',
        'https' => 'http://192.168.110.217:7777',
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->client = new Client([
            'verify' => false,
            'proxy' => $this->proxy,
            'timeout' => 30,
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/38.0.101.76 Safari/537.36']
        ]);

        $author = 'hongkongdoll';

//        $this->list($author);

        $posts = json_decode(file_get_contents($author . '.json'), true);

        $this->post($author, $posts);
    }

    public function post($author, $posts): void
    {
        $client = new Client([
            'verify' => false,
            'base_uri' => 'https://coomer.su',
            'proxy' => $this->proxy,
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/38.0.101.76 Safari/537.36']
        ]);

        $urls = Arr::pluck($posts, 'url');

        foreach ($urls as $url) {
            retry:
            try {
                $response = $client->get($url);
                $html = $response->getBody()->getContents();
//                file_put_contents('post.html', $html);
            } catch (Exception $exception) {
                $this->info($exception->getMessage());
                sleep(10);
                goto retry;
            }
            $id = last(explode("/", $url));

            $crawler = new Crawler($html);

            $title = str_replace(["by $author from OnlyFans | Coomer", '*', '"', '.', '?', '\'', '/', ':', '|', '<', '>'], '', $crawler->filter('title')->text());

            $this->info($id);
            $dir = $author . '/' . trim(iconv('GBK', 'UTF-8//IGNORE', iconv('UTF-8', 'GBK//IGNORE', $title)));

            $crawler->filter('.post__attachment-link')->each(function (Crawler $attachment) use ($author, $title) {
                $video = $attachment->attr('href');
                $this->info(now()->toDateTimeString() . "\t" . $title . "\t" . $video);
//                file_put_contents($author . '_videos.txt', $video . "\n", FILE_APPEND);

                $this->aria2($author, trim($title) . '.' . pathinfo(basename(Str::before($video, '?')), PATHINFO_EXTENSION), $video);
            });

            $crawler->filter('.fileThumb')->each(function (Crawler $attachment) use ($dir) {
                $image = $attachment->attr('href');
                $this->info(now()->toDateTimeString() . ' ' . $image);
                $this->aria2($dir, basename(Str::before($image, '?')), $image);
            });
            sleep(2);
        }
    }

    public function aria2($path, $filename, $uri): void
    {
        $realPath = '/mnt/d/zero/' . $path . '/' . $filename;
        if (file_exists($realPath)) {
            $fileInfo = stat($realPath);
            $fileSize = $this->getFileSize($uri);
            if ($fileInfo['size'] == $fileSize)
                return;
        }

        $json = [
            'id' => '',
            'jsonrpc' => '2.0',
            'method' => 'aria2.addUri',
            'params' => [
                [$uri],
                [
                    'dir' => 'D:/zero/' . $path,
                    'out' => $filename
                ]
            ]
        ];

        (new Client(['timeout' => 5]))->post('http://192.168.110.217:6800/jsonrpc', [
            'json' => $json
        ]);
    }

    public function getFileSize($url): string
    {
        $attempts = 0;
        try {
            RETRY_GET_FILE_SIZE:
            return $this->client->head($url)->getHeaderLine('Content-Length');
        } catch (Exception $exception) {
            if ($attempts++ < 3) goto RETRY_GET_FILE_SIZE;
        }
    }

    public function list($author): void
    {
        $client = new Client([
            'verify' => false,
            'proxy' => $this->proxy,
            'base_uri' => 'https://coomer.su',
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:126.0) Gecko/20100101 Firefox/126.0',
                'Host' => 'coomer.su',
                'Priority' => 'u=1',
                'Sec-Fetch-Dest' => 'document',
                'Sec-Fetch-Mode' => 'navigate',
                'Sec-Fetch-Site' => 'none',
                'Sec-Fetch-User' => '?1',
                'TE' => 'trailers',
                'Upgrade-Insecure-Requests' => '1',
                'Cookie' => '__ddg1_=amjOvPuc5Yy9IpR80N6x; thumbSize=180; zone-cap-5294206=1%3B1716733581'
            ]
        ]);

        $path = "/onlyfans/user/{$author}";

        $result = [];

        do {
            $response = $client->get($path);

            $html = $response->getBody()->getContents();

            $crawler = new Crawler($html);

            $result[] = $crawler->filter('article>a')->each(function (Crawler $article) {
                return ['title' => $article->text(), 'url' => $article->attr('href')];
            });

            $path = $crawler->filter('.pagination-button-after-current')->attr('href');

        } while ($path);

        file_put_contents($author . '.json', json_encode(Arr::collapse($result), JSON_UNESCAPED_UNICODE));
    }
}
