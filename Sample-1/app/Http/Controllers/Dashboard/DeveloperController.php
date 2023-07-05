<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeveloperRequest;
use App\Models\CollectionImage;
use App\Models\Developer;
use App\Models\Image;
use App\Services\DeveloperService;
use App\Services\ImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class DeveloperController extends Controller
{
    protected const OPERATOR_DASH_PAGINATE = 20;

    public function __construct(
        private ImageService $imageService,
        private DeveloperService $developerService
    ) {
    }

    public function index(): RedirectResponse|Response
    {
        if (
            Gate::denies('view', 'developer')
            && !auth()->user()->super_admin
        ) {
            session()->flash('warning', 'You don\'t have permission to view this page');

            return to_route('admin.index');
        }

        return response()->view('dashboard.developers.list', [
            'developers' => Developer::dashList()->paginate(self::OPERATOR_DASH_PAGINATE)
        ]);
    }

    public function create(): RedirectResponse|Response
    {
        if (
            Gate::denies('create', 'developer')
            && !auth()->user()->super_admin
        ) {
            session()->flash('warning', 'You don\'t have permission to view this page');

            return to_route('admin.index');
        }

        return response()->view('dashboard.developers.create', [
            'developerPhotos' => Image::createImages(CollectionImage::developerCollections()->get()?->toArray() ?? [])
                ->paginate(IMG_LIMIT_IN_LIST)
        ]);
    }

    public function store(DeveloperRequest $request): RedirectResponse|Response
    {
        if (
            Gate::denies('create', 'developer')
            && !auth()->user()->super_admin
        ) {
            session()->flash('warning', 'You don\'t have permission to view this page');

            return to_route('admin.index');
        }

        $developer = $this->developerService->createDeveloper($request);

        if ($developer) {
            $this->imageService->sync($developer->logo, $developer);
            Cache::tags(['slot', 'developer'])->flush();
            session()->flash('message', 'Developer successfully created');
        } else {
            Log::error('User ' . auth()->user()->email . ';' . __METHOD__);
            session()->flash('error', 'Something is wrong, Developer didn\'t created');
        }

        return to_route('developers.index');
    }

    public function show(string $id)
    {
        return 'TODO';
    }

    public function edit(Developer $developer): RedirectResponse|Response
    {
        if (
            Gate::denies('edit', 'developer')
            && !auth()->user()->super_admin
        ) {
            session()->flash('warning', 'You don\'t have permission to view this page');

            return to_route('admin.index');
        }

        return response()->view('dashboard.developers.edit', [
            'developer' => $developer,
            'developerPhotos' => Image::createImages(CollectionImage::developerCollections()->get()?->toArray() ?? [])
                ->paginate(IMG_LIMIT_IN_LIST)
        ]);
    }

    public function update(DeveloperRequest $request, Developer $developer): RedirectResponse|Response
    {
        if (
            Gate::denies('edit', 'developer')
            && !auth()->user()->super_admin
        ) {
            session()->flash('warning', 'You don\'t have permission to view this page');

            return to_route('admin.index');
        }

        $developerUpdated = $this->developerService->updateDeveloper($request, $developer);

        if ($developerUpdated) {
            $this->imageService->sync($developerUpdated->logo, $developerUpdated);
            Cache::tags(['slot', 'developer'])->flush();
            session()->flash('message', 'Developer successfully updated');
        } else {
            Log::error('User ' . auth()->user()->email . ';' . __METHOD__);
            session()->flash('error', 'Something is wrong, Developer didn\'t updated');
        }

        return to_route('developers.index');
    }

    public function destroy(string $id): RedirectResponse
    {
        if (
            Gate::denies('destroy', 'developer')
            && !auth()->user()->super_admin
        ) {
            session()->flash('warning', 'You do not have permission to view this page');

            return to_route('admin.index');
        }
        $developer = Developer::find((int) $id);
        $developer->loadMissing('slots');

        if ($developer->slots->count() !== 0) {
            session()->flash('warning', 'You can not delete developer with slot/s');

            return to_route('developers.index');
        }

        if ($developer->delete()) {
            $this->imageService->sync(null, $developer);
            Cache::tags(['slot', 'developer'])->flush();
            session()->flash('message', 'Developer was deleted');
        } else {
            session()->flash('error', 'Couldn\'t delete Developer');
        }

        return back();
    }
}
