<?php

namespace App\Models;

use Core\Model;

class Work extends Model
{

    protected $table = "works";

    const STATUS = [
        1, // Planning
        2, // Doing
        3, // Complete
    ];

}
