<?php
namespace Modules\Economy\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Economy\Events\TransactionConfirmed;

class Transaction extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $dispatchesEvents = [
        "created" => TransactionConfirmed::class,
    ];

    public function to()
    {
        return $this->belongsTo(Account::class, "account_to");
    }

    public function from()
    {
        return $this->belongsTo(Account::class, "account_from");
    }

    /**
     * Handles account balance when created or deleted
     */
    public static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            $model->to->updateBalance($model->amount, $model->isCredit);
            if($model->account_from) $model->from->updateBalance($model->amount, !$model->isCredit);
        });

        static::deleted(function ($model) {
            $model->to->updateBalance($model->amount, !$model->isCredit);
            if($model->account_from) $model->from->updateBalance($model->amount, $model->isCredit);
        });
    }
}
