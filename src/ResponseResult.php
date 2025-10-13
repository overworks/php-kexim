<?php

namespace Minhyung\Kexim;

enum ResponseResult: int
{
    case SUCCESS = 1;
    case INVALID_DATA_CODE = 2;
    case INVALID_AUTH_KEY = 3;
    case QUOTA_EXCEEDED = 4;
}
