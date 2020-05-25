<?php
namespace Modules\Economy\Contracts;

interface EconomicActor
{
    public function fiche();

    public function account();

    public function isSolvable(int $amount): bool;
}
