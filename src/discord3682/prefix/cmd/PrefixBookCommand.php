<?php

namespace discord3682\prefix\cmd;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;

use discord3682\prefix\Prefix;

class PrefixBookCommand extends Command
{

  public function __construct ()
  {
    parent::__construct ('칭호북', '칭호북을 지급 받습니다.', '/칭호북 [칭호]', ['prefixbook', '칭호주기']);
    $this->setPermission ('op');
  }

  public function execute (CommandSender $sender, string $commandLabel, array $args)
  {
    if (!$this->testPermission ($sender)) return;
    if ($sender instanceof Player)
    {
      if (!isset ($args [0]))
      {
        Prefix::msg ($sender, '칭호를 기입하여 주십시오.');
      }else
      {
        $sender->getInventory ()->addItem (Prefix::getPrefixBook (implode (' ', $args)));
        Prefix::msg ($sender, '칭호북을 지급 받으셨습니다.');
      }
    }else
    {
      Prefix::msg ($sender, '인게임에서 사용하여 주십시오.');
    }
  }
}
