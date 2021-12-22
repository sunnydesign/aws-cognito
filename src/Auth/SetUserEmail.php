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

use Sunnydesign\Cognito\AwsCognitoClient;

trait SetUserEmail
{

    /**
     * @param string $username
     * @param string $email
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function setUserEmail(
        string $username,
        string $email
    )
    {
        return app()->make(AwsCognitoClient::class)->setUserAttributes(
            $username,
            [
                'email' => $email
            ]
        );
    }

}
