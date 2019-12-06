<?php

namespace Modules\User\Services;

use Socialite;
use Adaojunior\Passport\SocialGrantException;
use Adaojunior\Passport\SocialUserResolverInterface;
use Modules\User\Repositories\Contracts\UserRepositoryInterface as UserRepository;

class SocialUserResolver implements SocialUserResolverInterface
{
    /*
    |--------------------------------------------------------------------------
    | Social User Resolver
    |--------------------------------------------------------------------------
    |
    | This class handles the login and registration of social users as well as
    | their validation and creation. The logic to create users is implemented
    | on Modules\User\Services\RegistersUsers service using UserRepository to
    | access model layer.
    |
    */

    use RegistersUsers;

    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Resolves user by given network and access token.
     *
     * @param string $network
     * @param string $accessToken
     * @return \Illuminate\Contracts\Auth\Authenticatable
     */
    public function resolve($network, $accessToken, $accessTokenSecret = null)
    {
        switch ($network) {
            case 'facebook':
                return $this->authWithFacebook($accessToken);
                break;
            default:
                throw SocialGrantException::invalidNetwork();
                break;
        }
    }

    /**
     * Resolves user by facebook access token.
     *
     * @param string $accessToken
     * @return \Modules\User\Entities\User
     */
    protected function authWithFacebook($accessToken)
    {
        $socialUser = Socialite::driver('facebook')->userFromToken($accessToken);

        $facebookUser = $this->userRepository->findByFacebookId($socialUser->getId());

        if ($facebookUser) {
            return $facebookUser;
        }

        $defaultUser = $this->userRepository->findByEmail($socialUser->getEmail());

        if (! $defaultUser) {
            return $this->register([
                'name'          => $socialUser->getName(),
                'email'         => $socialUser->getEmail(),
                'password'      => sha1($socialUser->getId().date('d-m-Y h:i:s')),
                'facebook_id'   => $socialUser->getId(),
            ]);
        }

        return $this->userRepository->update($defaultUser->id, [
            'facebook_id' => $socialUser->getId(),
        ]);
    }
}
