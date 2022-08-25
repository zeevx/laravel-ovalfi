<p align="center">
    <img title="ovalFi" src="https://miro.medium.com/max/1400/1*O6x9Uyx_sLsV6OuWn_IUUw.png"/>
</p>

## OvalFi Laravel Package
Laravel-OvalFi helps you Set up, test, and manage your OvalFi integration directly in your Laravel App.


[![Latest Version on Packagist](https://img.shields.io/packagist/v/zeevx/laravel-ovalfi.svg?style=flat-square)](https://packagist.org/packages/zeevx/laravel-ovalfi)
[![Total Downloads](https://img.shields.io/packagist/dt/zeevx/laravel-ovalfi.svg?style=flat-square)](https://packagist.org/packages/zeevx/laravel-ovalfi)
![GitHub Actions](https://github.com/zeevx/laravel-ovalfi/actions/workflows/main.yml/badge.svg)


## Installation

You can install the package via composer:

```bash
composer require zeevx/laravel-ovalfi
```

After installation, run (to create the ovalfi.php config file):
```bash
php artisan ovalfi:publish
```

Add the following to your .env, you can get these values from your ovalfi dashboard:

#### NB: OVALFI_MODE should be set to _sandbox_ for testing and _live_ for production.

```bash
OVALFI_MODE=
OVALFI_PUBLIC_KEY=
OVALFI_BEARER_TOKEN
```

## Usage:

### Use the helper function
```php
ovalfi() //It works automatically 😍
```


### Create customer
```php
ovalfi()->createCustomer(
        string $name,
        string $mobile_number,
        string $email,
        string $reference,
        string $yield_offering_id
        );
```

### Update customer
```php
ovalfi()->updateCustomer(
        string $customer_id,
        string $name = null,
        string $mobile_number = null,
        string $email = null,
        string $reference = null,
        string $yield_offering_id = null
    );
```
### Get customers
```php
ovalfi()->getCustomers();
```

### Get customer
```php
ovalfi()->getCustomer(
        string $customer_id
    );
```

### Get exchange rate
```php
ovalfi()->getExchangeRate(
        float $amount,
        string $currency,
        string $destination_currency
    );
```

### Initiate transfer
NB: Please check this doc to understand the parameters better: https://docs.ovalfi.com/docs/initiate-transfer
```php
ovalfi()->initiateTransfer(
        string $customer_id,
        float $amount,
        string $currency,
        array $destination,
        string $reason,
        string $reference,
        string $note = null
    );
```

### Cancel transfer by batch ID
```php
ovalfi()->cancelTransferByBatchId(
        string $batch_id,
        string $reason
    );
```

### Get business portfolios
```php
ovalfi()->getBusinessPortfolios();
```

### Create yield offering profile
```php
ovalfi()->createYieldOfferingProfile(
        string $name,
        string $reference,
        string $description,
        string $portfolio_id = null,
        float $apy_rate = null,
        string $currency = null,
        int $deposit_lock_day = null,
        float $minimum_deposit_allowed = null,
        float $maximum_deposit_allowed = null,
        int $yieldable_after_day = null,
        float $withdrawal_limit_rate = null
    );
```

### Get yield profiles
```php
ovalfi()->getYieldProfiles();
```

### Get yield profile
```php
ovalfi()->getYieldProfile(
        string $yield_offering_id
    );
```

### Update yield offering profile
```php
ovalfi()->updateYieldOfferingProfile(
        string $yield_offering_id,
        string $name = null,
        string $reference = null,
        string $description = null,
        string $portfolio_id = null,
        float $apy_rate = null,
        string $currency = null,
        int $deposit_lock_day = null,
        float $minimum_deposit_allowed = null,
        float $maximum_deposit_allowed = null,
        int $yieldable_after_day = null,
        float $withdrawal_limit_rate = null
    );
```

### Get customer balance
```php
ovalfi()->getCustomerBalance(
        string $customer_id
    );
```

### Initiate savings deposit
```php
ovalfi()->initiateSavingsDeposit(
        string $customer_id,
        string $reference,
        float $amount
    );
```


### Get deposits
```php
ovalfi()->getDeposits();
```

### Get deposit by batch ID
```php
ovalfi()->getDepositByBatchId(
        string $batch_id
    );
```

### Initiate savings withdrawal
```php
ovalfi()->initiateSavingsWithdrawal(
        string $customer_id,
        string $reference,
        float $amount
    );
```


### Security

If you discover any security related issues, please email adamsohiani@gmail.com instead of using the issue tracker.

## Credits

-   [Paul Adams](https://github.com/zeevx)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
