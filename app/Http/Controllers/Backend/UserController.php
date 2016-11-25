<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\ApplicationConstants\UserConstants;
use App\Http\Controllers\Controller;
use App\Managers\UserManager;

class UserController extends Controller
{
    /** @var UserManager $userManager */
    protected $userManager;

    /**
     * UserController constructor.
     * @param UserManager $userManager
     */
    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $userId
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $userId)
    {
        try {
            $this->userManager->deleteUser($userId);
            return redirect()->back();

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    /**
     * @param $countryCode
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCities($countryCode)
    {
        return response()->json([
            'cities' => UserConstants::getCities($countryCode)
        ]);
    }
}
