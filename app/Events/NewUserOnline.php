<?php
namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Personnages\Models\Personnage;

class NewUserOnline implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $user;
    public $user_id;
    public $color = "";
    public $currentPersonnageName;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(int $id)
    {
        $this->user_id = $id;
        $this->setElements();
    }

    private function setElements()
    {
        /**
         * Find and set active personnage
         */
        $active = Personnage::where([
            "owner_id" => $this->user_id,
            "active" => true
        ])->with(["assignations", "assignations.group"])->firstOrFail();
        $this->currentPersonnageName = $active->name;

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


    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PresenceChannel('whoisonline');
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return '[Auth] UserLoggedIn';
    }
}
