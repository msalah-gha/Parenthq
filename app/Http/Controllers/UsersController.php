<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserProviderRequest;
use App\Services\UserService;

use Illuminate\Http\Request;

class UsersController extends Controller
{
    protected $userService;
    protected $request;

    public function __construct(UserService $userService, Request $request)
    {
        $this->userService = $userService;
        $this->request = $request;
    }

    /**
     * @param UserProviderRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function index(UserProviderRequest $request)
    {
        $requestData = $this->request->all();
        if(!empty($requestData)) {

            $this->userService->setFiltersForProviders($this->request);
        }

        $usersData = $this->userService->getProvidersData();

        if(!$usersData){
            return $this->failure('No available data match your search');
        }

        return $this->success('Users listed successfully', $usersData);
    }
}
