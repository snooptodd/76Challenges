{
  Generate csv file of form ID list contents
  export Leveled lists LVLO entries
	idealy this will loop through the first leveled list and get elements from the sub leveled lists its not there yet in fact it is barely usable
	Version: 0.1
	run the folloing to clean up the output. ya this could be done in the script. I barely understand what the script is doing and dont want to break it.
	sed dmp_minerva.txt -e 's/BS02_SpecialVendor_Minerva_LL._GoldVendor.//g' -e 's/.\[\w*:\w*\].//g' -e 's/ .*Plan: //' -e 's/"/|/' |sort //"
	Version 0.1.1
	the output has been cleaned up and gold values added. 
	NOTE: Gold value does not have Minerva's discount applied

	version 0.2.0
	used scratchy's script as a base to get rid of the deeply nested loops

  
  Inventory list ID | Plan Name | gold value 
	01|Farmable Dirt Tiles|50
	01|Sympto-Matic|2000|

	TODO get this thing to start from formid 005FFDEB and follow the trail 
    
}
unit UserScript;

var // Initialize variables
  slExport: TStringList;
  currentItemEDID, filename, value, Minerva_Inventory_ListID, FULLname, GoldBullionValue, GoldBullionValueGLOB, s, valueType, formid: string;
  Minerva_LeveledListEntries, Minerva_CurrentLeveledListEntry, Minerva_CurrentLeveledListRecord, Minerva_CurrentLVLO, Minerva_CurrentReference : IInterface;
  Minerva_Inventory_LeveledListEntries, Minerva_Inventory_currentLeveledListEntry, Minerva_Inventory_currentLeveledListRecord, Minerva_Inventory_currentLVLO, Minerva_Inventory_currentReference : IInterface;
  i, j: integer;

function Initialize: integer;
begin
  slExport := TStringList.Create;
end;

function Process(e: IInterface): integer;
begin
  formid := IntToHex(FixedFormID(e),8);
  // filename := EditorID(e); // Get EDID for filename
  // slExport.Add(IntToHex(FormID(e) and $FFFFFF, 8) + ',' + EditorID(e)); // Add selected record's FormID & EDID to top of CSV
  // slExport.Add('FormID,EDID,FULL'); //Row headers
  Minerva_LeveledListEntries := ElementByPath(e, 'Leveled List Entries'); // Get array of Leveled List entries https://ptb.discord.com/channels/471930020454072348/483466910571167746/1101658577023803442
  for i := 0 to ElementCount(Minerva_LeveledListEntries) - 1 do begin // For each FormID entry
    // Minerva main record '005FFDEB'
    // LVLI \ Leveled List Entries \ Leveled List Entry \ LVLO - LVLO \ Reference
    Minerva_CurrentLeveledListEntry := ElementByIndex(Minerva_LeveledListEntries, i); // Grab i'th Leveled list Entry
    Minerva_CurrentLVLO := ElementBySignature(Minerva_CurrentLeveledListEntry,'LVLO');
    Minerva_CurrentReference := ElementByName(Minerva_CurrentLVLO,'Reference');
    Minerva_CurrentLeveledListRecord := LinksTo(Minerva_CurrentReference); // Get referenced record's mainRecordElement
    // this should be the LVLO/reference that is in the i'th Leveled list entry
    Minerva_Inventory_LeveledListEntries := ElementByPath(Minerva_CurrentLeveledListRecord, 'Leveled List Entries'); // Get array of Leveled List entries https://ptb.discord.com/channels/471930020454072348/483466910571167746/1101658577023803442
    for j := 0 to ElementCount(Minerva_Inventory_LeveledListEntries) - 1 do begin // For each FormID entry
      // Minerva linked records ( ie BS02_SpecialVendor_Minerva_LLS_GoldVendor_01 [LVLI:00602235] )
      // LVLI \ Leveled List Entries \ Leveled List Entry \ LVLO - LVLO \ Reference
      Minerva_Inventory_currentLeveledListEntry := ElementByIndex(Minerva_Inventory_LeveledListEntries, j); // Grab i'th Leveled list Entry
      Minerva_Inventory_currentLVLO := ElementBySignature(Minerva_Inventory_currentLeveledListEntry,'LVLO');
      Minerva_Inventory_currentReference := ElementByName(Minerva_Inventory_currentLVLO,'Reference');
      Minerva_Inventory_currentLeveledListRecord := LinksTo(Minerva_Inventory_currentReference); // Get referenced record's mainRecordElement
      // this should be the LVLO/reference that is in the i'th Leveled list entry
      // W05_Recipe_Armor_SecretService_ArmLeft_GoldVendor "Plan: Secret Service Left Arm" [BOOK:0059D543]
      //LVLI \ Leveled List Entries \ Leveled List Entry \ Conditions \ Condition \ CTDA - CTDA \ Comparison Value
      //Minerva_Inventory_ListID := GetElementEditValues(ElementBySignature(ElementByName(ElementByName(Minerva_CurrentLeveledListEntry,'Conditions'),'Condition'),'CTDA'),'Comparison Value'); 
      Minerva_Inventory_ListID := delete(GetElementEditValues(Minerva_CurrentLeveledListRecord, 'EDID'),1,42); //BS02_SpecialVendor_Minerva_LLS_GoldVendor_01 => 01
      // "Plan: " is removed by the delete it really should be a replace but i dont feel like doing that in pascal
      FULLname := GetElementEditValues(Minerva_Inventory_currentLeveledListRecord , 'FULL');
      if pos('Plan:',FULLname) = 1 then
        FULLname := delete(FULLname,1,6);
      GoldBullionValue :=  GetElementEditValues(ElementBySignature(Minerva_Inventory_currentLeveledListRecord, 'DATA'), 'Value');
      GoldBullionValueGLOB := GetElementEditValues(LinksTo(ElementBySignature(Minerva_Inventory_currentLeveledListRecord, 'BVGO')), 'FLTV');
      s := '';
      if pos('M',Minerva_Inventory_ListID) = 0 then begin // if this is not a 'big' sale add to s
        // if GoldBullionValueGLOB has a value then that is used for the gold value of the item
        if GoldBullionValueGLOB = '' then 
          s := Minerva_Inventory_ListID + '|' + FULLname + '|' + GoldBullionValue + '|' + formid
        else 
          s := Minerva_Inventory_ListID + '|' + FULLname + '|' + StringReplace(GoldBullionValueGLOB,'.000000','',[rfReplaceAll, rfIgnoreCase]); + '|' + formid;
        AddMessage(s);
        slExport.add(s);
      end;
    end;
  end;
end;

function Finalize: integer;
var
  dlgSave: TSaveDialog;
  ExportFileName: string;
begin
  if slExport.Count <> 0 then begin
    dlgSave := TSaveDialog.Create(nil);
    try
      dlgSave.Options := dlgSave.Options + [ofOverwritePrompt];
      dlgSave.Filter := 'text (*.txt)|*.txt';
      dlgSave.InitialDir := '\\doc\todd\src\76Challenges';
      dlgSave.FileName := 'dmp_minerva.txt';
      if dlgSave.Execute then begin
        ExportFileName := dlgSave.FileName;
        AddMessage('Saving ' + ExportFileName);
        slExport.SaveToFile(ExportFileName);
      end;
    finally
      dlgSave.Free;
    end;
  end;
  slExport.Free;
end;

end.