<?php

namespace discord3682\prefix\cmd;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;

use discord3682\prefix\Prefix;
use discord3682\prefix\PrefixData;

class MyPrefixCommand extends Command
{

  public function __construct ()
  {
    parent::__construct ('내칭호', '내 칭호를 확인합니다.', '/내칭호', ['myprefix', '칭호']);
  }

  public function execute (CommandSender $sender, string $commandLabel, array $args)
  {
    if ($sender instanceof Player)
    {
      $prefixData = Prefix::getData ($sender);
      if (
        !$prefixData instanceof PrefixData or
        count ($prefixData->getPrefixes ()) <= 0 or
        count ($prefixData->getColors ()) <= 0
      ) {
        Prefix::msg ($sender, '당신은 데이터가 없습니다.');
      }else
      {
        Prefix::msg ($sender, '당신의 칭호 : ' . $prefixData->getPrefix ($prefixData->getNowPrefix ()));
        Prefix::msg ($sender, '당신의 별명 : §l§8[' . $prefixData->getNickname () . ']');
        Prefix::msg ($sender, '당신의 채팅 색깔 : ' . Prefix::$colors [(string) $prefixData->getColor ($prefixData->getNowColor ())]);
      }
    }else
    {
      Prefix::msg ($sender, '인게임에서 사용하여 주십시오.');
    }
  }
}
