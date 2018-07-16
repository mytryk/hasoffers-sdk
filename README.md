# HasOffers SDK by Item8    [![Latest Stable Version](https://poser.pugx.org/item8/hasoffers-sdk/v/stable)](https://packagist.org/packages/item8/hasoffers-sdk)    [![Latest Unstable Version](https://poser.pugx.org/item8/hasoffers-sdk/v/unstable)](https://packagist.org/packages/item8/hasoffers-sdk)    [![License](https://poser.pugx.org/item8/hasoffers-sdk/license)](https://packagist.org/packages/item8/hasoffers-sdk)    [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/item8/hasoffers-sdk/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/item8/hasoffers-sdk/?branch=master) <img src="https://ci.item8.io/app/rest/builds/buildType:Others_HasOffersSdk/statusIcon" />

ORM/SDK for HasOffers API

## Code Examples

#### Client Initialization
```php
$hoClient = new ClientApi();
$hoClient->setAuth('networkId', 'token');
```

#### Integrator Initialization
```php
$hoClient = new IntegratorApi();
$hoClient->setAuth('clientId', 'clientSecret', 'integratorId');
```

#### Usage as ORM

```php
<?php
// Get needed classes
use Item8\HasOffers\Exception;
use Item8\HasOffers\Entity\AbstractEntity;
use Item8\HasOffers\Entity\Affiliate;
use Item8\HasOffers\Entity\AffiliateInvoice;
use Item8\HasOffers\Request\ClientApi;
use Item8\HasOffers\Contain\PaymentMethod;
use Item8\HasOffers\Contain\AffiliateInvoiceItem;
use JBZoo\Event\EventManager;
use JBZoo\Event\ExceptionStop;

// Init HasOffers Client
try {
    // Init HasOffers Client
    $hoClient = new ClientApi();
    $hoClient->setAuth('networkId', 'token');
    
    $eManager = new EventManager();
    $hoClient->setEventManager($eManager);

    // Sleep 10 seconds each 100 requests to API
    $hoClient->setTimeout(10);
    $hoClient->setRequestsLimit(100);
    
    /** @var Affiliate $affiliate */
    $affiliate = $hoClient->get(Affiliate::class);
    $affiliate2 = $hoClient->get(Affiliate::class, 1004);
    
    // Get & set props
    $affiliate->company = 'Test Company';
    $companyName = $affiliate->company;
    
    $affiliate->phone = '+7 845 845 84 54';
    $affiliate->bindData([
        'company' => 'Test Company',
        'phone'   => '+7 845 845 84 54'
    ]);
    $affiliate->mergeData([
        'company' => 'Test Company',
    ]);
    
    $affiliate->data()->find('company');
    $affiliate->data()->find('some.nested.key');
    
    // CRUD
    $affiliate->save();
    $affiliate->save(['company' => 'New Company Name']);
    $affiliate->reload(); // forced loading actual info from HO
    $affiliate->delete(); // set deleted status
    
    // Work with related objects
    /** @var PaymentMethod $paymentMethod */
    $paymentMethod = $affiliate->getPaymentMethod();
    $paymentType = $paymentMethod->getType(); 
    $paypalEmail1 = $paymentMethod->email; 
    $paypalEmail2 = $paymentMethod->data()->find('email');
    
    // Work with contain items
    $billId = 56;
    $affInvoice = $hoClient->get(AffiliateInvoice::class, $billId);
    $affInvoiceItemsResultSet = $affInvoice->getItemsList();
    
    // Find all: iterable
    $affInvoiceItems = $affInvoiceItemsResultSet->findAll();
    foreach ($affInvoiceItems as $affInvoiceItem) {
        $affInvoiceItem->amount = 0.0;
        $affInvoiceItem->save();
    }

    // Find by ID
    $affInvoiceItemId = 123;
    $affInvoiceItem = $affInvoiceItemsResultSet->findById($affInvoiceItemId);
    $affInvoiceItem->delete(); // delete from HO and reload parent
    
    // Add item
    $invoiceItem = $affInvoice
        ->getItemsList()
        ->addItem([
            'invoice_id'  => $billId,
            'offer_id'    => 8,
            'memo'        => 'memo',
            'amount'      => 0.0,
            'payout_type' => AffiliateInvoiceItem::PAYOUT_TYPE_CPA_FLAT
        ])->save();
    
    // Or
    $affInvoiceItem = $affInvoiceItemsResultSet->addItem();
    $affInvoiceItem->invoice_id = $billId;
    $affInvoiceItem->offer_id = 8;
    $affInvoiceItem->amount = 0.0;
    $affInvoiceItem->payout_type = 'cpa_flat';
    $affInvoiceItem->save();
    
    // Attach event handlers
    $eManager
        ->on('ho.*.save.before', function(AbstractEntity $entity){
            saveToLog($entity->data(), 'Snapshort before save');
        })
        ->on('ho.*.save.before', function(AbstractEntity $entity){
            if('__some__condition__') {
                throw new ExceptionStop('Break event chain');
            }
        })
        ->on('ho.*.save.after', function(AbstractEntity $entity){
            saveToLog($entity->data(), 'Snapshort after save');
        });

} catch(Exception $exception) {
    echo $exception->getMessage(); // Any API or SDK errors
}
```

#### Full Event List
```
 - ho.api.request.(before|after)
 - ho.api.sleep.(before|after)
 
 - ho.exception
 - ho.{entity}.init
 - ho.{entity}.save.(before|after)
 - ho.{entity}.set.{property}.(before|after)
 - ho.{entity}.unset.{property}.(before|after)
 - ho.{entity}.bind.(before|after)
 - ho.{entity}.delete.(before|after)
 - ho.{entity}.block.(before|after)
 - ho.{entity}.reload.(before|after)
 - ho.{entity}.restore.(before|after)                   // Only Advertiser 
 - ho.{entity}.unblock.(before|after)                   // Only Affiliate
 - ho.{entity}.find.(before|after)

 - ho.{entity}.related.{contain}.init.(before|after)
 - ho.{related}.reload.(before|after)
```

## Unit tests and check code style
```sh
make
make test-all
```

## Licence
GNU GPL v2.0 or later. [See details](https://github.com/item8/hasoffers-sdk/blob/master/LICENSE.md)
