<?php

    /**
    * make the uri from provider
    * @param string $claims
    * @param string $state
    * @param string $uri_login
    * @param string $client
    * @param string $uri_red
    * @return string
    */
    function mountUri(string $claims, string $state, string $uri_login, string $client, string $uri_red): string
    {
        return sprintf(
            '%s?response_type=code&client_id=%s&redirect_uri=%s&scope=%s&state=%s&claims=%s',
            $uri_login,
            $client,
            $uri_red,
            'channel:read:polls+openid+user:read:email',
            $state,
            $claims
        );
    }