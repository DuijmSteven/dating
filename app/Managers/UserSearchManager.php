<?php

namespace App\Managers;

use App\Helpers\ApplicationConstants\PaginationConstants;
use App\Helpers\ApplicationConstants\UserConstants;
use App\Role;
use App\Services\GeocoderService;
use App\User;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

/**
 * Class UserSearchManager
 * @package App\Managers
 */
class UserSearchManager
{
    const ORDERING_TYPE_RANDOMIZED = 'randomized';
    const ORDERING_TYPE_RADIUS = 'radius';

    /**
     * @var User
     */
    private $user;

    /**
     * UserSearchManager constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Search users depending on fields from the
     * users table and the user_meta table.
     *
     * Returns Collection of users with meta sub-array
     *
     * @param array $parameters
     * @param bool $paginated
     * @param int $page
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|Collection|static[]
     * @throws \Exception
     */
    public function searchUsers(array $parameters, $paginated = false, $page = 1, array $ordering = null)
    {
        if (isset($ordering) && isset($ordering['randomization_key'])) {
            if (gettype($ordering['randomization_key']) !== 'string') {
                throw new \Exception('The ordering randomization key must be of type [string]');
            }
        }

        // initial part of query
        $query = $this->user->join('user_meta as um', 'users.id', '=', 'um.user_id')
            ->select('users.*')
            ->with(['meta', 'roles']);

        // append to query
        if (isset($parameters['query'])) {
            $query = $query->where('users.username', 'like', '%' . $parameters['query'] . '%')
                ->orWhere('users.email', 'like', '%' . $parameters['query'] . '%');
        } else {
            if (isset($parameters['username'])) {
                $query = $query->where('users.username', 'like', '%' . $parameters['username'] . '%');
            }

            if (isset($parameters['active'])) {
                $query = $query->where('users.active', '=', $parameters['active']);
            }

            foreach (UserConstants::selectableFields('peasant') as $field => $values) {
                if (isset($parameters[$field])) {
                    $query = $query->whereHas('meta', function ($query) use ($parameters, $field) {
                        $query->where($field, $parameters[$field]);
                    });
                }
            }

            if (isset($parameters['dob'])) {
                $query = $query->whereHas('meta', function ($query) use ($parameters) {
                    $query->where('dob', '>=', $parameters['dob']['min']);
                    $query->where('dob', '<=', $parameters['dob']['max']);
                });
            }

            if (isset($parameters['city_name'])) {
                $client = new Client();
                $geocoder = new GeocoderService($client);

                $coordinates = $geocoder->getCoordinatesForAddress($parameters['city_name']);

                $lat = $coordinates['lat'];
                $lng = $coordinates['lng'];

                $latInRadians = deg2rad($lat);
                $lngInRadians = deg2rad($lng);

                $angularRadius = $parameters['radius'] / 6371;

                $latMin = rad2deg($latInRadians - $angularRadius);
                $latMax = rad2deg($latInRadians + $angularRadius);

                $deltaLng = asin(sin($angularRadius) / cos($latInRadians));

                $lngMin = rad2deg($lngInRadians - $deltaLng);
                $lngMax = rad2deg($lngInRadians + $deltaLng);

                $query = $query->whereHas(
                    'meta',
                    function ($query) use (
                        $parameters,
                        $latMin,
                        $latMax,
                        $lngMin,
                        $lngMax
                    ) {
                        $query->where('lat', '>=', $latMin)
                            ->where('lat', '<=', $latMax)
                            ->where('lng', '>=', $lngMin)
                            ->where('lng', '<=', $lngMax);
                    }
                );
            }
        }

        if (isset($parameters['with_profile_image']) && $parameters['with_profile_image']) {
            $query = $query->whereHas('profileImage');
        }

        $roleIdsArray = [];

        if (isset($parameters['role_id'])) {
            $roleIdsArray[] = (int) $parameters['role_id'];
        } else {
            $roleIdsArray = [Role::ROLE_PEASANT, Role::ROLE_BOT];
        }

        $query = $query->whereHas('roles', function ($query) use ($roleIdsArray) {
            $query->whereIn('id', $roleIdsArray);
        });

        $query->where('users.id', '!=', \Auth::user()->getId());

        if (!$paginated) {
            if (null !== $ordering) {
                if ($ordering['type'] === self::ORDERING_TYPE_RANDOMIZED) {
                    $query->inRandomOrder($ordering['randomization_key']);
                }
            }

            return $query->get();
        }

        $perPage = PaginationConstants::$perPage['user_profiles'];
        if (null !== $ordering) {
            if ($ordering['type'] === self::ORDERING_TYPE_RANDOMIZED) {
                $query->inRandomOrder($ordering['randomization_key']);

                return $query->paginate($perPage, ['*'], 'page', $page);
            } else if ($ordering['type'] === self::ORDERING_TYPE_RADIUS && isset($parameters['city_name'])) {
                return $query->orderByRaw('
                    ( 3959 * acos( cos( radians(' . $lat . ') ) * cos( radians( um.lat ) ) * cos( radians( um.lng ) - radians(' . $lng . ') ) + sin( radians(' . $lat . ') ) * sin( radians( um.lat ) ) ) )
                ', 'ASC')->paginate(PaginationConstants::$perPage['user_profiles'], ['*'], 'page', $page);

            }
        } else {
            return $query->paginate($perPage, ['*'], 'page', $page);
        }
    }
}
