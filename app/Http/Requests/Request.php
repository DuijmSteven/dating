<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class Request
 * @package App\Http\Requests
 */
abstract class Request extends FormRequest
{
    /**
     * @return array
     */
    public function formatInput()
    {
        $requestInput = $this->all();
        $this->emptyToNull($requestInput);
        $this->replace($requestInput);

        return $this->all();
    }

    /**
     * @param array $array
     * @return array
     */
    protected function emptyToNull(array &$array)
    {
        foreach ($array as $key => $value) {
            if ($value == '') {
                $array[$key] = null;
            }
        }
        return $array;
    }

    /**
     * @param array $array
     * @return array
     */
    public function removeNull()
    {
        $this->replace($this->deepFilter($this->all()));
    }

    /**
     * @param array $array
     * @return array
     */
    protected function deepFilter(array $array)
    {
        // Formally this is not need because if array is empty then $filteredArray will also be empty
        // but it simplifies the algorithm
        if (empty($array)) {
            return [];
        }

        $filteredArray = [];
        foreach ($array as $key => $value) {
            if (is_array($value) && !empty($value)) {
                $value = $this->deepFilter($value);
            }
            if (!empty($value)) {
                $filteredArray[$key] = $value;
            }
        }

        return $filteredArray;
    }
}
