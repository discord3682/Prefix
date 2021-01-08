<?php

namespace discord3682\prefix;

use discord3682\prefix\event\ChangePrefixEvent;

class PrefixData
{

  private $player;
  private $prefixes;
  private $nowPrefix;
  private $colors;
  private $nowColor;
  private $nickname;

  public function __construct (string $player, array $prefixes, int $nowPrefix, array $colors, int $nowColor, string $nickname)
  {
    $this->player = $player;
    $this->prefixes = $prefixes;
    $this->nowPrefix = $nowPrefix;
    $this->colors = $colors;
    $this->nowColor = $nowColor;
    $this->nickname = $nickname;
  }

  public function getPlayer () : string
  {
    return $this->player;
  }

  public function getPrefixes () : array
  {
    return $this->prefixes;
  }

  public function getPrefix (int $key) : ?string
  {
    return $this->prefixes [(int) $key] ?? null;
  }

  public function getPrefixToKey (string $prefix) : ?int
  {
    return in_array ($prefix, $this->prefixes) ? array_search ($prefix, $this->prefixes) : null;
  }

  public function haPrefix (string $prefix) : bool
  {
    return in_array ($prefix, $this->prefixes);
  }

  public function addPrefix (string $prefix)
  {
    $this->prefixes [] = $prefix;
  }

  public function removePrefix (int $key)
  {
    unset ($this->prefixes [$key]);
  }

  public function getNowPrefix () : int
  {
    return $this->nowPrefix;
  }

  public function setNowPrefix (int $key, bool $force = false)
  {
    $ev = new ChangePrefixEvent ($this->player, $this->nowPrefix, (int) $key);
    $ev->call ();

    if (!$ev->isCancelled () or $force)
    {
      $this->nowPrefix = $key;
    }
  }

  public function getColors () : array
  {
    return $this->colors;
  }

  public function getColor (int $key) : ?string
  {
    return $this->colors [(int) $key] ?? null;
  }

  public function getColorToKey (string $color) : ?int
  {
    return array_search ($color, $this->colors) !== false ? array_search ($color, $this->colors) : null;
  }

  public function hasColor (string $color) : bool
  {
    return in_array ($color, $this->colors);
  }

  public function addColor (string $color)
  {
    $this->colors [] = $color;
  }

  public function removeColor (int $key)
  {
    unset ($this->colors [$key]);
  }

  public function getNowColor () : int
  {
    return $this->nowColor;
  }

  public function setNowColor (int $key)
  {
    $this->nowColor = $key;
  }

  public function getNickname () : string
  {
    return $this->nickname;
  }

  public function setNickname (string $nickname)
  {
    $this->nickname = $nickname;
  }

  public function serialize () : string
  {
    return json_encode (array ($this->player, $this->prefixes, $this->nowPrefix, $this->colors, $this->nowColor, $this->nickname));
  }

  public static function deserialize (string $data) : self
  {
    return new PrefixData (...json_decode ($data, true));
  }
}
