<?php

namespace App\Services;

use App\Entity\Commande;

class OrderService
{
  public function createOrderCode(?Commande $lastOrder)
  {
    $stringDate = str_replace("/", "", (string) date("d/m/Y"));
    if($lastOrder){
      $lastOrderCode = $lastOrder->getCodeCmd();
      $tabCode = explode('/', $lastOrderCode);
      $lastNumber = substr($tabCode[0], 3, null);
      $currentNumber = (integer)$lastNumber + 1;

      if ( strlen((string) $currentNumber < 4 )) {
        $currentNumber = str_pad($currentNumber, 4, "0", STR_PAD_LEFT);
      }

      return "CMD" . "" . $currentNumber . "/" . $stringDate;
    }
    else{
      return "CMD0001" . "/" . $stringDate;
    }
    
    
  }
}