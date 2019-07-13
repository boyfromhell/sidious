<?php
namespace Modules\Economy\Traits;

use Modules\Economy\Models\Account;
use Modules\Economy\Models\Fiche;

trait HasEconomy
{
    /**
     * Model economy fiche
     *
     * @return mixed
     */
    public function fiche()
    {
        return $this->morphOne(Fiche::class, "owner");
    }

    /**
     * Model account
     *
     * @return mixed
     */
    public function account()
    {
        return $this->morphOne(Account::class, "owner");
    }

    /**
     * Checks account solvency
     *
     * @param int $amount
     * @return bool
     */
    public function isSolvable(int $amount)
    {
        $balance = $this->account->balance;
        return ($balance >= $amount);
    }

    public static function bootHasEconomy()
    {
        static::created(function ($model) {
            $model->fiche()->create();
            $model->account()->create();
        });

        static::deleting(function ($model) {
            $model->fiche()->delete();
            $model->account()->delete();
        });
    }
}