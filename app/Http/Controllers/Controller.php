<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Helpers\ResponseHelper;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * SUCCESS RESPONSE SHORTCUT
     */
    protected function success($data = null, $message = 'Success', $code = 200)
    {
        return ResponseHelper::success($data, $message, $code);
    }

    /**
     * ERROR RESPONSE SHORTCUT
     */
    protected function error($message = 'Error', $code = 400, $data = null)
    {
        return ResponseHelper::error($message, $code, $data);
    }

    /**
     * NOT FOUND SHORTCUT
     */
    protected function notFound($message = 'Data not found')
    {
        return ResponseHelper::notFound($message);
    }

    /**
     * UNAUTHORIZED SHORTCUT
     */
    protected function unauthorized($message = 'Unauthorized')
    {
        return ResponseHelper::unauthorized($message);
    }

    /**
     * VALIDATION ERROR SHORTCUT
     */
    protected function validationError($errors, $message = 'Validation Error')
    {
        return ResponseHelper::validation($errors, $message);
    }
}