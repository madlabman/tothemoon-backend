<?php
/**
 * Created by IntelliJ IDEA.
 * User: admin
 * Date: 21/08/2018
 * Time: 22:27
 */

namespace App\Library;

class BitApsResponse
{
    /**
     * @var string
     */
    private $address;

    /**
     * @var string
     */
    private $invoice;

    /**
     * @var string
     */
    private $payment_code;

    /**
     * @var bool
     */
    private $is_valid = false;

    /**
     * @return null|string
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    /**
     * @return null|string
     */
    public function getInvoice(): ?string
    {
        return $this->invoice;
    }

    /**
     * @param string $invoice
     */
    public function setInvoice(string $invoice): void
    {
        $this->invoice = $invoice;
    }

    /**
     * @return null|string
     */
    public function getPaymentCode(): ?string
    {
        return $this->payment_code;
    }

    /**
     * @param string $payment_code
     */
    public function setPaymentCode(string $payment_code): void
    {
        $this->payment_code = $payment_code;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->is_valid;
    }

    public function setIsValid(bool $is_valid)
    {
        $this->is_valid = $is_valid;
    }
}