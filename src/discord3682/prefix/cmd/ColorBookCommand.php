<?php

namespace discord3682\prefix\cmd;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\nbt\tag\StringTag;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\Item;
use pocketmine\Player;

use discord3682\prefix\Prefix;

class ColorBookCommand extends Command
{

  public function __construct ()
  {
    parent::__construct ('컬러북', '컬러북을 지급 받습니다.', '/컬러북 [컬러]', ['colorbook', '컬러주기']);
    $this->setPermission ('op');
  }

  public function execute (CommandSender $sender, string $commandLabel, array $args)
  {
    if (!$this->testPermission ($sender)) return;

    if ($sender instanceof Player)
    {
      if (!isset ($args [0]))
      {
        Prefix::msg ($sender, '컬러를 기입하여 주십시오.');
      }else
      {
        if (!in_array ($args [0], array_keys (Prefix::$colors)))
        {
          Prefix::msg ($sender, '존재하지 않는 컬러입니다.');
        }else
        {
          $args [0] = implode ('', $args);
          $book = Item::get (340, 0, 1);
          $book->setCustomName ('§f컬러북 : ' . Prefix::$colors [(string) $args [0]]);
          $book->setLore ([
            '',
            '§b- - - - - - - - - - - - - - - - ',
            '',
            '§f터치하여 컬러북 사용',
            '§f컬러 : ' . Prefix::$colors [(string) $args [0]],
            '',
            '§b- - - - - - - - - - - - - - - - ',
            ''
          ]);
          $book->addEnchantment (new EnchantmentInstance (Enchantment::getEnchantment (Enchantment::INFINITY), 0));
          $book->setNamedTagEntry (new StringTag ('Color', $args [0]));
          $sender->getInventory ()->addItem ($book);
          Prefix::msg ($sender, '컬러북을 지급 받으셨습니다.');
        }
      }
    }else
    {
      Prefix::msg ($sender, '인게임에서 사용하여 주십시오.');
    }
  }
}
