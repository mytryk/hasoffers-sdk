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

use Unilead\HasOffers\Entity\AffiliateReceipt;
use Unilead\HasOffers\Entity\Affiliate;

/**
 * Class AffiliateReceiptTest
 *
 * @package JBZoo\PHPUnit
 */
class AffiliateReceiptTest extends HasoffersPHPUnit
{
    protected $testId = '2';

    public function testCreatingAffiliateReceiptWays()
    {
        $affiliate1 = $this->hoClient->get(AffiliateReceipt::class); // recommended!
        $affiliate2 = $this->hoClient->get('AffiliateReceipt');
        $affiliate3 = $this->hoClient->get('Unilead\\HasOffers\\Entity\\AffiliateReceipt');
        $affiliate4 = new AffiliateReceipt();
        $affiliate4->setClient($this->hoClient);

        isClass(AffiliateReceipt::class, $affiliate1);
        isClass(AffiliateReceipt::class, $affiliate2);
        isClass(AffiliateReceipt::class, $affiliate3);
        isClass(AffiliateReceipt::class, $affiliate4);

        isNotSame($affiliate1, $affiliate2);
        isNotSame($affiliate1, $affiliate3);
    }

    public function testCanGetAffiliateReceiptById()
    {
        $affReceipt = $this->hoClient->get(AffiliateReceipt::class, $this->testId);

        is($this->testId, $affReceipt->id);
    }

    public function testIsExist()
    {
        $affReceipt = $this->hoClient->get(AffiliateReceipt::class);
        isFalse($affReceipt->isExist());

        $affReceipt = $this->hoClient->get(AffiliateReceipt::class, 0);
        isFalse($affReceipt->isExist());

        // TODO: think about it
        // Throw exception: No Affiliate Receipt found with id = 10000000
        // $affReceipt = $this->hoClient->get(AffiliateReceipt::class, '10000000');
        // isFalse($affReceipt->isExist());

        $affReceipt = $this->hoClient->get(AffiliateReceipt::class, $this->testId);
        isTrue($affReceipt->isExist());
    }

    public function testUnset()
    {
        $affReceipt = $this->hoClient->get(AffiliateReceipt::class, $this->testId);
        isTrue($affReceipt->currency);
        unset($affReceipt->currency);
        isFalse($affReceipt->currency);

        isSame(['currency' => null], $affReceipt->getChangedFields());
    }

    public function testBindData()
    {
        $affReceipt = $this->hoClient->get(AffiliateReceipt::class, $this->testId);
        $oldNotes = $affReceipt->notes;
        $newNotes = 'New notes';
        $affReceipt->mergeData(['notes' => $newNotes]);

        isNotSame($affReceipt->notes, $oldNotes);
        isSame(['notes' => $newNotes], $affReceipt->getChangedFields());
    }

    /**
     * @expectedExceptionMessage    No data to create new object "Unilead\HasOffers\Entity\AffiliateReceipt" in HasOffers
     * @expectedException           \Unilead\HasOffers\Exception
     */
    public function testCannotSaveUndefinedId()
    {
        $affReceipt = $this->hoClient->get(AffiliateReceipt::class);
        $affReceipt->save();
    }

    /**
     * @expectedExceptionMessage Undefined property "undefined_property" in Unilead\HasOffers\Entity\AffiliateReceipt
     * @expectedException \Unilead\HasOffers\Exception
     */
    public function testCannotGetUndefinedProperty()
    {
        $affReceipt = $this->hoClient->get(AffiliateReceipt::class, $this->testId);
        is($this->testId, $affReceipt->id);

        $affReceipt->undefined_property;
    }

    public function testData()
    {
        $affReceipt = $this->hoClient->get(AffiliateReceipt::class, $this->testId);
        isNotEmpty($affReceipt->data());
    }

    public function testIsset()
    {
        $affReceipt = $this->hoClient->get(AffiliateReceipt::class, $this->testId);
        isTrue(isset($affReceipt->status));
        isFalse(isset($affReceipt->undefined));
    }

    /**
     * @expectedExceptionMessage Undefined method "getFakeContainObject" or related object "FakeContainObject" in
     *                           Unilead\HasOffers\Entity\AffiliateReceipt for objectId=2
     * @expectedException \Unilead\HasOffers\Exception
     */
    public function testCannotGetUndefinedContain()
    {
        $affiliate = $this->hoClient->get(AffiliateReceipt::class, $this->testId);

        $affiliate->getFakeContainObject();
    }

    public function testGetAffiliateInvoice()
    {
        $this->markTestSkipped('Contain not ready yet');
        $affiliate = $this->hoClient->get(AffiliateReceipt::class, $this->testId);
        $affInvoices = $affiliate->getAffiliateInvoice();

        isSame(1, count($affInvoices));
        isSame('1000', $affInvoices[0]->affiliate_id);
    }

    public function testGetAffiliate()
    {
        $this->markTestSkipped('Contain not ready yet');
        $affiliate = $this->hoClient->get(AffiliateReceipt::class, $this->testId);
        $affiliate = $affiliate->getAffiliate();

        isSame('1000', $affiliate->id);
        isSame('IL', $affiliate->country);
        isSame(Affiliate::STATUS_ACTIVE, $affiliate->status);
    }

    public function testCanCreate()
    {
        /** @var AffiliateReceipt $affReceipt */
        $affReceipt = $this->hoClient->get(AffiliateReceipt::class);
        $affReceipt->affiliate_id = '2';
        $affReceipt->date = date('Y-m-d');
        $affReceipt->currency = $this->faker->currencyCode;
        $affReceipt->amount = 20.0;
        $affReceipt->status = AffiliateReceipt::STATUS_SUCCESS;
        $affReceipt->method = AffiliateReceipt::PAYMENT_METHOD_WIRE;
        isTrue($affReceipt->isNew());
        $affReceipt->save();
        isFalse($affReceipt->isNew());

        /** @var AffiliateReceipt $affReceiptCheck */
        $affReceiptCheck = $this->hoClient->get(AffiliateReceipt::class, $affReceipt->id);

        isSame($affReceipt->id, $affReceiptCheck->id); // Check is new id bind to object
        isSame($affReceipt->currency, $affReceiptCheck->currency);
        isSame($affReceipt->amount, $affReceiptCheck->amount);

        $affReceipt->delete(); // Clean up after test
    }

    public function testCanUpdate()
    {
        $this->skipIfFakeServer();

        $affReceiptBeforeSave = $this->hoClient->get(AffiliateReceipt::class, $this->testId);

        $beforeMemo = $affReceiptBeforeSave->memo;
        $affReceiptBeforeSave->memo = $this->faker->text();
        $affReceiptBeforeSave->save();

        $affReceiptAfterSave = $this->hoClient->get(AffiliateReceipt::class, $this->testId);
        isNotSame($beforeMemo, $affReceiptAfterSave->memo);
    }

    public function testCanDelete()
    {
        $this->skipIfFakeServer();

        $affReceipt = $this->hoClient->get(AffiliateReceipt::class, $this->testId);
        $affReceipt->delete();

        $affReceiptAfterSave = $this->hoClient->get(AffiliateReceipt::class, $this->testId);

        isSame(AffiliateReceipt::STATUS_DELETED, $affReceiptAfterSave->status);
    }

    // TODO: add tests for STATUS_PENDING
    // TODO: add tests for STATUS_FAILED

    public function testUpdateOnlyChangedFields()
    {
        $randomValue = $this->faker->email;

        $affReceipt = $this->hoClient->get(AffiliateReceipt::class, $this->testId);
        $affReceipt->notes = $randomValue;
        $affReceipt->memo = $randomValue;

        isSame([
            'notes' => $randomValue,
            'memo'  => $randomValue,
        ], $affReceipt->getChangedFields());

        $affReceipt->save();

        isSame([], $affReceipt->getChangedFields());
    }

    public function testNoDataToUpdateIsNotError()
    {
        $affReceipt = $this->hoClient->get(AffiliateReceipt::class, $this->testId);
        $affReceipt->save();

        isSame([], $affReceipt->getChangedFields());
        is($this->testId, $affReceipt->id);
    }

    public function testNoRequestOnEmptyDataSave()
    {
        $eventChecker = [];
        $this->eManager->on('ho.*.save.*', function () use (&$eventChecker) {
            $args = func_get_args();
            $eventChecker[] = end($args);
        });

        $affReceipt = $this->hoClient->get(AffiliateReceipt::class, $this->testId);
        $affReceipt->save();

        isSame([], $affReceipt->getChangedFields());
        // TODO: change to affiliatereceipt
        isSame(['ho.affiliatebilling.save.before'], $eventChecker);
    }

    public function testNoChangeStatOnSameValues()
    {
        $eventChecker = [];
        $this->eManager->on('ho.*.save.*', function () use (&$eventChecker) {
            $args = func_get_args();
            $eventChecker[] = end($args);
        });

        $affReceipt = $this->hoClient->get(AffiliateReceipt::class, $this->testId);
        $affReceipt->reload();

        isSame([], $affReceipt->getChangedFields());
        $affReceipt->save();

        // TODO: change to affiliatereceipt
        isSame(['ho.affiliatebilling.save.before'], $eventChecker);
    }

    public function testNoChangeStatOnSameValuesAfterSet()
    {
        $eventChecker = [];
        $this->eManager->on('ho.*.save.*', function () use (&$eventChecker) {
            $args = func_get_args();
            $eventChecker[] = end($args);
        });

        $affReceipt = $this->hoClient->get(AffiliateReceipt::class, $this->testId);
        $memo = $affReceipt->memo;
        $affReceipt->memo = $memo;
        isSame([], $affReceipt->getChangedFields());

        $affReceipt->save();
        // TODO: change to affiliatereceipt
        isSame(['ho.affiliatebilling.save.before'], $eventChecker);
    }

    public function testSaveByArgument()
    {
        $eventChecker = [];
        $this->eManager->on('ho.*.save.*', function () use (&$eventChecker) {
            $args = func_get_args();
            $eventChecker[] = end($args);
        });

        $affReceipt = $this->hoClient->get(AffiliateReceipt::class, $this->testId);
        $affReceipt->save(['memo' => $this->faker->text()]);

        // TODO: change to affiliatereceipt
        isSame([
            'ho.affiliatebilling.save.before',
            'ho.affiliatebilling.save.after',
        ], $eventChecker);
    }

    public function testNoSaveByArgumentWithSameProps()
    {
        $eventChecker = [];
        $this->eManager
            ->on('ho.*.save.*', function () use (&$eventChecker) {
                $args = func_get_args();
                $eventChecker[] = end($args);
            })
            ->on('ho.api.request.*', function () use (&$eventChecker) {
                $args = func_get_args();
                $eventChecker[] = end($args);
            });

        $affReceipt = $this->hoClient->get(AffiliateReceipt::class, $this->testId);
        $affReceipt->save(['memo' => $affReceipt->memo]);

        // TODO: change to affiliatereceipt
        isSame([
            'ho.api.request.before',
            'ho.api.request.after',
            'ho.affiliatebilling.save.before',
        ], $eventChecker);
    }
}
