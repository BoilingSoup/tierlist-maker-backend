<?php

namespace App\Http\Controllers;

use App\Repositories\TierListRepository;
use Illuminate\Http\Request;

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
    public function store(Request $request)
    {
    //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
    //
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
     * Get the 5 most recent public tier lists to display on home page carousel.
     */
    public function recent()
    {
        // TODO: maybe put this in a Resource class.
        return $this->repository->recent();
    }
}
