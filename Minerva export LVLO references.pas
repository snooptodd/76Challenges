
	//export Leveled lists LVLO entries
	//idealy this will loop through the first leveled list and get elements from the sub leveled lists its not there yet in fact it is barely usable
	//Version: 0.1
	//run the folloing to clean up the output. ya this could be done in the script. I barely understand what the script is doing and dont want to break it.
	//sed dmp_minerva.txt -e 's/BS02_SpecialVendor_Minerva_LL._GoldVendor.//g' -e 's/.\[\w*:\w*\].//g' -e 's/ .*Plan: //' -e 's/"/|/' |sort //"
	// Version 0.1.1
	// the output has been cleaned up and gold values added. 
	// NOTE: Gold value does not have Minerva's discount applied
	// Inventory list ID | Plan Name | gold value 
	// 01|Farmable Dirt Tiles|50
	// 01|Sympto-Matic|2000|

	// TODO get this thing to start from formid 005FFDEB and follow the trail 


unit UserScript;

const
  FormIDStart = $005FFDEB; // starting formid
  FormIDCount = 1;        // formids to list


var
	slParentElements: TStringList;
	sTargetLevel: string;
	bDebug: bool;
	slExport: TStringList;

//============================================================================
function Initialize: integer;

	begin

		bDebug := true;

		slExport := TStringList.Create;

		slParentElements := TStringList.Create;

		if bDebug then begin
			ClearMessages();
			AddMessage('Applying script...' + #13#10);
		end;

	end;

//============================================================================
function Process(e: IInterface): integer;

	var
		h, i, j, k, l: integer;
		p, r, L0, L1, L2, L3, L4: IInterface;
		s, FULLname, GoldBullionValue, GoldBullionValueGLOB,EditorID,MinervaInventoryListID : string;

	begin

		slParentElements.Clear;
		p := GetFile(e);
		r := RecordByFormID(p, FormIDStart, True); //change 'e' to 'r' to not use UI selected records
		// signature check
		if (Signature(e) = 'LVLI') or (Signature(e) = 'LVLN') or (Signature(e) = 'LVSP') then
			slParentElements.Add('Leveled List Entries')
		else
			exit;
		// Leveled List 
		// -------------------------------------------------------------------------------
		for h := 0 to slParentElements.Count - 1 do begin

			// level 1 Leveled List Entries
			L0 := ElementByPath(e, slParentElements[h]);
			
			// -------------------------------------------------------------------------------
			for i := 0 to ElementCount(L0) - 1 do begin

				// level 2 Leveled List Entry
				L1 := ElementByIndex(L0, i);
				
				// -------------------------------------------------------------------------------
				for j := 0 to ElementCount(L1) - 1 do begin

					// level 3 LVLO - LVLO
					L2 := ElementByIndex(L1, j);
					//
					if CompareStr(Signature(L2), 'LVLO') = 0 then begin
						// LVLI \ Leveled List Entries \ Leveled List Entry \ LVLO - LVLO \ Reference
						// ElementByName(L2, 'Reference') gets element of LVLO named 'Reference'
						// LinksTo(ElementByName(L2, 'Reference')) 'opens' the record 
						L3 := LinksTo(ElementByName(L2, 'Reference')); // Reference
						MinervaInventoryListID := delete(GetElementEditValues(e, 'EDID'),1,42);
						//if CompareStr(Signature(L3), 'BOOK') = 0 then begin
							FULLname := delete(GetElementEditValues(L3 , 'FULL'),1,6);
							GoldBullionValue :=  GetElementEditValues(ElementBySignature(L3, 'DATA'), 'Value');
							GoldBullionValueGLOB := GetElementEditValues(LinksTo(ElementBySignature(L3, 'BVGO')), 'FLTV');
							// if GoldBullionValueGLOB has a value then that is used for the gold value of the item
							if GoldBullionValueGLOB = '' then 
								s := MinervaInventoryListID + '|' + FULLname + '|' + GoldBullionValue
							else 
								s := MinervaInventoryListID + '|' + FULLname + '|' + StringReplace(GoldBullionValueGLOB,'.000000','',[rfReplaceAll, rfIgnoreCase]);
							//AddMessage(s);
							slExport.Add(s);
						//end; // end if
					end; // end if

				end; // end for - level 3

			end; // end for - level 2

		end; // end for - level 1

		slParentElements.Clear;

	end;

//============================================================================
function Finalize: integer;
var
  dlgSave: TSaveDialog;
  ExportFileName: string;
begin
  if slExport.Count <> 0 then begin
    //slExport.sort;
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