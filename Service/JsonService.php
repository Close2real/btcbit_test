<?php

class JsonService
{

    /**
     * @param string $jsonArrayString
     * @return bool
     * @throws Exception
     */
    public function checkJson(string $jsonArrayString): bool
    {
        $jsonArray = json_decode($jsonArrayString);

        if(is_array($jsonArray)) {
            return true;
        } else {
            throw new Exception("String is not an JSON array. Please fix your input and try again");
        }
    }

    /**
     * @param string $jsonArrayString
     * @return array
     */
    public function returnDecodedJson(string $jsonArrayString): array
    {
        return json_decode($jsonArrayString);
    }

    /**
     * @param array $jsonArray
     * @return string
     */
    public function returnEncodedJson(array $jsonArray): string
    {
        return json_encode($jsonArray);
    }
}