<?php

namespace App\Http\Controllers\Apis;

use App\Collections\OrganizationUnits\OrganizationUnitListCollection;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Organizations\StoreOrganizationUnitRequest;
use App\Http\Requests\Organizations\UpdateOrganizationUnitRequest;
use App\Resources\Organizations\OrganizationUnitDetailResource;
use App\Services\Organizations\DeleteOrganizationUnitByIdService;
use App\Services\Organizations\GetOrganizationUnitByIdService;
use App\Services\Organizations\GetOrganizationUnitService;
use App\Services\Organizations\GetRoleByOrganizationIdsService;
use App\Services\Organizations\StoreOrganizationUnitService;
use App\Services\Organizations\UpdateOrganizationUnitService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrganizationUnitController extends ApiController
{
    /**
     * Get the organization note list
     *
     * @return OrganizationUnitListCollection
     */
    public function index(): OrganizationUnitListCollection
    {
        $tree = resolve(GetOrganizationUnitService::class)->run();

        return $this->formatJson(OrganizationUnitListCollection::class, $tree);
    }

    /**
     * Store the organization note
     *
     * @param StoreOrganizationUnitRequest $request
     * @return JsonResponse
     */
    public function store(StoreOrganizationUnitRequest $request): JsonResponse
    {
        $request = $request->validated();
        resolve(StoreOrganizationUnitService::class)->run($request);
        session()->flash('success', __('organization_create_successfully'));

        return $this->responseSuccess();
    }

    /**
     * Get the organization node by id
     *
     * @param int $id
     * @return OrganizationUnitDetailResource
     */
    public function show(int $id): OrganizationUnitDetailResource
    {
        $tree = resolve(GetOrganizationUnitByIdService::class)->run($id);

        return $this->formatJson(OrganizationUnitDetailResource::class, $tree);
    }

    /**
     * Update the organization note by id
     *
     * @param int $id
     * @param UpdateOrganizationUnitRequest $request
     * @return JsonResponse
     */
    public function update(int $id, UpdateOrganizationUnitRequest $request): JsonResponse
    {
        $request = $request->validated();
        resolve(UpdateOrganizationUnitService::class)->run($id, $request);
        session()->flash('success', __('organization_update_successfully'));

        return $this->responseSuccess();
    }

    /**
     * Delete the organization note by id
     *
     * @param int $id
     * @return JsonResponse
     */
    public function delete(int $id): JsonResponse
    {
        resolve(DeleteOrganizationUnitByIdService::class)->run($id);
        session()->flash('success', __('organization_delete_successfully'));

        return $this->responseSuccess();
    }

    /**
     * Retrieve roles by organization IDs.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function roleByOrganizationIds(Request $request): JsonResponse
    {
        $organizationIds = $request->input('organization_ids');
        $roles = resolve(GetRoleByOrganizationIdsService::class)->run($organizationIds);

        return $this->responseSuccessWithData($roles);
    }
}
