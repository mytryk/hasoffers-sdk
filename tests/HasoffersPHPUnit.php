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

use JBZoo\Data\JSON;
use JBZoo\Event\EventManager;
use JBZoo\HttpClient\Response;
use JBZoo\Utils\Env;
use JBZoo\Utils\Str;
use Unilead\HasOffers\HasOffersClient;

/**
 * Class HasoffersPHPUnit
 * @package JBZoo\PHPUnit
 */
class HasoffersPHPUnit extends PHPUnit
{
    /**
     * @var HasOffersClient
     */
    protected $hoClient;

    /**
     * @var EventManager
     */
    protected $eManager;

    public function setUp()
    {
        parent::setUp();

        $this->hoClient = new HasOffersClient(
            Env::get('HO_API_NETWORK_ID'),
            Env::get('HO_API_NETWORK_TOKEN')
        );
        $this->hoClient->setRequestsLimit(1);
        $this->hoClient->setTimeout(1);

        $this->eManager = new EventManager();
        $this->hoClient->setEventManager($this->eManager);

        $this->eManager->on('ho.api.request.before', function ($client, $data) {
            $dumpFile = $this->getDumpFilename('request');
            file_put_contents($dumpFile . '.json', (new JSON($data))->__toString());
        });

        $this->eManager->on('ho.api.request.after', function ($client, $jsonResult, Response $response) {
            $dumpFile = $this->getDumpFilename('response');
            file_put_contents($dumpFile . '.json', $response->getJSON());
        });
    }

    /**
     * @param $postfix
     * @return string
     */
    private function getDumpFilename($postfix)
    {
        $testName = $this->getTestName(debug_backtrace());
        $dumpFile = PROJECT_BUILD . "/dumps/{$testName}-{$postfix}-0";
        while (file_exists($dumpFile . '.json')) {
            $dumpFile = Str::inc($dumpFile, 'dash');
        }

        return $dumpFile;
    }

    /**
     * @param array $trace
     * @return string
     * @throws Exception
     */
    private function getTestName(array $trace)
    {
        foreach ($trace as $traceRow) {
            if (strpos($traceRow['function'], 'test') === 0) {
                $testName = str_replace('test-', '', Str::splitCamelCase($traceRow['function'], '-'));
                $entity = str_replace([__NAMESPACE__ . '\\', 'Test'], '', static::class);

                return strtolower($entity . '-' . $testName);
            }
        }

        throw new Exception('Test name not found');
    }
}
