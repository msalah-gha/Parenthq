<?php

namespace App\Repositories\DataProviders;

use App\Enums\DataProviders\DataProviderX as DataProviderXEnum;

class DataProviderX implements DataProviderInterface
{

    /**
     * @return mixed
     */
    public function readData()
    {
        $path   = storage_path() . "/DataProviders/DataProviderX.json";
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
            $mappedData[$index]['id']         = $row['parentIdentification'];
            $mappedData[$index]['statusCode'] = $this->getMappedCodeByStatusCode($row['statusCode']);
            $mappedData[$index]['balance']    = $row['parentAmount'];
            $mappedData[$index]['currency']   = $row['Currency'];
            $mappedData[$index]['email']      = $row['parentEmail'];
            $mappedData[$index]['created_at'] = $row['registerationDate'];
            $mappedData[$index]['provider']   = 'DataProviderX';

        }

        return $mappedData;
    }

    private function getMappedCodeByStatusCode($code)
    {
       return DataProviderXEnum::DataProviderXStatusCodes[$code];
    }

}
