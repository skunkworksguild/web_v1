<?php

// Enchants!
// The WoW API returns enchants as spell ids and there is no easy way to link to said ids via wowhead
// So the workaround is to map the spell id to an actual spell enchant id

// If an enchant comes up looking odd just make sure it is mapped correctly here


// WoD by Slot
// Keeping MoP enchants in for now for alts/whatever
//////////////////
// Neck
//////////////////
$enchant_convert[5285] = 158892; // 40 Critical Strike  // Breath of Critical Strike
$enchant_convert[5292] = 158893; // 40 Haste            // Breath of Haste
$enchant_convert[5293] = 158894; // 40 Mastery          // Breath of Mastery
$enchant_convert[5294] = 158895; // 40 Multistrike      // Breath of Multistrike
$enchant_convert[5295] = 158896; // 40 Versatility      // Breath of Versatility
$enchant_convert[5317] = 158899; // 75 Critical Strike  // Gift of Critical Strike
$enchant_convert[5318] = 158900; // 75 Haste            // Gift of Haste
$enchant_convert[5319] = 158901; // 75 Mastery          // Gift of Mastery
$enchant_convert[5320] = 158902; // 75 Multistrike      // Gift of Multistrike
$enchant_convert[5321] = 158903; // 75 Versatility      // Gift of Versatility

//////////////////
// Back/Cloak
//////////////////
$enchant_convert[5281] = 158877; // 100 Critical Strike  // Breath of Critical Strike
$enchant_convert[5298] = 158878; // 100 Haste            // Breath of Haste
$enchant_convert[5300] = 158879; // 100 Mastery          // Breath of Mastery
$enchant_convert[5302] = 158880; // 100 Multistrike      // Breath of Multistrike
$enchant_convert[5304] = 158881; // 100 Versatility      // Breath of Versatility
$enchant_convert[5310] = 158884; // 100 Critical Strike + 10% Move   // Gift of Critical Strike
$enchant_convert[5311] = 158885; // 100 Haste + 10% Move             // Gift of Haste
$enchant_convert[5312] = 158886; // 100 Mastery + 10% Move           // Gift of Mastery
$enchant_convert[5313] = 158887; // 100 Multistrike + 10% Move       // Gift of Multistrike
$enchant_convert[5314] = 158889; // 100 Versatility + 10% Move       // Gift of Versatility

//////////////////
// Ring
//////////////////
$enchant_convert[5284] = 158907; // 30 Critical Strike  // Breath of Critical Strike
$enchant_convert[5297] = 158908; // 30 Haste            // Breath of Haste
$enchant_convert[5299] = 158909; // 30 Mastery          // Breath of Mastery
$enchant_convert[5301] = 158910; // 30 Multistrike      // Breath of Multistrike
$enchant_convert[5303] = 158911; // 30 Versatility      // Breath of Versatility
$enchant_convert[5324] = 158914; // 50 Critical Strike  // Gift of Critical Strike
$enchant_convert[5325] = 158915; // 50 Haste            // Gift of Haste
$enchant_convert[5326] = 158916; // 50 Mastery          // Gift of Mastery
$enchant_convert[5327] = 158917; // 50 Multistrike      // Gift of Multistrike
$enchant_convert[5328] = 158918; // 50 Versatility      // Gift of Versatility

//////////////////
// Weapon
//////////////////
$enchant_convert[5336] = 159674; // 500 Armor 12s      // Mark of Blackrock
$enchant_convert[5335] = 159673; // 500 Spirit 15s     // Mark of Shadowmoon
$enchant_convert[5334] = 159672; // 500 Multistrke 6s  // Mark of the Frostwolf
$enchant_convert[5331] = 159236; // 1500 + 4500 6s     // Mark of the Shattered Hand
$enchant_convert[5330] = 159235; // 500 Crit 6s        // Mark of the Thunderlord
$enchant_convert[5337] = 159671; // Haste % 20s        // Mark of Warsong
$enchant_convert[5384] = 173323; // 500 Mastery 12s    // Mark of Bleeding Hollow
///Hunter Scopes
$enchant_convert[5275] = 156050; // Multistrike Proc   // Oglethorpe's Scope
$enchant_convert[5276] = 156061; // Crit Proc          // Megawatt Filament
$enchant_convert[5383] = 173287; // Mastery Proc       // Hemet's Heartseeker



////////////////////
// DK Specific Runes
$enchant_convert[3366] = 53331; // Rune of Lichbane
$enchant_convert[3367] = 53342; // Rune of Spellshattering
$enchant_convert[3368] = 53344; // Rune of the Fallen Crusader
$enchant_convert[3369] = 53341; // Rune of Cinderglacier
$enchant_convert[3370] = 53343; // Rune of Razorice
$enchant_convert[3594] = 54446; // Rune of Swordbreaking
$enchant_convert[3595] = 54447; // Rune of Spellbreaking
$enchant_convert[3847] = 62158; // Rune of the Stoneskin Gargoyle
$enchant_convert[3883] = 70164; // Rune of the Nerubian Carapace














////////////////////////////////////////////////////////////
//
//     LEGACY MOP
//
////////////////////////////////////////////////////////////
//////////////////
// Shoulder
//////////////////
// Convert to spells!
$enchant_convert[4910] = 127019; // 180 Stam, 80 Dodge  // Ox Horn Inscription
$enchant_convert[4909] = 127018; // 120 Int, 80 Crit    // Crane Wing Inscription
$enchant_convert[4908] = 127017; // 120 Agi, 80 Crit    // Tiger Claw Inscription
$enchant_convert[4907] = 127016; // 520 Str, 80 Crit    // Tiger Fang Inscription


$enchant_convert[4805] = 126994; // 300 Stam, 100 Dodge // Greater Ox Horn Inscription
$enchant_convert[4806] = 126995; // 200 Int, 100 Crit   // Greater Crane Wing Inscription
$enchant_convert[4804] = 126996; // 200 Agi, 100 Crit   // Greater Tiger Claw Inscription
$enchant_convert[4803] = 126997; // 200 Str, 100 Crit   // Greater Tiger Fang Inscription


$enchant_convert[4912] = 127024; // 750 Stam, 100 Dodge // Secret Ox Horn Inscription
$enchant_convert[4915] = 127023; // 520 Int, 100 Crit   // Secret Crane Wing Inscription
$enchant_convert[4914] = 127021; // 520 Agi, 100 Crit   // Secret Tiger Claw Inscription
$enchant_convert[4913] = 127020; // 520 Str, 100 Crit   // Secret Tiger Fang Inscription

//////////////////
// Back
//////////////////
$enchant_convert[4421] = 104398; // 180 Hit    // Enchant Cloak - Accuracy
$enchant_convert[4422] = 104401; // 200 Stam   // Enchant Cloak - Greater Protection
$enchant_convert[4423] = 104403; // 180 Int    // Enchant Cloak - Superior Intellect
$enchant_convert[4424] = 104404; // 180 Crit   // Enchant Cloak - Superior Critical Strike

//////////////////
// Chest
//////////////////
$enchant_convert[4417] = 104392; // 200 Resil   // Enchant Chest - Super Resilience
$enchant_convert[4418] = 104393; // 200 Spirit  // Enchant Chest - Mighty Spirit
$enchant_convert[4419] = 104395; // 80 All      // Enchant Chest - Glorious Stats
$enchant_convert[4420] = 104397; // 300 Stam    // Enchant Chest - Superior Stamina


//////////////////
// Wrist
//////////////////
$enchant_convert[4411] = 104338; // 170 Mastery  // Enchant Bracer - Mastery
$enchant_convert[4412] = 104385; // 170 Dodge    // Enchant Bracer - Major Dodge
$enchant_convert[4414] = 104389; // 170 Int      // Enchant Bracer - Super Intellect
$enchant_convert[4415] = 104390; // 170 Str      // Enchant Bracer - Exceptional Strength
$enchant_convert[4416] = 104391; // 170 Agi      // Enchant Bracer - Greater Agility


//////////////////
// Hands
//////////////////
$enchant_convert[4430] = 104416; // 170 Haste     // Enchant Gloves - Greater Haste (170 Haste)
$enchant_convert[4431] = 104417; // 170 Expertise // Enchant Gloves - Superior Expertise 
$enchant_convert[4432] = 104419; // 170 Str       // Enchant Gloves - Super Strength 
$enchant_convert[4433] = 104420; // 170 Mastery   // Enchant Gloves - Superior Mastery 

//////////////////
// Legs
//////////////////
$enchant_convert[4872] = 124126; // 170 Str, 100 Crit   // Brutal Leg Armor
$enchant_convert[4871] = 124124; // 170 Agi, 100 Crit   // Sha-Touched Leg Armor
$enchant_convert[4879] = 124125; // 250 Stam, 100 Dodge // Toughened Leg Armor
$enchant_convert[4823] = 124127; // 285 Str, 165 Crit   // Angerhide Leg Armor
$enchant_convert[4822] = 124129; // 285 Agu, 165 Crit   // Shadowleather Leg Armor
$enchant_convert[4824] = 124128; // 439 Stan, 165 Dodge // Ironscale Leg Armor


$enchant_convert[5004] = 125552; // 170 Int, 100 Spirit   // Pearlescent Spellthread
$enchant_convert[5003] = 125553; // 170 Int, 100 Crit     // Cerulean Spellthread
$enchant_convert[4826] = 125554; // 285 Int, 165 Spriit   // Greater Pearlescent Spellthread
$enchant_convert[4825] = 125555; // 285 Int, 165 Crit     // Greater Cerulean Spellthread


//////////////////
// Feet
//////////////////
$enchant_convert[4426] = 104407; // 175 Haste 				    // Enchant Boots - Greater Haste
$enchant_convert[4427] = 104408; // 175 Hit   				    // Enchant Boots - Greater Precision
$enchant_convert[4428] = 104409; // 140 Agil, Inc Run Speed     // Enchant Boots - Blurred Speed
$enchant_convert[4429] = 104414; // 140 Mastery, Inc Run Speed  // Enchant Boots - Pandaren's Step


//////////////////
// Main Hand / 2H
//////////////////
$enchant_convert[4441] = 104425; // Crit/Haste/Mastery       // Enchant Weapon - Windsong
$enchant_convert[4442] = 104427; // Int and Spirit           // Enchant Weapon - Jade Spirit
$enchant_convert[4443] = 104430; // Extra Elemental Damage   // Enchant Weapon - Elemental Force
$enchant_convert[4444] = 104434; // Strength or Agil         // Enchant Weapon - Dancing Steel
$enchant_convert[4445] = 104440; // Damage Absorb            // Enchant Weapon - Colossus
$enchant_convert[4446] = 104442; // Dodge				     // Enchant Weapon - River's Song

$enchant_convert[4699] = 127115; // Agil to Ranged           // Lord Blastington's Scope of Doom
$enchant_convert[4700] = 127116; // Crit to Ranged           // Mirror Scope




//////////////////
// Offhand
//////////////////
$enchant_convert[5001] = 131928; // Damage when Shield Struck    // Ghost Iron Shield Spike
$enchant_convert[4434] = 104445; // 165 Int                      // Enchant Off-Hand - Major Intellect
$enchant_convert[4993] = 130758;  // 170 Parry                    // Enchant Shield - Greater Parry


////////////////////////////////////////////////////////////////////////////
// Professions
// These tend to be actual spells rather than items

//////////////////
// Tinkers (Engineering)
//////////////////
$enchant_convert[4223] = 55016;  // Nitro Boosts
$enchant_convert[4897] = 126392; // Goblin Glider
$enchant_convert[4898] = 126731; // Synapse Springs
$enchant_convert[4697] = 108789; // Phase Fingers
$enchant_convert[5000] = 109099; // Watergliding Jets
$enchant_convert[3601] =  54793; // Frag Belt
$enchant_convert[3605] =  55002; // Frag Belt
$enchant_convert[3599] =  54736; // EMP Generator


//////////////////
// Embroidery (Tailoring)
//////////////////
$enchant_convert[4892] = 125481; // 2000 Int Proc     // Lightweave Embroidery
$enchant_convert[4893] = 125482; // 3000 Spirit Proc  // Darkglow Embroidery
$enchant_convert[4894] = 125483; // 4000 AP Proc      // Swordguard Embroidery 

// Tailoring Legs
$enchant_convert[4895] = 125496; // 285 Int + 165 Crit    // Master's Spellthread
$enchant_convert[4896] = 125497; // 285 Int + 165 Spirit  // Sanctified Spellthread

//////////////////
// Leatherworking Enchants
//////////////////
// Bracer
$enchant_convert[4875] = 124551; // 500 Agi     // Fur Lining - Agility
$enchant_convert[4877] = 124552; // 500 Int     // Fur Lining - Intellect
$enchant_convert[4878] = 124553; // 750 Stam    // Fur Lining - Stamina
$enchant_convert[4879] = 124554; // 500 Str     // Fur Lining - Strength
// Leg
$enchant_convert[4881] = 125481; // 285 Str + 165 Crit      // Draconic Leg Reinforcements
$enchant_convert[4882] = 125481; // 430 Stam + 165 Dodge    // Heavy Leg Reinforcements
$enchant_convert[4880] = 125481; // 285 Agi + 165 Crit      // Primal Leg Reinforcements



//////////////////
// Ring Enchants
//////////////////
$enchant_convert[4359] = 103461;  // 160 Agi   // Enchant Ring - Greater Agility
$enchant_convert[4360] = 103462;  // 160 Int   // Enchant Ring - Greater Intellect
$enchant_convert[4361] = 103462;  // 240 Stam  // Enchant Ring - Greater Stamina
$enchant_convert[4807] = 103465;  // 160 Str   // Enchant Ring - Greater Strength





?>