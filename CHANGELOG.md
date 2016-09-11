# Changelog

## 20160909,
Added: New fields to the database, differences of energy import/export. See below 'schemaupdate 2'

Fixed: Consumption / Energyimport (kWh) is not correctly calculated.<br>
The active power usage was used to calculate the consumed power.<br>
Now the power usage is calculated from the measured kWh every 5 minutes.<br>
PVoutput requires a daily cummulative value to be posted in v3.

note: Cummulative Energyimport values are needed for field v3.<br>
note: Found typo's in Enery, fixed.

### IF Schemaversion is 1 (Takes a while to process these commands)
ALTER TABLE P1device ADD EnergyimportPeakOffDelta INT(10) NULL DEFAULT '0';<br>
ALTER TABLE P1device ADD EnergyimportPeakDelta INT(10) NULL DEFAULT '0';<br>
ALTER TABLE P1device ADD EnergyexportDelta INT(10) NULL DEFAULT '0';<br>
ALTER TABLE P1device ADD EnergyexportPeakOffDelta INT(10) NULL DEFAULT '0';<br>
ALTER TABLE P1device ADD EnergyexportPeakDelta INT(10) NULL DEFAULT '0';<br>
ALTER TABLE P1device ADD EnergyimportDaily INT(10) UNSIGNED NULL DEFAULT '0';<br>
ALTER TABLE P1device ADD EnergyexportDaily INT(10) NULL DEFAULT '0';<br>
UPDATE Config SET Value='2' WHERE Config.Key='SchemaVersion';

## 20160110,
Initial version, moved project to GIT.