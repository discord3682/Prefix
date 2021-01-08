<?php

namespace discord3682\prefix\cmd;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;

use discord3682\prefix\Prefix;
use discord3682\prefix\PrefixData;
use discord3682\prefix\form\ColorSelectForm;

class ColorSelectCommand extends Command
{

  public function __construct ()
  {
    parent::__construct ('컬러선택', '컬러를 선택합니다.', '/컬러선택', ['컬러목록', 'colorselect']);
  }

  public function execute (CommandSender $sender, string $commandLabel, array $args)
  {
    if ($sender instanceof Player)
    {
      $prefixData = Prefix::getData ($sender);

      if (!$prefixData instanceof PrefixData)
      {
        Prefix::msg ($sender, '당신은 데이터가 없습니다.');
      }else
      {
        $sender->sendForm (new ColorSelectForm ($sender));
      }
    }else
    {
      Prefix::prevent ($sender, '인게임에서 사용하여 주십시오.');
    }
  }
}
