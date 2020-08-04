<?php

namespace App\Services;

/**
 * Class UserService
 *
 * @package App\Http\Services
 */
class UserService
{

    const REPOSITORIES_PROVIDERS_BASE_PATH = 'App\\Repositories\\DataProviders\\';

    protected $providers;

    protected $providersFiltersArray;

    public function setProvider($providersList)
    {
        $this->providers = $providersList;
    }


    /**
     * Get list of Providers that we collect data from, as getting this list from our config
     *
     * @return array
     */
    private function getProviders()
    {
        if(empty($this->providers)) {

            $this->providers   = $this->getActiveUsersProviders();
        }

       return $this->providers;
    }

    /**
     *
     */
    public function getProvidersData()
    {
        // get providers list in order to read their data
        $providersArr = $this->getProviders();

        $result = [];

        foreach ($providersArr as $provider) {

        $providerClass = self::REPOSITORIES_PROVIDERS_BASE_PATH.$provider;
        $providerInstance = new $providerClass;

            $result[] = $providerInstance->readData();
        }

        $users  = call_user_func_array('array_merge', $result);

        // check if there are filters set
        if(!empty($this->providersFiltersArray))
        {
            return $this->applyFiltersAndReturnData($users);
        }

        return $users;
    }


    public function setFiltersForProviders($filters)
    {
        $this->providersFiltersArray = $filters;
    }

    private function applyFiltersAndReturnData($usersData)
    {

        $filters   = collect($this->providersFiltersArray->only([
            'provider', 'statusCode', 'currency'
          ]));

        $usersData = collect($usersData);

        $filters->filter()->each(function ($value, $name) use (&$usersData) {
            $usersData = $usersData
                ->filter(function ($userData) use ($name, $value) {
                    return $userData[$name] === $value;
                })
                ->values();
        });


        $balanceMin = $this->providersFiltersArray->input('balanceMin');
        $balanceMax = $this->providersFiltersArray->input('balanceMax');

        if ($balanceMin && $balanceMax) {
            $usersData = $usersData
                ->filter(function ($userData) use ($balanceMin , $balanceMax) {
                    $balance = $userData['balance'];

                    return $balance >= $balanceMin && $balance <= $balanceMax;
                })
                ->values();

        }elseif ($balanceMin) {
            $usersData = $usersData
                ->filter(function ($userData) use ($balanceMin) {

                    return $userData['balance'] >= $balanceMin;
                })
                ->values();
        }elseif ($balanceMax) {
            $usersData = $usersData
                ->filter(function ($userData) use ($balanceMax) {

                    return $userData['balance'] <= $balanceMax;
                })
                ->values();
        }

        return $usersData->all();
    }

    /**
     * @return array
     */
    public function getActiveUsersProviders(): array
    {
        return config('DataProviders.dataProviders')['dataProviders'];
    }

}
