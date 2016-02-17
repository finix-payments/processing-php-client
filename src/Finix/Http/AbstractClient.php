<?php

namespace Finix\Http;

interface AbstractClient {

    /**
     * @param $request    Request    The Request object containing all the parameters
     *                            necessary for the HTTP request.
     *
     * @return Response    The Response object returned by the server.
     */
    public function sendRequest($request);

}