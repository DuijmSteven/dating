<?php

namespace App\Managers;

use App\Helpers\ApplicationConstants\PaginationConstants;
use App\Helpers\ApplicationConstants\UserConstants;
use App\Helpers\FormattingHelper;
use App\Role;
use App\Services\GeocoderService;
use App\Services\UserLocationService;
use App\User;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cookie;
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
     * @var UserManager
     */
    private UserManager $userManager;
    /**
     * @var UserLocationService
     */
    private UserLocationService $userLocationService;

    /**
     * UserSearchManager constructor.
     * @param User $user
     */
    public function __construct(
        User $user,
        UserManager $userManager,
        UserLocationService $userLocationService
    ) {
        $this->user = $user;
        $this->userManager = $userManager;
        $this->userLocationService = $userLocationService;
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
    public function searchUsers(
        array $parameters,
        $paginated = false,
        $page = 1,
        array $ordering = null,
        $relations = [],
        $relationCounts = []
    ) {
        if (isset($ordering) && isset($ordering['randomization_key'])) {
            if (gettype($ordering['randomization_key']) !== 'string') {
                throw new \Exception('The ordering randomization key must be of type [string]');
            }
        }

        if ($relations && count($relations) === 0) {
            $relations = User::COMMON_RELATIONS;

            if (isset($parameters['role_id'])) {
                $relations = UserManager::getRequiredRelationsForRole($parameters['role_id']);
            }
        }

        if ($relationCounts && count($relationCounts) === 0) {
            $relationCounts = [];

            if (isset($parameters['role_id'])) {
                $relationCounts = UserManager::getRequiredRelationCountsForRole($parameters['role_id']);
            }
        }

        // initial part of query
        $query = $this->user->join('user_meta as um', 'users.id', '=', 'um.user_id')
            ->select('users.*')
            ->with($relations)
            ->withCount($relationCounts);

        // append to query
        if (isset($parameters['query'])) {
            $query = $query->where(function ($query) use ($parameters) {
                $query->where('users.username', 'like', '%' . $parameters['query'] . '%')
                    ->orWhere('users.email', 'like', '%' . $parameters['query'] . '%');
            });
        } else {
            if (isset($parameters['username'])) {
                $query = $query->where('users.username', 'like', '%' . $parameters['username'] . '%');
            }

            if (isset($parameters['active'])) {
                $query = $query->where('users.active', '=', $parameters['active']);
            }

            if (isset($parameters['dob'])) {
                $query = $query->whereHas('meta', function ($query) use ($parameters) {
                    $query->where('dob', '>=', $parameters['dob']['min']);
                    $query->where('dob', '<=', $parameters['dob']['max']);
                });
            }

            if (isset($parameters['affiliate'])) {
                $query = $query->whereHas('affiliateTracking', function ($query) use ($parameters) {
                    $query->where('affiliate', $parameters['affiliate']);
                });
            }

            if (isset($parameters['created_at_after'])) {
                $query->where('users.created_at', '>=', $parameters['created_at_after']);
            }

            if (isset($parameters['created_at_before'])) {
                $query->where('users.created_at', '<=', $parameters['created_at_before']);
            }

            if (isset($parameters['city_name'])) {
                $coordinates = $this->userLocationService->getCoordinatesForCity($parameters['city_name']);

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

        foreach (UserConstants::selectableFields('common') as $field => $values) {
            if (isset($parameters[$field])) {
                $query = $query->whereHas('meta', function ($query) use ($parameters, $field) {
                    $query->where($field, $parameters[$field]);
                });
            }
        }

        if (isset($parameters['country'])) {
            $query = $query->whereHas('meta', function ($query) use ($parameters, $field) {
                $query->where('country', $parameters['country']);
            });
        }

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

    public function formatUserSearchArray($array)
    {
        FormattingHelper::emptyToNull($array);

        if (isset($array['with_profile_image'])) {
            Cookie::queue('searchWithProfileImageSet', $array['with_profile_image'], 60);
        }

        if (isset($array['age'])) {
            $ageMax = 100;

            $largestAgeLimit = (int)array_keys(UserConstants::$ageGroups)[sizeof(UserConstants::$ageGroups) - 1];

            if ($array['age'] != $largestAgeLimit) {
                [$ageMin, $ageMax] = explode('-', $array['age']);
            } else {
                $ageMin = $largestAgeLimit;
            }

            $date = new \DateTime;
            // The "Min" and "Max" are reversed on purpose in their usages, since the resulting date
            // from the minimum age would be more recent than the one resulting from the maximum age
            $formattedMaxDate = $date->modify('-' . $ageMin . ' years')->format('Y-m-d H:i:s');

            $date = new \DateTime;
            $formattedMinDate = $date->modify('-' . $ageMax . ' years')->format('Y-m-d H:i:s');

            $array['dob'] = [];
            $array['dob']['min'] = $formattedMinDate;
            $array['dob']['max'] = $formattedMaxDate;
        }

        if (isset($array['created_at_after'])) {
            $array['created_at_after'] = (new Carbon($array['created_at_after']))
                ->tz('Europe/Amsterdam')
                ->format('Y-m-d H:i:s');
        }

        if (isset($array['created_at_before'])) {
            $array['created_at_before'] = (new Carbon($array['created_at_before']))
                ->addDays(1)
                ->tz('Europe/Amsterdam')
                ->format('Y-m-d H:i:s');
        }

        return $array;
    }
}
