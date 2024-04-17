<?php

namespace App\Events;

use App\Jobs\CatalogItemsExcelLoadJob;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;


class ExcelEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $file;
    public $user;

    public function __construct($file,$user)
    {
        $this->file = $file;
        $this->user = $user;

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('load.excel.'.$this->user->id);
    }

    public function broadcastWith(){
        return CatalogItemsExcelLoadJob::dispatch($this->file,$this->user);
    }

    public function broadcastAs()
    {
        return 'load.excel.'.$this->user->id;
    }
}
