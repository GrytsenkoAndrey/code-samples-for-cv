<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Developer;
use Illuminate\Http\Request;

class DeveloperService
{
    public function createDeveloper(Request $request): Developer
    {
        $developer = Developer::create($request->validated()
            + [
                'logo' => $request->logo,
            ]
        );

        return $developer;
    }

    public function updateDeveloper(Request $request, Developer $developer): Developer|null
    {
        if ($developer->update($request->validated()
            + [
                'logo' => $request->logo,
            ]
        )) {
            return $developer;
        }

        return null;
    }
}
