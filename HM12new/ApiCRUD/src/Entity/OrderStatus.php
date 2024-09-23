<?php

namespace App\Entity;

enum OrderStatus: string
{

    case Pending = "Pending";
    case Completed = "Completed";
    case Cancelled = "Cancelled";
}