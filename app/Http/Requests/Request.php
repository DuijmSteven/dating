<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class Request extends SymfonY
{
    public function formatInput()
    {
        $requestInput = $this->all();
        $this->emptyToNull($requestInput);
        $this->replace($requestInput);

        return $this->all();
    }

    private function emptyToNull(array &$array)
    {
        foreach ($array as $key => $value) {
            if ($value == '') {
                $array[$key] = null;
            }
        }
        return $array;
    }
}
