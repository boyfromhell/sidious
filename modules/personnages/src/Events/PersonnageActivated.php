<?php
namespace Modules\Personnages\Events;

use Illuminate\Broadcasting\PresenceChannel;
use Modules\Personnages\Models\Personnage;

class PersonnageActivated extends Event
{
    protected $name;
    public $currentPersonnageName;
    public $color;
    public $user_id;

    public function __construct(Personnage $personnage)
    {
        $this->currentPersonnageName = $personnage->name;
        $this->user_id = $personnage->owner_id;
        $this->setColor($personnage);
        $this->name = "[PJ] Personnage activated";
    }

    private function setColor(Personnage $active)
    {
        /**
         * Find main group to set color
         */
        if(!($active->assignations->isEmpty()))
        {
            $mainGroup = $active->assignations->filter(function($item) {
                return $item->isMain == true;
            })->first();
            $this->color = $mainGroup->color;
        }
    }

    public function broadcastOn()
    {
        return new PresenceChannel('whoisonline');
    }
}
