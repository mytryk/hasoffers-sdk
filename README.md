# Unilead HasOffers     [![build status](http://code.unilead.net/unilead/hasoffers/badges/master/build.svg)](http://code.unilead.net/unilead/hasoffers/commits/master)

#### ORM/SDK for HasOffers API

### Example

```php
<?php
// Get needed classes
use Unilead\HasOffers\Exception;
use Unilead\HasOffers\Entity\Affiliate;
use Unilead\HasOffers\HasOffersClient;

// Init HasOffers Client
try {
    $hoClient = new HasOffersClient('networkId', 'token');
    
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
    
    // CRUD
    $affiliate->save();
    $affiliate->reload(); // forced loading actial info from HO
    $affiliate->delete(); // set deleted status
    
    // Work with related objects
    $paymentMethod = $affiliate->getPaymentMethod();
    $paymentType = $paymentMethod->getType(); 
    $payPalEmail = $paymentMethod->email; 

} catch(Exception $e) {
    echo $e->getMessage(); // API or SDK Errors
}

```

## Unit tests and check code style
```sh
make
make test-all
```
