<?php
namespace Modules\Economy\Controllers;

use App\Http\Controllers\Controller;
use Modules\Economy\Exceptions\TransactionNotAllowed;
use Modules\Economy\Models\Account;
use Modules\Economy\Models\Transaction;

class AccountController extends Controller
{
    /**
     * Get account & all transactions
     *
     * @param Account $account
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Account $account)
    {
        $transactions = Transaction::where("account_to", $account->id)
            ->orWhere("account_from", $account->id)
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json(["account" => $account, "transactions" => $transactions]);
    }

    /**
     * Transfer from identified account to another
     *
     * @param Account $account
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function fromAccountToAnother(Account $account)
    {
        $data = request()->only(["to", "motivation", "amount"]);

        $to = Account::where("id", $data['to'])->firstOrFail();

        if(!$account->canPay($data['amount'])) throw new TransactionNotAllowed("Not enough funds");
        try {
            Transaction::create([
                "amount" => $data['amount'],
                "motivation" => $data["motivation"],
                "account_to" => $data['to'],
                "account_from" => $account->id,
                "isCredit" => true
            ]);
        } catch (\Exception $exception) {
            throw $exception;
        }

        return response()->json("Transaction success");
    }

    /**
     * Complex transaction
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function complexTransaction()
    {
        $data = request()->only(["to", "to_type", "motivation", "amount", "from", "from_type"]);

        $to = $this->findAccountByOwner($data['to_type'], $data['to']);
        $from = $this->findAccountByOwner($data['from_type'], $data['from']);

        if(!$from->canPay($data['amount'])) throw new TransactionNotAllowed("Not enough funds");
        try {
            Transaction::create([
                "amount" => $data['amount'],
                "motivation" => $data["motivation"],
                "account_to" => $data['to'],
                "account_from" => $data['from'],
                "isCredit" => true,
            ]);
        } catch (\Exception $exception) {
            throw $exception;
        }

        return response()->json();
    }

    /**
     * Find account by type, id couple
     *
     * @param string $type
     * @param int $id
     * @return mixed
     */
    private function findAccountByOwner(string $type, int $id)
    {
        return Account::where([
            "owner_id" => $id,
            "owner_type" => $type
        ])->firstOrFail();
    }

    /**
     * Freeze an account
     *
     * @param Account $account
     * @return Account
     */
    public function freeze(Account $account)
    {
        $account->isFrozen = true;
        $account->save();
        return $account;
    }
}
