<?php

namespace App\Http\Controllers;

use SMD\Common\ReservationSystem\Helpers\GeneralHelper;
use Symfony\Component\HttpFoundation\Response as ResponseCode;

abstract class AppBaseController extends Controller
{

    /**
     * Send a JSON successful response (done => true)
     * @param array|null $array Data
     * @return \Illuminate\Http\JsonResponse
     */
    protected function jsonSuccessResponse(array $array = null)
    {
        if (is_null($array)) {
            $array = array();
        }
        $array['done'] = true;
        return response()->json($array);
    }

    /**
     * Send a JSON error response (done => false, error => $error) in a 200 HTTP response.
     * @param mixed|null $error
     * @param array|null $array
     * @return \Illuminate\Http\JsonResponse
     */
    protected function jsonErrorResponse($error = null, array $array = null)
    {
        if (is_null($array)) {
            $array = array();
        }
        if (GeneralHelper::isNullOrEmpty($error)) {
            $error = trans('general.error.occurred');
        }
        $array['done'] = false;
        $array['error'] = $error;
        return response()->json($array);
    }

    /**
     * Send a JSON error response (done => false, error => $error -default to: No change required-) in a 200 HTTP response.
     * @param array|null $array
     * @param mixed|null $error
     * @return \Illuminate\Http\JsonResponse
     */
    protected function jsonNoChangeRequiredResponse(array $array = null, $error = null)
    {
        if (GeneralHelper::isNullOrEmpty($error)) {
            $error = trans('general.no_changed_required');
        }
        return $this->jsonErrorResponse($error, $array);
    }

    /**
     * Reply with Not found HTTP response
     * @param mixed|null $message
     * @return \Illuminate\Contracts\Routing\ResponseFactory|ResponseCode
     */
    protected function notFoundResponse($message = null)
    {
        if (GeneralHelper::isNullOrEmpty($message)) {
            $message = trans('general.not_found');
        }
        return response($message, ResponseCode::HTTP_NOT_FOUND);
    }

    /**
     * General error processing request
     * @param mixed|null $message
     * @return \Illuminate\Contracts\Routing\ResponseFactory|ResponseCode
     */
    protected function errorProcessingResponse($message = null)
    {
        if (GeneralHelper::isNullOrEmpty($message)) {
            $message = trans('general.error.processing_request');
        }
        return response($message, ResponseCode::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Reply with Not found HTTP response
     * @param mixed|null $message
     * @return \Illuminate\Contracts\Routing\ResponseFactory|ResponseCode
     */
    protected function badRequestResponse($message = null)
    {
        if (GeneralHelper::isNullOrEmpty($message)) {
            $message = trans('general.bad_request');
        }
        return response($message, ResponseCode::HTTP_BAD_REQUEST);
    }


}
