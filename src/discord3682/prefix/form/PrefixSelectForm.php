<?php

namespace discord3682\prefix\form;

use pocketmine\form\Form;
use pocketmine\Player;

use discord3682\prefix\Prefix;

class PrefixSelectForm implements Form
{

  private $player;

  public function __construct (Player $player)
  {
    $this->player = $player;
  }

  public function jsonSerialize () : array
  {
    $buttons = [];
    $prefixData = Prefix::getData ($this->player);

    foreach ($prefixData->getPrefixes () as $key => $prefix)
    {
      $description = $key === $prefixData->getNowPrefix () ? '§r§8사용 중인 칭호' : '§r§8터치하여 사용';
      $buttons [] = [
        'text' => $prefix . "\n" . $description
      ];
    }

    return [
      'type' => 'form',
      'title' => '§l§0칭호를 선택합니다.',
      'content' => '',
      'buttons' => $buttons
    ];
  }

  public function handleResponse (Player $player, $data) : void
  {
    if (is_numeric ($data))
    {
      $prefixData = Prefix::getData ($player);
      if ($prefixData->getNowPrefix () == $data)
      {
        Prefix::msg ($player, '이미 사용 중인 칭호입니다.');
      }else
      {
        if ($prefixData->getPrefix ($data) == null)
        {
          Prefix::msg ($player, '해당 칭호를 소유하고 계시지 않습니다.');
        }else
        {
          $prefixData->setNowPrefix ($data);
          Prefix::msg ($player, '§a' . $data . '번§r§7 칭호를 장착하셨습니다.');
        }
      }
    }
  }
}
