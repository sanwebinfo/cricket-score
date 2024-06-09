<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class ScoreController
{
    protected $logger;

    public function __construct()
    {
        $this->logger = new Logger('api-logger');
        $this->logger->pushHandler(new StreamHandler(__DIR__ . '/../../logs/app.log', Logger::DEBUG));
    }

    public function score(Request $request, Response $response, $args)
    {
        $queryParams = $request->getQueryParams();

        if (!isset($queryParams['id']) || empty($queryParams['id'])) {
            return $this->jsonResponse($response, ['error' => 'Invalid or missing match ID'], 400);
        }

        $id = htmlspecialchars($queryParams['id']);

        try {
            $client = new Client();
            $apiResponse = $client->request('GET', 'https://www.cricbuzz.com/live-cricket-scores/' . $id, [
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36'
                ]
            ]);

            if ($apiResponse->getStatusCode() !== 200) {
                return $this->jsonResponse($response, ['error' => 'Failed to fetch data from Cricbuzz'], 502);
            }

            $html = (string) $apiResponse->getBody();
            $crawler = new Crawler($html);

            $data = $this->parseData($crawler);
            $status = $this->getStatus($data);
            if (empty($data['title'])) {
                return $this->jsonResponse($response, ['error' => 'Data not found'], 404);
            }

            if (empty($data['current_batsmen'])) {
                $data['current_batsmen'] = [['name' => 'Match Stats will Update Soon', 'runs' => 'Match Stats will Update Soon', 'balls' => 'Match Stats will Update Soon', 'strike_rate' => 'Match Stats will Update Soon']];
            }
             if (empty($data['current_bowler'])) {
                $data['current_bowler'] = [['name' => 'Match Stats will Update Soon', 'overs' => 'Match Stats will Update Soon', 'runs' => 'Match Stats will Update Soon', 'wickets' => 'Match Stats will Update Soon']];
            }

            return $this->jsonResponse($response, [
                'title' => $data['title'],
                'update' => $status,
                'livescore' => $data['live_score'],
                'match_date' => $data['match_date'],
                'runrate' => $data['runrate'],
                'current_batsmen' => $data['current_batsmen'],
                'current_bowler' => $data['current_bowler']
            ]);
        } catch (\Exception $e) {
            $this->logger->error('Error fetching score: ' . $e->getMessage());
            return $this->jsonResponse($response, ['error' => 'An error occurred while processing your request'], 500);
        }
    }

    private function parseData(Crawler $crawler)
    {
        return [
            'update' => $this->getText($crawler, "div.cb-col.cb-col-100.cb-min-stts.cb-text-complete"),
            'process' => $this->getText($crawler, "div.cb-text-inprogress"),
            'noresult' => $this->getText($crawler, "div.cb-col.cb-col-100.cb-font-18.cb-toss-sts.cb-text-abandon"),
            'stumps' => $this->getText($crawler, "div.cb-text-stumps"),
            'lunch' => $this->getText($crawler, "div.cb-text-lunch"),
            'inningsbreak' => $this->getText($crawler, "div.cb-text-inningsbreak"),
            'tea' => $this->getText($crawler, "div.cb-text-tea"),
            'rain_break' => $this->getText($crawler, "div.cb-text-rain"),
            'wet_outfield' => $this->getText($crawler, "div.cb-text-wetoutfield"),
            'match_date' => $this->getMatchDate($crawler),
            'live_score' => $this->getText($crawler, "span.cb-font-20.text-bold"),
            'title' => $this->getText($crawler, "h1.cb-nav-hdr.cb-font-18.line-ht24", 0, true),
            'runrate' => $this->getText($crawler, 'span.cb-font-12.cb-text-gray > span.text-bold:contains("CRR:") + span'),
            'current_batsmen' => $this->getBatsmenData($crawler),
            'current_bowler' => $this->getBowlersData($crawler)
        ];
    }

    private function getText(Crawler $crawler, $selector, $index = 0, $removeCommentary = false)
    {
        $node = $crawler->filter($selector);
        if ($node->count() > $index) {
            $text = $node->eq($index)->text();
            if ($removeCommentary) {
                $text = str_replace(', Commentary', '', $text);
            }
            $text = str_replace(' - Live Cricket Score', '', $text);
            return $text;
        }
        return 'Match Stats will Update Soon';
    }

    private function getMatchDate(Crawler $crawler)
    {
        $node = $crawler->filter('span[itemprop="startDate"]');

        if ($node->count() > 0) {
            $matchTime = $node->attr('content');
            $newDt = explode('+', $matchTime)[0];
            $utcTime = new \DateTime($newDt, new \DateTimeZone('UTC'));
            $utcTime->setTimezone(new \DateTimeZone('Asia/Kolkata'));

            return $utcTime->format('l, d M Y - h:i:s A');
        }

        return 'Match Date will Update Soon';
    }

    private function getStatus($data)
    {
        if ($data['update'] !== 'Match Stats will Update Soon') {
            return $data['update'];
        } elseif ($data['process'] !== 'Match Stats will Update Soon') {
            return $data['process'];
        } elseif ($data['noresult'] !== 'Match Stats will Update Soon') {
            return $data['noresult'];
        } elseif ($data['stumps'] !== 'Match Stats will Update Soon') {
            return $data['stumps'];
        } elseif ($data['lunch'] !== 'Match Stats will Update Soon') {
            return $data['lunch'];
        } elseif ($data['inningsbreak'] !== 'Match Stats will Update Soon') {
            return $data['inningsbreak'];
        } elseif ($data['tea'] !== 'Match Stats will Update Soon') {
            return $data['tea'];
        } elseif ($data['rain_break'] !== 'Match Stats will Update Soon') {
            return $data['rain_break'];
        } elseif ($data['wet_outfield'] !== 'Match Stats will Update Soon') {
            return $data['wet_outfield'];
        }
        return 'Match Stats will Update Soon';
    }

    private function jsonResponse(Response $response, array $data, int $statusCode = 200): Response
    {
        $payload = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withHeader('X-XSS-Protection', '1; mode=block')
            ->withHeader('X-Content-Type-Options', 'nosniff')
            ->withHeader('X-Frame-Options', 'DENY')
            ->withHeader('Strict-Transport-Security', 'max-age=63072000')
            ->withHeader('X-Robots-Tag', 'noindex, nofollow', true)
            ->withStatus($statusCode);
    }

    private function getBatsmenData(Crawler $crawler)
    {
        $batsmen = [];
        $batters = $crawler->filter('div.cb-col.cb-col-50');
    
        if ($batters->count() >= 3) {
            $name = $runs = $balls = $strikeRate = '';
    
            if ($batters->eq(1)->count() > 0) {
                $name = $batters->eq(1)->text();
            }
    
            $runsNode = $crawler->filter('div.cb-col.cb-col-10.ab.text-right')->eq(0);
            if ($runsNode->count() > 0) {
                $runs = $runsNode->text();
            }
    
            $ballsNode = $crawler->filter('div.cb-col.cb-col-10.ab.text-right')->eq(1);
            if ($ballsNode->count() > 0) {
                $balls = $ballsNode->text();
            }
    
            $strikeRateNode = $crawler->filter('div.cb-col.cb-col-14.ab.text-right')->eq(0);
            if ($strikeRateNode->count() > 0) {
                $strikeRate = $strikeRateNode->text();
            }
    
            $batsmen[] = [
                'name' => $name ?: 'Match Stats will Update Soon',
                'runs' => $runs !== '' ? $runs : '0',
                'balls' => $balls !== '' ? $balls : '0',
                'strike_rate' => $strikeRate !== '' ? $strikeRate : '0'
            ];
        } else {
            $batsmen[] = [
                'name' => 'Match Stats will Update Soon',
                'runs' => 'Match Stats will Update Soon',
                'balls' => 'Match Stats will Update Soon',
                'strike_rate' => 'Match Stats will Update Soon'
            ];
        }
    
        return $batsmen;
    }
    
    
    private function getBowlersData(Crawler $crawler)
    {
        $bowlers = [];
        $bowlers_info = $crawler->filter('div[class^="cb-col cb-col-"][class*="cb-col-50"]');
    
        if ($bowlers_info->count() >= 6) {
            $name = $overs = $runs = $wickets = '';
    
            if ($bowlers_info->eq(4)->count() > 0) {
                $name = $bowlers_info->eq(4)->text();
            }
    
            $oversNode = $crawler->filter('div[class^="cb-col cb-col-10"][class*="text-right"]')->eq(8);
            if ($oversNode->count() > 0) {
                $overs = $oversNode->text();
            }
    
            $runsNode = $crawler->filter('div[class^="cb-col cb-col-10"][class*="text-right"]')->eq(9);
            if ($runsNode->count() > 0) {
                $runs = $runsNode->text();
            }
    
            $wicketsNode = $crawler->filter('div[class^="cb-col cb-col-8"][class*="text-right"]')->eq(9);
            if ($wicketsNode->count() > 0) {
                $wickets = $wicketsNode->text();
            }
    
            $bowlers[] = [
                'name' => $name ?: 'Match Stats will Update Soon',
                'overs' => $overs !== '' ? $overs : '0',
                'runs' => $runs !== '' ? $runs : '0',
                'wickets' => $wickets !== '' ? $wickets : '0'
            ];
        } else {
            $bowlers[] = [
                'name' => 'Match Stats will Update Soon',
                'overs' => 'Match Stats will Update Soon',
                'runs' => 'Match Stats will Update Soon',
                'wickets' => 'Match Stats will Update Soon'
            ];
        }
    
        return $bowlers;
    }
    
    
}