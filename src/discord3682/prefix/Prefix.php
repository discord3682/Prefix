<?php

namespace discord3682\prefix;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\nbt\tag\StringTag;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\Server;

use discord3682\prefix\cmd\ColorBookCommand;
use discord3682\prefix\cmd\ColorSelectCommand;
use discord3682\prefix\cmd\MyPrefixCommand;
use discord3682\prefix\cmd\PrefixBookCommand;
use discord3682\prefix\cmd\PrefixSelectCommand;
use discord3682\prefix\cmd\NameBookCommand;

function convert ($player) : string
{
  if ($player instanceof Player)
    return strtolower ($player->getName ());

  if (($player = Server::getInstance ()->getPlayer ($player)) !== null)
    return strtolower ($player->getName ());

  return strtolower ($player);
}

class Prefix extends PluginBase
{

  private const PREFIX = '§l§b[칭호]§r§7 ';

  private static $database = null;

  protected static $prefixDatas = [];

  public static $colors = [
    'b' => '민트색',
    'a' => '초록색',
    'd' => '보라색',
    'c' => '주황색',
    'e' => '노란색',
    'f' => '하얀색',
    '1' => '파란색',
    '2' => '초록색',
    '3' => '청록색',
    '4' => '빨강색',
    '5' => '진한 보라색',
    '6' => '황금색',
    '7' => '회색',
    '8' => '진한 회색',
    '9' => '어두운 청록색',
    '0' => '검은색'
  ];

  public function onEnable () : void
  {
    self::$database = new Config ($this->getDataFolder () . 'Data.yml', Config::YAML);

    foreach (self::$database->getAll () as $key => $data)
    {
      $prefixData = PrefixData::deserialize ($data);
      self::$prefixDatas [$key] = $prefixData;
    }

    $this->getServer ()->getPluginManager ()->registerEvents (new EventListener (), $this);
    $this->getServer ()->getCommandMap ()->registerAll ('discord3682', [
      new ColorBookCommand (),
      new ColorSelectCommand (),
      new MyPrefixCommand (),
      new PrefixBookCommand (),
      new PrefixSelectCommand (),
      new NameBookCommand ()
    ]);
  }

  public function onDisable () : void
  {
    $data = [];

    foreach (self::$prefixDatas as $player => $prefixData)
    {
      $data [$player] = $prefixData->serialize ();
    }

    self::$database->setAll ($data);
    self::$database->save ();
  }

  public static function msg ($player, string $msg) : void
  {
    $player->sendMessage (self::PREFIX . $msg);
  }

  public static function getPrefixBook (string $prefix) : Item
  {
    $book = Item::get (340, 0, 1);
    $book->setCustomName ('§f칭호북 : ' . $prefix);
    $book->setLore ([
      '',
      '§b- - - - - - - - - - - - - - - - ',
      '',
      '§f터치하여 칭호북 사용',
      '§f칭호 : ' . $prefix,
      '',
      '§b- - - - - - - - - - - - - - - - ',
      ''
    ]);
    $book->addEnchantment (new EnchantmentInstance (Enchantment::getEnchantment (Enchantment::INFINITY), 0));
    $book->setNamedTagEntry (new StringTag ('Prefix', $prefix));

    return $book;
  }

  public static function isExistData ($player) : bool
  {
    return isset (self::$prefixDatas [convert ($player)]) or isset (self::$database->getAll () [convert ($player)]) or self::getData ($player) !== null ? in_array (self::getData ($player)->serialize (), self::$database->getAll ()) : false;
  }

  public static function getData ($player) : ?PrefixData
  {
    return self::$prefixDatas [convert ($player)] ?? null;
  }

  public const DEFAULT_PREFIX = '§l§a[떠돌이]';
  public const DEFAULT_NICK = '무명';
  public const DEFAULT_COLOR = 'f';

  public static function addData ($player) : bool
  {
    if (isset (self::$prefixDatas [convert ($player)])) return false;

    $prefixData = new PrefixData (convert ($player), [], 0, [], 0, self::DEFAULT_NICK);
    $prefixData->addPrefix (self::DEFAULT_PREFIX);
    $prefixData->addColor (self::DEFAULT_COLOR);
    self::$prefixDatas [convert ($player)] = $prefixData;

    return true;
  }
}
