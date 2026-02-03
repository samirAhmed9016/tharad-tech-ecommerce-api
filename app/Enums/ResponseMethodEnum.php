<?php

namespace App\Enums;

enum ResponseMethodEnum: string
{
    case STORE = 'store';
    case UPDATE = 'update';
    case DESTROY = 'destroy';
    case SERVER_ERROR = 'server_error';
    case INDEX = 'index';
    case SHOW = 'show';
    case UNPROCESSABLE = 'unprocessable';
    case SINGLE = 'single';
    case COLLECTION = 'collection';
    case CUSTOM = 'custom';
}
