<?php

namespace App\Managers;

use App\Helpers\ApplicationConstants\PaginationConstants;
use App\Helpers\ApplicationConstants\UserConstants;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Class UserSearchManager
 * @package App\Managers
 */
class UserSearchManager
{
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
     */
    public function searchUsers(array $parameters, $paginated = false, $page = 1)
    {
        // initial part of query
        $query = $this->user->with('meta');

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
                    $query = $query->where('dob', '>=', $parameters['dob']['min']);
                    $query = $query->where('dob', '<=', $parameters['dob']['max']);
                });
            }

            if (isset($parameters['city'])) {
                $query = $query->whereHas('meta', function ($query) use ($parameters) {
                    $query = $query->where('city', 'like', '%' . $parameters['city'] . '%');
                });
            }
        }

        if (!$paginated) {
            $results = $query->get();
            return $results;
        }
        $results = $query->paginate(PaginationConstants::$perPage['user_profiles'], ['*'], 'page', $page);
        return $results;
    }
}
