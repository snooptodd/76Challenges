{
	export Leveled lists LVLO entries
	idealy this will loop through the first leveled list and get elements from the sub leveled lists its not there yet in fact it is barely usable
	Version: 0.1
	 run the folloing to clean up the output. ya this could be done in the script. I barely understand what the script is doing and dont want to break it.
	 sed dmp_minerva.txt -e 's/BS02_SpecialVendor_Minerva_LL._GoldVendor.//g' -e 's/.\[\w*:\w*\].//g' -e 's/ .*Plan: //' -e 's/"/|/' |sort

}

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
		h, i, j, k: integer;
		p, r, L0, L1, L2, L3: IInterface;
		s: string;

	begin

		slParentElements.Clear;
		p := GetFile(e);
		r := RecordByFormID(p, FormIDStart, True); //change 'e' to 'r' to not use UI selected records
		// signature check
		if (Signature(e) = 'LVLI') or (Signature(e) = 'LVLN') or (Signature(e) = 'LVSP') then
			slParentElements.Add('Leveled List Entries')
		else
			exit;

		// -------------------------------------------------------------------------------
		for h := 0 to slParentElements.Count - 1 do begin

			// level 1
			L0 := ElementByPath(e, slParentElements[h]);
			//AddMessage(Name(e));
			// -------------------------------------------------------------------------------
			for i := 0 to ElementCount(L0) - 1 do begin

				// level 2
				L1 := ElementByIndex(L0, i);
				//AddMessage('L2 ' + GetEditValue(L1) + Name(L1));

				// -------------------------------------------------------------------------------
				for j := 0 to ElementCount(L1) - 1 do begin

					// level 3
					L2 := ElementByIndex(L1, j);
					//
					if CompareStr(Signature(L2), 'LVLO') = 0 then begin
						AddMessage(Name(e) + ' | ' + GetElementEditValues(L2, 'Reference'));
						slExport.Add(Name(e) + ' | ' + GetElementEditValues(L2, 'Reference'));
					end; // end if
					// idealy this will loop through the first leveled list and get elements from the sub leveled lists its not there yet in fact it is barely usable
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
      dlgSave.InitialDir := 'C:\Users\todd\Documents\';
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