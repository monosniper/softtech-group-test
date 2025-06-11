<?php

namespace App\Enums;

enum MediaType: string
{
    case LEADS = 'leads';
    case DEALS = 'deals';
    case TASKS = 'tasks';
    case PRODUCTS = 'products';
    case CHATS = 'chats';
}
