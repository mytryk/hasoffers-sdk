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
use JBZoo\Data\PHPArray;
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

        $this->hoClient = new HasOffersClient(
            Env::get('HO_API_NETWORK_ID'),
            Env::get('HO_API_NETWORK_TOKEN'),
            Env::get('HO_API_URL') ?: HasOffersClient::DEFAULT_API_URL
        );

        $httpUser = Env::get('HO_API_HTTP_USER');
        $httpPass = Env::get('HO_API_HTTP_PASS');
        if ($httpUser && $httpPass) {
            $this->hoClient->setHttpAuth($httpUser, $httpPass);
        }

        $this->hoClient->setRequestsLimit(Env::get('HO_API_REQUEST_LIMIT', 1, Env::VAR_INT));
        $this->hoClient->setTimeout(Env::get('HO_API_REQUEST_TIMEOUT', 1, Env::VAR_INT));

        $this->eManager = new EventManager();
        $this->hoClient->setEventManager($this->eManager);
        EventManager::setDefault($this->eManager);

        $this->eManager
            ->on(
                'ho.api.request.before',
                function ($client, &$requestParams, &$url) {
                    $dumpFile = $this->getDumpFilename('request');
                    $requestParams['_ho_url'] = $url;
                    file_put_contents($dumpFile . '.json', '' . json($requestParams));
                }
            )
            ->on(
                'ho.api.request.after',
                function ($client, $jsonResult, Response $response) {
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
        //skip('Skip test for fake server: ' . $this->getTestName());
    }
}
