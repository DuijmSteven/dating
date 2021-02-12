<?php

namespace App\Http\Controllers\Api;

use App\Http\Validators\PeasantCreate;
use App\Http\Validators\PeasantUpdate;
use App\Managers\PeasantManager;
use App\Managers\UserManager;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PeasantController
{
    /**
     * @var PeasantManager
     */
    private PeasantManager $peasantManager;
    /**
     * @var UserManager
     */
    private UserManager $userManager;

    public function __construct(
        PeasantManager $peasantManager,
        UserManager $userManager
    ) {
        $this->peasantManager = $peasantManager;
        $this->userManager = $userManager;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function update(Request $request, int $peasantId)
    {
        $validator = Validator::make($request->all(), PeasantUpdate::rules($peasantId, $request->files));

        if ($validator->fails()) {
            return response()->json($validator->getMessageBag(), 422);
        }

        /** @var User $requestingUser */
        $requestingUser = $request->user();

        $peasantData = $request->all();

        try {
            $this->peasantManager->updatePeasant($peasantData, $peasantId);
            return response()->json();
        } catch (\Exception $exception) {
            throw new \Exception();
//            return response()->json($exception->getMessage(), 500);
        }
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), PeasantCreate::rules($request->files));

        if ($validator->fails()) {
            return response()->json($validator->getMessageBag(), 422);
        }

        $peasantData = $request->all();
        $peasantData['city'] = trim(strtolower($peasantData['city']));

        try {
            $this->peasantManager->createBot($peasantData);
            return response()->json();
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }
}
