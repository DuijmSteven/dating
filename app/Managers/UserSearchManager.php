<?php

namespace App\Managers;

use App\Helpers\ApplicationConstants\PaginationConstants;
use App\Helpers\ApplicationConstants\UserConstants;
use App\User;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class UserSearchManager
 * @package App\Managers
 */
class UserSearchManager
{
    const ORDERING_TYPE_RANDOMIZED = 'randomized';

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
    public function         searchUsers(array $parameters, $paginated = false, $page = 1, array $ordering = null)
    {
        if (isset($ordering) && isset($ordering['randomization_key'])) {
            if (gettype($ordering['randomization_key']) !== 'string') {
                throw new \Exception('The ordering randomization key must be of type [string]');
            }
        }

        // initial part of query
        $query = $this->user->with(['meta', 'roles']);

        // append to query
        if (isset($parameters['query'])) {
            $query = $query->where('username', 'like', '%' . $parameters['query'] . '%')
                ->orWhere('email', 'like', '%' . $parameters['query'] . '%');
        } else {
            if (isset($parameters['username'])) {
                $query = $query->where('username', 'like', '%' . $parameters['username'] . '%');
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

            if (isset($parameters['city']) && isset($parameters['lat']) && isset($parameters['lng'])) {
                $latInRadians = deg2rad($parameters['lat']);
                $lngInRadians = deg2rad($parameters['lng']);

                $angularRadius = $parameters['radius']/6371;

                $latMin = rad2deg($latInRadians - $angularRadius);
                $latMax = rad2deg($latInRadians + $angularRadius);

                $deltaLng = asin(sin($angularRadius)/cos($latInRadians));

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

        $query = $query->whereHas('roles', function ($query) {
            $query->whereIn('id', [2, 3]);
        });

        if (!$paginated) {
            if (null !== $ordering) {
                if ($ordering['type'] = self::ORDERING_TYPE_RANDOMIZED) {
                    $query->inRandomOrder($ordering['randomization_key']);
                }
            }

            return $query->get();
        }

        if (null !== $ordering) {
            if ($ordering['type'] = self::ORDERING_TYPE_RANDOMIZED) {
                $query->inRandomOrder($ordering['randomization_key']);
            }
        }

        $results = $query->paginate(PaginationConstants::$perPage['user_profiles'], ['*'], 'page', $page);
        return $results;
    }
}
