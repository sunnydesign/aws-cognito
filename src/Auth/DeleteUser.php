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

trait DeleteUser
{

    /**
     * @param string $username
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function deleteUser(string $username)
    {
        return app()->make(AwsCognitoClient::class)->deleteUser($username);
    }

}
