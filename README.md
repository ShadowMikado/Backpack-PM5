# Backpack

This plugin adds customizable backpack functionality to your PocketMine MP server.

## Configuration

The `config.yml` file contains settings for the backpack:
(permission is now starting with `backpack` + `yourpermissionname`

```yaml
item: "clay_ball"

permission:
  enabled: false
  name: "backpack.use"

messages:
  item_disabled: "§cThis item cannot be placed in a backpack."
  backpack_dropped: "§aYour backpack has been dropped on the ground because your inventory is full!"

item_configuration:
  display_name: "Backpack"
  lore_empty: "§r§9Empty§r"
  lore_content: "§r§9 {item} §6x{count}§r"
  display_name_after_use: "§r§f{player}'s Backpack"
  can_put_backpack_in_backpack: false

ui_configuration:
  display_name: "{player}'s Backpack"
  inv_size: 9

pack_configuration:
  pack_name: "BackpackRP.mcpack"
```

## Screenshots

![image](https://github.com/ShadowMikado/Backpack-PM5/assets/89030950/366c68db-c9de-4089-af48-2aefc3d2416a)
![image](https://github.com/ShadowMikado/Backpack-PM5/assets/89030950/29da46e2-eb0f-48c3-8da3-37417f512424)



## Contributions

Contributions and suggestions are welcome. Feel free to submit issues or create pull requests to enhance this plugin.
