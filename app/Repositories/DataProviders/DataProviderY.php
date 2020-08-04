<?php

namespace App\Repositories\DataProviders;

use App\Enums\DataProviders\DataProviderY as DataProviderYEnum;

class DataProviderY implements DataProviderInterface
{

    /**
     * @return mixed
     */
    public function readData()
    {

        $path   = storage_path() . "/DataProviders/DataProviderY.json";
        $json   = json_decode(file_get_contents($path), true)['users'];

        return $this->mapData($json);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function mapData($data)
    {
        $mappedData = [];
        foreach ($data as $index => $row)
        {
            $mappedData[$index]['id']         = $row['id'];
            $mappedData[$index]['statusCode'] = $this->getMappedCodeByStatusCode($row['status']);
            $mappedData[$index]['balance']    = $row['balance'];
            $mappedData[$index]['currency']   = $row['currency'];
            $mappedData[$index]['email']      = $row['email'];
            $mappedData[$index]['created_at'] = $row['created_at'];
            $mappedData[$index]['provider']   = 'DataProviderY';

        }
        return $mappedData;
    }

    private function getMappedCodeByStatusCode($code)
    {
        return DataProviderYEnum::DataProviderYStatusCodes[$code];
    }

}
