<?php

/**
 * PlagueEntity v0.0.1
 * Licensed under the PlagueEntity License.
 * Author: "Shaheryar Sohail"
 *
 * By the license, you're not allowed to remove this documented snippet.
 */

declare(strict_types=1);

namespace PlagueEntity;

use pocketmine\entity\Entity;
use pocketmine\entity\Human;
use pocketmine\entity\Skin;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;

class PE {

    /** @const string */
    private const PROJECT_REPO = "https://github.com/TheBlackPlague/PlagueEntity/";

    /** @var array */
    private static $plagueEntity = [];
    /** @var bool */
    private static $enableLog = false;
    /** @var PluginBase */
    private static $pluginBase;

    /**
     * Setup PlagueEntity API.
     *
     * @param PluginBase $pluginBase
     * @param array $resourceFile
     * @param bool $enableLog
     * @return void
     */
    public static function Setup(PluginBase $pluginBase, array $resourceFile, bool $enableLog = false): void {
        foreach ($resourceFile as $file) {
            $pluginBase->saveResource($file);
        }
        self::$pluginBase = $pluginBase;
        self::$enableLog = $enableLog;
        $pluginBase->getLogger()->info("Thank you for using PlagueEntity. Project Link: " . self::PROJECT_REPO);
    }

    /**
     * Spawn a PlagueEntity for a player.
     *
     * @param Player $player
     * @param Vector3 $position
     * @param string $entityName
     * @param string $entitySkinPNG
     * @param string $entityGeometryName
     * @param string $entityGeometryJSONFile
     * @return void
     */
    public static function SpawnPlagueEntity(Player $player, Vector3 $position, string $entityName, string $entitySkinPNG, string $entityGeometryName, string $entityGeometryJSONFile): void {
        $skinID = $entityName;
        /** Make Skin Data. */
        $path = self::$pluginBase->getDataFolder() . $entitySkinPNG . ".png";
        $image = imagecreatefrompng($path);
        $skinData = "";
        $sizeY = (int)getimagesize($path)[1]; // Allows different sizes.
        $sizeX = (int)getimagesize($path)[0]; 
        for ($y = 0; $y < $sizeY; $y++) {
            for ($x = 0; $x < $sizeX; $x++) {
                // Convert Image Pixel to RGBA
                $colorAt = imagecolorat($image, $x, $y);
                $a = ((~((int)($colorAt >> 24))) << 1) & 0xff;
                $r = ($colorAt >> 16) & 0xff;
                $g = ($colorAt >> 8) & 0xff;
                $b = $colorAt & 0xff;
                // Create a Byte Array
                $skinData .= chr($r) . chr($g) . chr($b) . chr($a);
            }
        }
        imagedestroy($image);
        /** Skin Data made. */
        $nbt = Entity::createBaseNBT($position, null, 180, 0);
        $player->saveNBT();
        $playerSkin = $player->namedtag->getCompoundTag("Skin");
        assert($playerSkin !== null);
        $nbt->setTag(clone $playerSkin);
        $human = new Human($player->getLevel(), $nbt);
        $human->setNameTag($entityName);
        $human->setNameTagVisible(false);
        $customSkin = new Skin(
            $skinID,
            $skinData,
            "",
            "geometry." . $entityGeometryName,
            file_get_contents(self::$pluginBase->getDataFolder() . $entityGeometryJSONFile . ".json")
        );
        $human->setSkin($customSkin);
        $human->sendSkin();
        $human->spawnTo($player);
        if (self::$enableLog != false) {
            $logText = "Spawning entity at [" . $position->getX() . ", " . $position->getY() . ", " . $position->getZ() . "].";
            self::$pluginBase->getLogger()->info("[PlagueEntityRuntime]: " . $logText);
        }
        self::$plagueEntity[$player->getName() . $entityName] = $human;
        return;
    }

    /**
     * DeSpawn a Plague Entity for a player.
     *
     * @param Player $player
     * @param string $entityName
     * @return void
     */
    public static function DeSpawnPlagueEntity(Player $player, string $entityName): void {
        if (array_key_exists($player->getName() . $entityName, self::$plagueEntity) == false) {
            return;
        }
        if (self::$plagueEntity[$player->getName() . $entityName] != null) {
            self::$plagueEntity[$player->getName() . $entityName]->close(); // Close the entity rather than killing it.
            self::$plagueEntity[$player->getName() . $entityName] = null;
            return;
        }
        return;
    }

}