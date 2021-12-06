<?php

/*
 * This file is part of AWS Cognito Auth solution.
 *
 * (c) EllaiSys <support@ellaisys.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sunnydesign\Cognito\Auth;

use Sunnydesign\Cognito\AwsCognitoClaim;
use Sunnydesign\Cognito\AwsCognitoClient;
use Sunnydesign\Cognito\AwsCognitoManager;

trait RefreshToken
{

    /**
     * @param string $refreshToken
     * @param string $username
     * @param \Illuminate\Contracts\Auth\Authenticatable $user
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \Exception
     */
    protected function refreshToken(
        string $refreshToken,
        string $username,
        \Illuminate\Contracts\Auth\Authenticatable $user
    )
    {
        $response = app()->make(AwsCognitoClient::class)->refreshToken($refreshToken, $username);

        $claim = new AwsCognitoClaim($response, $user, $username);

        $manager = app()->make(AwsCognitoManager::class);
        $manager->encode($claim)->store();

        return $response;
    } //Function ends

} //Trait ends
