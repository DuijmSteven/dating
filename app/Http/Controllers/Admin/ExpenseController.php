<?php

namespace App\Http\Controllers\Admin;

use App\Expense;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Expenses\ExpenseCreateRequest;
use App\Http\Requests\Admin\Expenses\ExpenseUpdateRequest;
use Carbon\Carbon;
use DB;

/**
 * Class ExpenseController
 * @package App\Http\Controllers\Admin
 */
class ExpenseController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $expenses = Expense::orderBy('takes_place_at', 'desc')->paginate(20);

        return view(
            'admin.expenses.overview',
            [
                'title' => 'Expenses Overview - ' . \config('app.name'),
                'headingLarge' => 'Expenses',
                'headingSmall' => 'Overview',
                'carbonNow' => Carbon::now(),
                'expenses' => $expenses
            ]
        );
    }

    /**
     * @param int $expenseId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(int $expenseId)
    {
        try {
            Expense::destroy($expenseId);

            $alerts[] = [
                'type' => 'success',
                'message' => 'The expense was deleted.'
            ];
        } catch (\Exception $exception) {
            $alerts[] = [
                'type' => 'error',
                'message' => 'The expense was not deleted due to an exception.'
            ];
        }

        return redirect()->back()->with('alerts', $alerts);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getCreate()
    {
        return view(
            'admin.expenses.create',
            [
                'title' => 'Create expense - ' . \config('app.name'),
                'headingLarge' => 'Expenses',
                'headingSmall' => 'Create'
            ]
        );
    }

    /**
     * @param int $expenseId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getUpdate(int $expenseId)
    {
        return view(
            'admin.expenses.edit',
            [
                'title' => 'Edit expense - ' . \config('app.name'),
                'headingLarge' => 'Expenses',
                'headingSmall' => 'Edit',
                'expense' => Expense::find($expenseId)
            ]
        );
    }

    /**
     * @param ExpenseCreateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function post(ExpenseCreateRequest $request)
    {
        try {
            $data = $request->all();

            /** @var Expense $createdExpense */
            $createdExpense = Expense::create([
                'description' => $data['description'],
                'amount' => $data['amount'] * 1000,
                'payee' => $data['payee'],
                'type' => $data['type'],
                'takes_place_at' => Carbon::parse($data['takes_place_at'])->format('Y-m-d')
            ]);

            $alerts[] = [
                'type' => 'success',
                'message' => 'The expense was created.'
            ];
        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());

            $alerts[] = [
                'type' => 'error',
                'message' => 'The expense was not created due to an exception.'
            ];
        }

        return redirect()->back()->with('alerts', $alerts);
    }

    /**
     * @param ExpenseUpdateRequest $request
     * @param int $expenseId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ExpenseUpdateRequest $request, int $expenseId)
    {
        try {
            $data = $request->all();

            $expense = Expense::findOrFail($expenseId);

            /** @var Expense $updatedExpense */
            $updatedExpense = $expense->update([
                'description' => $data['description'],
                'amount' => $data['amount'] * 1000,
                'payee' => $data['payee'],
                'type' => $data['type'],
                'takes_place_at' => Carbon::parse($data['takes_place_at'])->format('Y-m-d')
            ]);

            $alerts[] = [
                'type' => 'success',
                'message' => 'The expense was updated.'
            ];
        } catch (\Exception $exception) {
            $alerts[] = [
                'type' => 'error',
                'message' => 'The expense was not updated due to an exception.'
            ];
        }

        return redirect()->back()->with('alerts', $alerts);
    }
}
