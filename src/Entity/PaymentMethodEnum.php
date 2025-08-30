<?php

namespace App\Entity;

enum PaymentMethodEnum: string
{
    case CASH = 'cash';
    case CREDIT_CARD = 'credit_card';
    case DEBIT_CARD = 'debit_card';
    case BANK_TRANSFER = 'bank_transfer';
    case CHECK = 'check';
    case DIGITAL_WALLET = 'digital_wallet';
}
