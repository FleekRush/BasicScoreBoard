<?php

namespace FleekRush;

use pocketmine\scheduler\Task;

class SCTask extends Task
{

    private $plugin;

    public function __construct(main $plugin)
    {
        $this->plugin = $plugin;
    }

    public function onRun(int $currentTick)
    {
        $this->plugin->onScore();
    }
}
