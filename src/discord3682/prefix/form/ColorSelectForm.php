<?php

namespace discord3682\prefix\form;

use pocketmine\form\Form;
use pocketmine\Player;

use discord3682\prefix\Prefix;

class ColorSelectForm implements Form
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

    foreach ($prefixData->getColors () as $key => $color)
    {
      $description = $key === $prefixData->getNowColor () ? '§r§8사용 중인 컬러' : '§r§8터치하여 사용';
      $buttons [] = [
        'text' => Prefix::$colors [(string) $color] . "\n" . $description
      ];
    }

    return [
      'type' => 'form',
      'title' => '§l§0컬러를 선택합니다.',
      'content' => '',
      'buttons' => $buttons
    ];
  }

  public function handleResponse (Player $player, $data) : void
  {
    if (is_numeric ($data))
    {
      $prefixData = Prefix::getData ($player);

      if ($prefixData->getNowColor () == $data)
      {
        Prefix::msg ($player, '이미 사용 중인 컬러입니다.');
      }else
      {
        if ($prefixData->getColor ($data) == null)
        {
          Prefix::msg ($player, '해당 칭호를 소유하고 계시지 않습니다.');
        }else
        {
          $prefixData->setNowColor ($data);
          $color = $prefixData->getColor ($data);
          Prefix::msg ($player, '§a' . $data . '번§r§7 컬러를 장착하셨습니다.');
        }
      }
    }
  }
}
