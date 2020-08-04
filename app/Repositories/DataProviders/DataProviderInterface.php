<?php

namespace App\Repositories\DataProviders;


/**
 * Interface DataProviderInterface
 * @package App\Repositories\DataProviders
 */
interface DataProviderInterface
{

    /**
     * @return mixed
     */
    public function readData();

    /**
     * @param $data
     * @return mixed
     */
    public function mapData($data);

}
