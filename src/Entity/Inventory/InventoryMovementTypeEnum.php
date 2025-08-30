<?php

namespace App\Entity\Inventory;

enum InventoryMovementTypeEnum: string
{
    case IN = 'IN';
    case OUT = 'OUT';
    case ADJUSTMENT = 'ADJUSTMENT';
}