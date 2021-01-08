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

class NameBookCommand extends Command
{

  public function __construct ()
  {
    parent::__construct ('별명북', '별명 변경권을 지급 받습니다.', '/별명북', ['namebook', '별명변경권']);
    $this->setPermission ('op');
  }

  public function execute (CommandSender $sender, string $commandLabel, array $args)
  {
    if (!$this->testPermission ($sender)) return;

    if ($sender instanceof Player)
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
      $sender->getInventory ()->addItem ($book);
      Prefix::msg ($sender, '별명 변경권을 지급 받으셨습니다.');
    }else
    {
      Prefix::msg ($sender, '인게임에서 사용하여 주십시오.');
    }
  }
}
