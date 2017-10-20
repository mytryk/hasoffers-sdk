<?php
/**
 * Unilead | HasOffers
 *
 * This file is part of the Unilead Service Package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package     HasOffers
 * @license     Proprietary
 * @copyright   Copyright (C) Unilead Network, All rights reserved.
 * @link        https://www.unileadnetwork.com
 */

namespace JBZoo\PHPUnit;

use Faker\Factory;
use Faker\Generator;
use Faker\Provider\pt_BR\PhoneNumber;
use function JBZoo\Data\json;
use JBZoo\Event\EventManager;
use JBZoo\HttpClient\HttpClient;
use JBZoo\HttpClient\Response;
use JBZoo\Utils\Env;
use JBZoo\Utils\Str;
use Unilead\HasOffers\HasOffersClient;
use Unilead\HasOffers\Helper;

/**
 * Class HasoffersPHPUnit
 *
 * @package JBZoo\PHPUnit
 */
abstract class HasoffersPHPUnit extends PHPUnit
{
    /**
     * @var HasOffersClient
     */
    protected $hoClient;

    /**
     * @var EventManager
     */
    protected $eManager;

    /**
     * @var Generator
     */
    protected $faker;

    public function setUp()
    {
        parent::setUp();

        $this->faker = Factory::create();
        $this->faker->addProvider(new PhoneNumber($this->faker));

        $apiUrl = Env::get('HO_API_URL') ?: HasOffersClient::DEFAULT_API_URL;
        $isLearning = Env::get('HO_FAKE_SERVER_LEARNING', false, Env::VAR_BOOL);
        $fakeServerUrl = Env::get('HO_FAKE_SERVER_URL');
        $isFakeServer = $apiUrl !== HasOffersClient::DEFAULT_API_URL;

        $this->hoClient = new HasOffersClient(
            Env::get('HO_API_NETWORK_ID'),
            Env::get('HO_API_NETWORK_TOKEN'),
            $apiUrl
        );
        $this->hoClient->setRequestsLimit(Env::get('HO_API_REQUEST_LIMIT', 1, Env::VAR_INT));
        $this->hoClient->setTimeout(Env::get('HO_API_REQUEST_TIMEOUT', 1, Env::VAR_INT));

        $this->eManager = new EventManager();
        $this->hoClient->setEventManager($this->eManager);
        EventManager::setDefault($this->eManager);

        $this->eManager
            ->on(
                'ho.api.request.before',
                function ($client, &$requestParams, &$url) use ($isFakeServer) {
                    if ($isFakeServer) {
                        $url = rtrim($url, '/') . '/get/' . Helper::hash($requestParams);
                    }

                    $dumpFile = $this->getDumpFilename('request');
                    file_put_contents($dumpFile . '.json', '' . json($requestParams));
                }
            )
            ->on(
                'ho.api.request.after',
                function ($client, $jsonResult, Response $response, $data) use ($isLearning, $fakeServerUrl) {
                    if ($isLearning) {
                        $learnData = [
                            'key'      => Helper::hash($data),
                            'request'  => '' . json($data),
                            'response' => '' . $response->getJSON(),
                            'comment'  => 'HO Tests: ' . $this->getTestName(),
                        ];

                        $httpClient = new HttpClient();
                        $response = $httpClient->request($fakeServerUrl . '/learn', $learnData, 'post');

                        if (!$response->getJSON()->is('status', 'ok')) {
                            throw new Exception(
                                'Fake server cannot save fixture: ' . print_r($learnData, true) .
                                'Response: ' . print_r($response, true)
                            );
                        }
                    }

                    $dumpFile = $this->getDumpFilename('response');
                    file_put_contents($dumpFile . '.json', $response->getJSON());
                }
            );
    }

    /**
     * @param $postfix
     * @return string
     */
    private function getDumpFilename($postfix)
    {
        $testName = $this->getTestName();
        $dumpFile = PROJECT_BUILD . "/dumps/{$testName}-{$postfix}-0";
        while (file_exists($dumpFile . '.json')) {
            $dumpFile = Str::inc($dumpFile, 'dash');
        }

        return $dumpFile;
    }

    /**
     * @return string
     */
    private function getTestName()
    {
        $trace = debug_backtrace();
        foreach ($trace as $traceRow) {
            if (strpos($traceRow['function'], 'test') === 0) {
                $testName = str_replace('test_', '', Str::splitCamelCase($traceRow['function'], '_'));
                $entity = str_replace([__NAMESPACE__ . '\\', 'Test'], '', static::class);

                return strtolower($entity . '_' . $testName);
            }
        }

        return 'undefined_test_name';
    }

    /**
     * @return bool
     */
    protected function skipIfFakeServer()
    {
        $apiUrl = Env::get('HO_API_URL') ?: HasOffersClient::DEFAULT_API_URL;
        if ($apiUrl !== HasOffersClient::DEFAULT_API_URL) {
            skip('Skip test for fake server: ' . $this->getTestName());
        }
    }
}
