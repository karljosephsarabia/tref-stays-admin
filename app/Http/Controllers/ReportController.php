<?php

namespace App\Http\Controllers;

use App\RsUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SMD\Common\ReservationSystem\Enums\RsPaymentVia;
use SMD\Common\ReservationSystem\Enums\TransactionType;
use SMD\Common\ReservationSystem\Helpers\GeneralHelper;
use SMD\Common\ReservationSystem\Models\RsIncomingReport;
use SMD\Common\ReservationSystem\Models\RsTransaction;

class ReportController extends AppBaseController
{
    public function __construct()
    {
    }

    public function income(Request $request, $user = null)
    {
        $user = $request->user();

        $owners = RsUser::owners();

        if (!$user->is_broker) {
            $owners = $owners->whereId($user->id);
        }

        return view('reports.income')
            ->with([
                'owners' => $owners->get(),
                'payment_via' => RsPaymentVia::TYPES
            ]);
    }

    public function incomePdf(Request $request, $user, $id = null)
    {
        $data = [];

        if ($id == null) {
            $data['user'] = RsUser::whereId($user)->firstOrFail();
            $data['report'] = $data['user']->currentIncomes();
        } else {
            $data['report'] = RsIncomingReport::whereUserId($user)->whereId($id)->firstOrFail();
            $data['user'] = $data['report']->user;
        }

        $pdf = \PDF::loadView('reports.pdf.income', compact('data'));

        $file_name = GeneralHelper::getUserFullName($data['user']) . '-' .
            \App\Helpers\GeneralHelper::ReportDateRange($data['report']->starting_at, $data['report']->ending_at);

        return $pdf->download($file_name . '.pdf');
    }

    public function incomePay(Request $request)
    {
        DB::beginTransaction();
        try {
            $report = RsIncomingReport::whereId($request->input('id'))->firstOrFail();
            $report->payment_done = true;
            $report->payment_via = $request->input('payment_via');
            $report->comment = $request->input('comment');
            $report->paid_at = Carbon::now();
            $report->save();

            $payment = new RsTransaction();
            $payment->user_id = $report->user_id;
            $payment->type = TransactionType::PAYMENT;
            $payment->amount = $report->ending_balance;
            $payment->save();

            DB::commit();
            return $this->jsonSuccessResponse($request->all());
        } catch (\Exception $e) {
            DB::rollback();
            return $this->jsonErrorResponse(trans('general.processing_request') . ':: ' . $e->getMessage());
        }
    }
}
