# PlagueEntity
A general-purpose custom entity API for [PocketMine-MP](https://github.com/pmmp/PocketMine-MP).

![SplashImage](https://i.ibb.co/nLtXTKG/Plague-Entity-Splash-Image.png)
 
## Documentation
This is a very basic guide of spawning your entity using clear 
standard methods available in the API.

- First you must have a `resources` directory in your plugin base
directory. Documentation on how to create a directory is not
provided. This resources folder must contain the skin, and 
custom geometry for the custom entity you're trying to spawn.

    Custom geometry and skin can be created using [BlockBench](https://blockbench.net).
    An example is available in the `example` directory of the repository.

    Expected structure:
    ```
    - <Plugin Name>
    |- plugin.yml
    |- src
    |- resources
     | - Skin001.png <in PNG FORMAT ONLY>
     | - Geometry001.json <in JSON FORMAT ONLY>
    ```
  
    If your structure is not similar, you will not be provided any
    help by me in any way.
    
- Copy the `PlagueEntity` directory in the project repository to
your `src` directory. Then import it your class using:

    ```php
    use PlagueEntity\PE;
    ```

- Then create an array containing the names and format of these 
files:
    ```php
    $myFile = ["Skin001.png", "Geometry001.json"];
    ```
  
- Setup the API by calling the `Setup()`method:
    ```php
    PE::Setup(<PluginBase Instance>, $myFile);
    ```
  
- Spawn an entity for player using the API:
    ```php
    $entityName = "Skin001"; // can set to anything. Must be unique to each entity. Generate it however you like.
    $entitySkinPNG = "Skin001"; // Your skin file name.
    $entityGeometryName = "geometry001"; // Your model name inside the JSON.
    $entityGeometryJSONFile = "Geometry001"; // Your JSON file name.
    $position = new Vector3(<int|float>x, <int|float>y, <int|float>z);
    PE::SpawnPlagueEntity(<Player Instance>, $position, $entityName, $entitySkinPNG, $entityGeometryName, $entityGeometryJSONFile);
    ```
  
- **[OPTIONAL]** DeSpawn an entity for player using the API:
    ```php
    $entityName = "Skin001"; // Your unique entity name.
    PE::DeSpawnPlagueEntity(<Player Instance>, $entityName);
    ```
  
It is that simple. If you use this API in your server, please
let me know and I'll add your server to this page. **(I can
choose not to)**

### License
```
PlagueEntity License

Copyright (c) 2019 Shaheryar Sohail

...
```

The full version of license is available on this repository.
By using this API, you're by default agreeing to it.
