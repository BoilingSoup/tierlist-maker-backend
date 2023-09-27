<?php

namespace App\Http\Controllers;

use App\Helpers\AuthorizationHelper;
use App\Http\Requests\SaveNewTierListRequest;
use App\Repositories\TierListRepository;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class TierListController extends Controller
{
    protected TierListRepository $repository;

    public function __construct(TierListRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SaveNewTierListRequest $request)
    {
      $validated = (array) $request->validated();

      // TODO: thumbnail and image upload

      $savedData = $this->repository->store($validated);

      return $savedData;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $uuid)
    {
      if (! Uuid::isValid($uuid)) {
        abort(404);

        return;
      }

      $tierList = $this->repository->getOrFail($uuid);

      if (! AuthorizationHelper::canShowTierList($tierList)) {
        abort(404);

        return;
      }

      return $tierList;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
    //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
    //
    }

    /**
     * Get the 4 most recent public tier lists to display on home page carousel.
     */
    public function recent()
    {
        return $this->repository->recent();
    }
}
