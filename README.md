# Unilead HasOffers     [![build status](http://code.unilead.net/unilead/hasoffers/badges/master/build.svg)](http://code.unilead.net/unilead/hasoffers/commits/master)

#### ORM/SDK for HasOffers API

### Example

```php
<?php
// Get needed classes
use Unilead\HasOffers\Exception;
use Unilead\HasOffers\Entity\Affiliate;
use Unilead\HasOffers\HasOffersClient;
use Unilead\HasOffers\PaymentMethod;

// Init HasOffers Client
try {
    $hoClient = new HasOffersClient('networkId', 'token');
    
    /** @var Affiliate $affiliate */
    $affiliate = $hoClient->get(Affiliate::class);
    $affiliate2 = $hoClient->get(Affiliate::class, 1004);
    
    // Get & set props
    $affiliate->company = 'Test Company';
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

} catch(Exception $exception) {
    echo $exception->getMessage(); // API or SDK errors
}

```

## TODO list
 - add `$hoClient->setRequestsLimit()`
 - add `$hoClient->setTimeout()`
 - fix tests for `$affiliate->delete()`
 - add JBZoo/Event support and triggers
    - save (before, after)
    - save.new (before, after)
    - create (before, after)
    - init entites (before, after)
    - api request (before, after)
    - api on limit (sleep) from config  
    - init HO

## Unit tests and check code style
```sh
make
make test-all
```
