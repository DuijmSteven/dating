<?php

namespace App\Managers;

use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class UserSearchManager
{
    /**
     * @var User
     */
    private $user;

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
            foreach (\UserConstants::SELECTABLE_PROFILE_FIELDS as $field => $values) {
                if (isset($parameters[$field])) {
                    $query->whereHas('meta', function ($query) use ($parameters, $field) {
                        $query->where($field, $parameters[$field]);
                    });
                }
            }
        }

        if (!$paginated) {
            $results = $query->get();
            return $results;
        }
        $results = $query->paginate(\PaginationConstants::PER_PAGE['user_profiles'], ['*'], 'page', $page);

        return $results;
    }
}
