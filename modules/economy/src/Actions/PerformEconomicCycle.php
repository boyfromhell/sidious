<?php
namespace Modules\Economy\Actions;

use Carbon\Carbon;
use Inani\LaravelNovaConfiguration\Helpers\Configuration;
use Lorisleiva\Actions\Action;
use Modules\Economy\Events\CyclePerformed;
use Modules\Economy\Exceptions\TransactionNotAllowed;
use Modules\Economy\Models\Fiche;

class PerformEconomicCycle extends Action
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [];
    }

    /**
     * Perform economic cycle
     *
     * @throws TransactionNotAllowed
     */
    public function handle()
    {
        $now = Carbon::now()->format("m Y");
        if($this->checkLastRun($now)) {
            $fiches = Fiche::all();
            $motivation = "Cycle Economique | " . $now;
            foreach ($fiches as $fiche) {
                MakeTransaction::dispatch(abs($fiche->balance), $motivation, $fiche->account, null);
            }
            Configuration::set("LAST_ECONOMIC_CYCLE", $now);
            event(new CyclePerformed($now));
        } else {
            throw new TransactionNotAllowed("Cycle économique déjà réalisé");
        }
    }

    /**
     * Check if current month economic cycle was already handled
     *
     * @param string $now
     * @return int
     */
    private function checkLastRun(string $now)
    {
        $configuration = Configuration::get("LAST_ECONOMIC_CYCLE");
        $configuration = Carbon::createFromFormat("m Y", $configuration);
        $now = Carbon::createFromFormat("m Y", $now);

        $diff = $now->diffInMonths($configuration);
        return ($diff);
    }
}
