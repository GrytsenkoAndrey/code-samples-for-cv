<?php
declare(strict_types=1);

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Relations\MorphToMany;

interface HasImgInterface
{
    public function images(): MorphToMany;
}
