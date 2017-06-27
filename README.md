# Unilead HasOffers     [![build status](http://code.unilead.net/unilead/hasoffers/badges/master/build.svg)](http://code.unilead.net/unilead/hasoffers/commits/master)

#### ORM/SDK for HasOffers API

### Example

```php
<?php
// Get needed classes
use Unilead\HasOffers\Exception;
use Unilead\HasOffers\Entity\AbstractEntity;
use Unilead\HasOffers\Entity\Affiliate;
use Unilead\HasOffers\HasOffersClient;
use Unilead\HasOffers\Contain\PaymentMethod;
use JBZoo\Event\EventManager;
use JBZoo\Event\ExceptionStop;

// Init HasOffers Client
try {
    // Init HasOffers Client
    $hoClient = new HasOffersClient('networkId', 'token');
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
    $sdasd = $affiliate->company;
    
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
    $affiliate->reload(); // forced loading actual info from HO
    $affiliate->delete(); // set deleted status
    
    // Work with related objects
    /** @var PaymentMethod $paymentMethod */
    $paymentMethod = $affiliate->getPaymentMethod();
    $paymentType = $paymentMethod->getType(); 
    $paypalEmail1 = $paymentMethod->email; 
    $paypalEmail2 = $paymentMethod->data()->find('email');
    
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
    echo $exception->getMessage(); // API or SDK errors
}
```

## Full Event List 
```
 - ho.api.request.(before|after)
 - ho.api.sleep    
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
 - ho.{entity}.related.{related}.init.(before|after)
 - ho.{related}.reload.(before|after)
 - ho.{entity}.find.(before|after)
 - billItem.reload.(before|after)
 - invoiceItem.reload.(before|after)
 - billItem.create.(before|after)
 - billItem.delete.(before|after)
 - invoiceItem.create.(before|after)
 - invoiceItem.delete.(before|after)
```

## Unit tests and check code style
```sh
make
make test-all
```
