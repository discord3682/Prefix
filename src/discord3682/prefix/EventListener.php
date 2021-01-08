<?php

namespace discord3682\prefix;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerChatEvent;

use discord3682\prefix\form\NameChangeForm;

class EventListener implements Listener
{

  public function onPlayerInteract (PlayerInteractEvent $ev) : void
  {
    $player = $ev->getPlayer ();

    if (Prefix::isExistData ($player))
    {
      $item = $ev->getItem ();
      $data = Prefix::getData ($player);

      if (($entry = $item->getNamedTagEntry ('Prefix')) !== null)
      {
        if ($data->haPrefix ($entry->getValue ()))
        {
          Prefix::msg ($player, '이미 소유한 칭호입니다.');
        }else
        {
          $data->addPrefix ($entry->getValue ());
          $data->setNowPrefix (count ($data->getPrefixes ()) - 1);
          $player->getInventory ()->removeItem ($item->setCount (1));
          Prefix::msg ($player, '[ ' . $entry->getValue () . ' §r§7] 칭호를 획득하셨습니다.');
        }
      }elseif (($entry = $item->getNamedTagEntry ('Color')) !== null)
      {
        if ($data->hasColor ($entry->getValue ()))
        {
          Prefix::msg ($player, '이미 소유한 컬러입니다.');
        }else
        {
          $data->addColor ($entry->getValue ());
          $data->setNowColor (count ($data->getColors ()) - 1);
          $player->getInventory ()->removeItem ($item->setCount (1));
          Prefix::msg ($player, '[ ' . Prefix::$colors [(string) $entry->getValue ()] . ' ] 컬러를 획득하셨습니다.');
        }
      }elseif (($entry = $item->getNamedTagEntry ('Name')) !== null)
      {
        $player->sendForm (new NameChangeForm (false));
        $player->getInventory ()->removeItem ($item->setCount (1));
      }
    }
  }

  public function onPlayerJoin (PlayerJoinEvent $ev) : void
  {
    $player = $ev->getPlayer ();

    if (!Prefix::isExistData ($player))
    {
      Prefix::addData ($player);
    }

    if (Prefix::getData ($player)->getNickname () === '무명')
    {
      $player->sendForm (new NameChangeForm ());
    }
  }

  public const FORMAT_FORM = '<{칭호}§r {이름} {별명}§r> {메시지}';

  public function onPlayerChat (PlayerChatEvent $ev) : void
  {
    $player = $ev->getPlayer ();
    $prefixData = Prefix::getData ($player);

    if (
      !$prefixData instanceof PrefixData or
      count ($prefixData->getPrefixes ()) <= 0 or
      count ($prefixData->getColors ()) <= 0
    ) {
      Prefix::msg ($player, '채팅 사용이 거부 되었습니다.');
    }else
    {
      if ($prefixData->getPrefix ($prefixData->getNowPrefix ()) === null)
      {
        $prefixData->setNowPrefix (count ($prefixData->getPrefixes ()) - 1);
      }

      if ($prefixData->getColor ($prefixData->getNowColor ()) === null)
      {
        $prefixData->setNowColor (count ($prefixData->getColors ()) - 1);
      }

      $ev->setFormat (str_replace (['{칭호}', '{이름}', '{별명}', '{메시지}'], [
        $prefixData->getPrefix ($prefixData->getNowPrefix ()),
        $player->getName (),
        '§l§8[' . $prefixData->getNickname () . ']',
        '§' . $prefixData->getColor ($prefixData->getNowColor ()) . $ev->getMessage ()
      ], self::FORMAT_FORM));
    }
  }
}
