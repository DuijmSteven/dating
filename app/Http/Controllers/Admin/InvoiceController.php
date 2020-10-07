<?php

namespace App\Http\Controllers\Admin;

use App\ConversationMessage;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Articles\ArticleCreateRequest;
use App\Role;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use LaravelDaily\Invoices\Invoice;

/**
 * Class InvoiceController
 * @package App\Http\Controllers\Admin
 */
class InvoiceController extends Controller
{
//    /**
//     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
//     */
//    public function index()
//    {
//        $articles = Article::orderBy('created_at', 'desc')->paginate(5);
//
//        foreach ($articles as $article) {
//            $article->body = Markdown::convertToHtml($article->body);
//        }
//
//        return view(
//            'admin.articles.overview',
//            [
//                'title' => 'Articles Overview - ' . ucfirst(\config('app.name')),
//                'headingLarge' => 'Articles',
//                'headingSmall' => 'Overview',
//                'carbonNow' => Carbon::now(),
//                'articles' => $articles
//            ]
//        );
//    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getCreate()
    {
        $employees = User::with(['roles'])
            ->whereHas('roles', function ($query) {
                $query->whereIn('id', [
                    Role::ROLE_OPERATOR
                ]);
            })
            ->where('active', true)
            ->get();

        return view(
            'admin.invoices.create',
            [
                'title' => 'Create invoice - ' . ucfirst(\config('app.name')),
                'headingLarge' => 'Invoices',
                'headingSmall' => 'Create',
                'employees' => $employees
            ]
        );
    }

    public function fromParameters(Request $request)
    {
        $fromDate = $request->get('fromDate');
        $untilDate = $request->get('untilDate');
        $userId = $request->get('employeeId');
        $invoiceSequenceNumber = (int) $request->get('sequenceNumber');

        /** @var User $user */
        $user = User::find($userId);

        $fromDate = (new Carbon($fromDate))
            ->tz('Europe/Amsterdam')
            ->startOfDay()
            ->setTimezone('UTC');

        $untilDate = (new Carbon($untilDate))
            ->tz('Europe/Amsterdam')
            ->endOfDay()
            ->setTimezone('UTC');

        $normalMessagesCount = ConversationMessage
            ::where('operator_id', $userId)
            ->where('created_at', '>=', $fromDate)
            ->where('created_at', '<=', $untilDate)
            ->where('operator_message_type', null)
            ->count();

        $stoppedMessagesCount = ConversationMessage
            ::where('operator_id', $userId)
            ->where('created_at', '>=', $fromDate)
            ->where('created_at', '<=', $untilDate)
            ->where('operator_message_type', ConversationMessage::OPERATOR_MESSAGE_TYPE_STOPPED)
            ->count();

        $costOfEachStoppedMessageInCents = 10;

        $costOfStopped = $costOfEachStoppedMessageInCents * $stoppedMessagesCount;

        $costOfEachNormalMessageInCents = 12;

        if ($normalMessagesCount > 500 && $normalMessagesCount <= 1000) {
            $costOfEachNormalMessageInCents = 13;
        } elseif ($normalMessagesCount > 1000 && $normalMessagesCount <= 1500) {
            $costOfEachNormalMessageInCents = 14;
        } elseif ($normalMessagesCount > 1500 && $normalMessagesCount <= 2000) {
            $costOfEachNormalMessageInCents = 15;
        } elseif ($normalMessagesCount > 2000) {
            $costOfEachNormalMessageInCents = 16;
        }

        $costOfNormal = $costOfEachNormalMessageInCents * $normalMessagesCount;

        //dd($normalMessagesCount, $stoppedMessagesCount, $costOfEachNormalMessageInCents);

        $customer = new Buyer([
            'name' => substr($user->getFirstName(), 0, 1) . '. ' . $user->getLastName(),
            'address' => $user->getStreetName() . ', ' . $user->getPostalCode() . ', ' . $user->meta->getCity() . ', ' . strtoupper($user->meta->getCountry()),
        ]);

        $formattedFromDate = (new Carbon($fromDate))
            ->tz('Europe/Amsterdam')
            ->format('d-m-Y');

        $formattedUntilDate = (new Carbon($untilDate))
            ->tz('Europe/Amsterdam')
            ->format('d-m-Y');

        $item = (new InvoiceItem())
            ->title('Content diensten ' . $formattedFromDate . ' tot en met ' . $formattedUntilDate)
            ->pricePerUnit(($costOfStopped + $costOfNormal) / 100);

        $invoice = Invoice::make()
            ->name('Creditnota')
            ->notes('De factuur zal binnen 7 werkdagen worden voldaan onder vermelding van het factuurnummer.')
            ->buyer($customer)
            ->date(Carbon::now())
            ->dateFormat('d-m-Y')
            ->taxRate(0)
            ->taxableAmount(0)
            ->sequence($invoiceSequenceNumber)
            ->addItem($item);

        return $invoice->stream();
    }

    /**
     * @param ArticleCreateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
//    public function post(ArticleCreateRequest $request)
//    {
//        DB::beginTransaction();
//        try {
//            $this->articleManager->persistArticle($request->all());
//
//            $alerts[] = [
//                'type' => 'success',
//                'message' => 'The article was created.'
//            ];
//        } catch (\Exception $exception) {
//            DB::rollBack();
//
//            \Log::error($exception->getMessage());
//
//            $alerts[] = [
//                'type' => 'error',
//                'message' => 'The article was not created due to an exception.'
//            ];
//        }
//
//        DB::commit();
//
//        return redirect()->back()->with('alerts', $alerts);
//    }
}
