<?php

namespace discord3682\prefix\form;

use pocketmine\form\Form;
use pocketmine\nbt\tag\StringTag;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\Item;
use pocketmine\Player;

use discord3682\prefix\Prefix;

class NameChangeForm implements Form
{

  private $loop;

  public function __construct (bool $loop = true)
  {
    $this->loop = $loop;
  }

  public function jsonSerialize () : array
  {
    return [
      'type' => 'custom_form',
      'title' => '§l§0별명을 정해주세요.',
      'content' => [
        [
          'type' => 'label',
          'text' => '§r§f별명은 수정이 불가능합니다, 4글자 이내로 신중히 작성하여 주세요.'
        ],

        [
          'type' => 'input',
          'text' => ' ',
          'placeholder' => '§r§7터치하여 작성'
        ]
      ]
    ];
  }

  public function handleResponse (Player $player, $data) : void
  {
    if (is_array ($data))
    {
      $book = Item::get (340, 0, 1);
      $book->setCustomName ('§f별명 변경권');
      $book->setLore ([
        '',
        '§a- - - - - - - - - -',
        '',
        '§f터치하여 별명 변경권 사용',
        '',
        '§a- - - - - - - - - -',
        ''
      ]);
      $book->addEnchantment (new EnchantmentInstance (Enchantment::getEnchantment (Enchantment::INFINITY), 0));
      $book->setNamedTagEntry (new StringTag ('Name', ' '));

      if (!isset ($data [1]) or !is_string ($data [1]))
      {
        if ($this->loop)
        {
          $player->sendForm (new NameChangeForm ());
        }else
        {
          Prefix::msg ($player, '별명 변경이 취소되었습니다.');
          $player->getInventory ()->addItem ($book);
        }
      }else
      {
        if (mb_strlen ($data [1], 'UTF-8') <= 4)
        {
          $prefixData = Prefix::getData ($player);
          $prefixData->setNickname ($data [1]);

          if ($this->loop)
          {
            Prefix::msg ($player, '별명을 설정하셨습니다.');
          }else
          {
            Prefix::msg ($player, '별명을 변경하셨습니다.');
          }
        }else
        {
          if ($this->loop)
          {
            $player->sendForm (new NameChangeForm ());
          }else
          {
            Prefix::msg ($player, '4글자 이내로 입력하여 주십시오');
            $player->getInventory ()->addItem ($book);
          }
        }
      }
    }else
    {
      $player->sendForm (new NameChangeForm ());
    }
  }
}
