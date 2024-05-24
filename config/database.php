<?php

use App\Database\DB;

return [
    DB::class => function () {
        return new DB(
            'localhost',
            'easecloud_api',
            'easeAdmin.123',
            '.Hys^#&Oghks!GppwBb)0O'
        );
    }
];
