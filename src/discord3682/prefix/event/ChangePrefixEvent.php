<?php

namespace discord3682\prefix\event;

use pocketmine\event\Event;
use pocketmine\event\Cancellable;

class ChangePrefixEvent extends Event implements Cancellable
{

  private $player;
  private $beforePrefix;
  private $afterPrefix;

  public function __construct (string $player, int $beforePrefix, int $afterPrefix)
  {
    $this->player = $player;
    $this->beforePrefix = $beforePrefix;
    $this->afterPrefix = $afterPrefix;
  }

  public function getPlayer () : string
  {
    return $this->player;
  }

  public function getBeforePrefix () : int
  {
    return $this->beforePrefix;
  }

  public function getAfterPrefix () : int
  {
    return $this->afterPrefix;
  }
}
