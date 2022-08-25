<?php

namespace Zeevx\LaravelOvalfi;

use Illuminate\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Http;

class LaravelOvalfi
{
    /**
     * @var Repository|Application|mixed
     */
    private $public_key;

    /**
     * @var Repository|Application|mixed
     */
    private $bearer_token;

    /**
     * @var Repository|Application|mixed
     */
    private $mode;

    /**
     * @var Repository|Application|mixed
     */
    private $base_url;

    public function __construct()
    {
        $this->mode = config('ovalfi.mode', 'sandbox');
        $sandbox = config('ovalfi.sandbox_url');
        $live = config('ovalfi.live_url');
        $this->base_url = $this->mode == 'sandbox' ? $sandbox : $live;
        $this->public_key = config('ovalfi.public_key');
        $this->bearer_token = config('ovalfi.bearer_token');
    }

    /**
     * @param  string  $endpoint
     * @param  string  $method
     * @param  array|null  $data
     * @return mixed
     * Handles all request
     */
    private function makeRequest(
        string $endpoint,
        string $method,
        array $data = null
    ) {
        $headers = [
            'Authorization' => "Bearer {$this->bearer_token}",
        ];
        if ($method == 'POST' && isset($data['reference'])) {
            $key = "{$this->public_key}{$data['reference']}";
            $signature = hash('sha256', $key);
            $headers['Signature'] = $signature;
        }

        return Http::withHeaders($headers)
            ->$method("{$this->base_url}{$endpoint}", $data)
            ->json();
    }

    /**
     * @param  string  $name
     * @param  string  $mobile_number
     * @param  string  $email
     * @param  string  $reference
     * @param  string  $yield_offering_id
     * @return mixed
     * Create customer
     */
    public function createCustomer(
        string $name,
        string $mobile_number,
        string $email,
        string $reference,
        string $yield_offering_id
    ) {
        $data = [
            'name' => $name,
            'mobile_number' => $mobile_number,
            'email' => $email,
            'reference' => $reference,
            'yield_offering_id' => $yield_offering_id,
        ];

        return $this->makeRequest('customer', 'POST', $data);
    }

    /**
     * @param  string  $customer_id
     * @param  string|null  $name
     * @param  string|null  $mobile_number
     * @param  string|null  $email
     * @param  string|null  $reference
     * @param  string|null  $yield_offering_id
     * @return mixed
     * Update customer
     */
    public function updateCustomer(
        string $customer_id,
        string $name = null,
        string $mobile_number = null,
        string $email = null,
        string $reference = null,
        string $yield_offering_id = null
    ) {
        $data = [
            'customer_id' => $customer_id,
            'name' => $name,
            'mobile_number' => $mobile_number,
            'email' => $email,
            'reference' => $reference,
            'yield_offering_id' => $yield_offering_id,
        ];

        return $this->makeRequest('customer', 'PATCH', $data);
    }

    /**
     * @return mixed
     * Get customers
     */
    public function getCustomers()
    {
        return $this->makeRequest('customer', 'GET');
    }

    /**
     * @param  string  $customer_id
     * @return mixed
     * Get customer
     */
    public function getCustomer(
        string $customer_id
    ) {
        $endpoint = "customer/{$customer_id}";

        return $this->makeRequest($endpoint, 'GET');
    }

    /**
     * @param  float  $amount
     * @param  string  $currency
     * @param  string  $destination_currency
     * @return mixed
     * Get exchange rate
     */
    public function getExchangeRate(
        float $amount,
        string $currency,
        string $destination_currency
    ) {
        $data = [
            'amount' => $amount,
            'currency' => $currency,
            'destination_currency' => $destination_currency,
        ];

        return $this->makeRequest('transfer/detail', 'GET', $data);
    }

    /**
     * @param  string  $customer_id
     * @param  float  $amount
     * @param  string  $currency
     * @param  array<string, array>  $destination
     * @param  string  $reason
     * @param  string  $reference
     * @param  string|null  $note
     * @return mixed
     * Initiate transfer
     */
    public function initiateTransfer(
        string $customer_id,
        float $amount,
        string $currency,
        array $destination,
        string $reason,
        string $reference,
        string $note = null
    ) {
        $data = [
            'customer_id' => $customer_id,
            'amount' => $amount,
            'currency' => $currency,
            'destination' => $destination,
            'reason' => $reason,
            'reference' => $reference,
            'note' => $note,
        ];

        return $this->makeRequest('transfer', 'POST', $data);
    }

    /**
     * @param  string  $batch_id
     * @param  string  $reason
     * @return mixed
     * Cancel transfer by batch ID
     */
    public function cancelTransferByBatchId(
        string $batch_id,
        string $reason
    ) {
        $endpoint = "transfer/{$batch_id}";
        $data = [
            'reason' => $reason,
        ];

        return $this->makeRequest($endpoint, 'DELETE', $data);
    }

    /**
     * @return mixed
     * Get business portfolio
     */
    public function getBusinessPortfolios()
    {
        return $this->makeRequest('configuration/portfolio', 'GET');
    }

    /**
     * @param  string  $name
     * @param  string  $reference
     * @param  string  $description
     * @param  string|null  $portfolio_id
     * @param  float|null  $apy_rate
     * @param  string|null  $currency
     * @param  int|null  $deposit_lock_day
     * @param  float|null  $minimum_deposit_allowed
     * @param  float|null  $maximum_deposit_allowed
     * @param  int|null  $yieldable_after_day
     * @param  float|null  $withdrawal_limit_rate
     * @return mixed
     * Create yield offering profile
     */
    public function createYieldOfferingProfile(
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
    ) {
        $data = [
            'portfolio_id' => $portfolio_id,
            'name' => $name,
            'description' => $description,
            'apy_rate' => $apy_rate,
            'currency' => $currency,
            'deposit_lock_day' => $deposit_lock_day,
            'minimum_deposit_allowed' => $minimum_deposit_allowed,
            'maximum_deposit_allowed' => $maximum_deposit_allowed,
            'yieldable_after_day' => $yieldable_after_day,
            'withdrawal_limit_rate' => $withdrawal_limit_rate,
            'reference' => $reference,
        ];

        return $this->makeRequest('configuration/yield-offering', 'POST', $data);
    }

    /**
     * @return mixed
     * Get yield profiles
     */
    public function getYieldProfiles()
    {
        return $this->makeRequest('configuration/yield-offering', 'GET');
    }

    /**
     * @return mixed
     * Get yield profile
     */
    public function getYieldProfile(
        string $yield_offering_id
    ) {
        $endpoint = "configuration/yield-offering/{$yield_offering_id}";

        return $this->makeRequest($endpoint, 'GET');
    }

    /**
     * @param  string  $yield_offering_id
     * @param  string|null  $name
     * @param  string|null  $reference
     * @param  string|null  $description
     * @param  string|null  $portfolio_id
     * @param  float|null  $apy_rate
     * @param  string|null  $currency
     * @param  int|null  $deposit_lock_day
     * @param  float|null  $minimum_deposit_allowed
     * @param  float|null  $maximum_deposit_allowed
     * @param  int|null  $yieldable_after_day
     * @param  float|null  $withdrawal_limit_rate
     * @return mixed
     * Update yield offering profile
     */
    public function updateYieldOfferingProfile(
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
    ) {
        $data = [
            'yield_offering_id' => $yield_offering_id,
            'portfolio_id' => $portfolio_id,
            'name' => $name,
            'description' => $description,
            'apy_rate' => $apy_rate,
            'currency' => $currency,
            'deposit_lock_day' => $deposit_lock_day,
            'minimum_deposit_allowed' => $minimum_deposit_allowed,
            'maximum_deposit_allowed' => $maximum_deposit_allowed,
            'yieldable_after_day' => $yieldable_after_day,
            'withdrawal_limit_rate' => $withdrawal_limit_rate,
            'reference' => $reference,
        ];

        return $this->makeRequest('configuration/yield-offering', 'PUT', $data);
    }

    /**
     * @param  string  $customer_id
     * @return mixed
     * Get customer balance
     */
    public function getCustomerBalance(
        string $customer_id
    ) {
        $endpoint = "customer/balance/{$customer_id}";

        return $this->makeRequest($endpoint, 'GET');
    }

    /**
     * @param  string  $customer_id
     * @param  string  $reference
     * @param  float  $amount
     * @return mixed
     * Initiate savings deposit
     */
    public function initiateSavingsDeposit(
        string $customer_id,
        string $reference,
        float $amount
    ) {
        $data = [
            'customer_id' => $customer_id,
            'amount' => $amount,
            'reference' => $reference,
        ];

        return $this->makeRequest('deposit', 'POST', $data);
    }

    /**
     * @return mixed
     * Get deposits
     */
    public function getDeposits()
    {
        return $this->makeRequest('deposit', 'GET');
    }

    /**
     * @param  string  $batch_id
     * @return mixed
     * Get deposit by batch ID
     */
    public function getDepositByBatchId(
        string $batch_id
    ) {
        $endpoint = "deposit/{$batch_id}";

        return $this->makeRequest($endpoint, 'GET');
    }

    /**
     * @param  string  $customer_id
     * @param  string  $reference
     * @param  float  $amount
     * @return mixed
     * Initiate savings withdrawal
     */
    public function initiateSavingsWithdrawal(
        string $customer_id,
        string $reference,
        float $amount
    ) {
        $data = [
            'customer_id' => $customer_id,
            'reference' => $reference,
            'amount' => $amount,
        ];

        return $this->makeRequest('withdrawal', 'POST', $data);
    }
}
