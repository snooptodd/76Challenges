{
  Export list of challenges for use with 76challenges.php

  it would be nice to have the script pre slelect the correct formid and sort and uniq the output. 
}
unit UserScript;

const
  // skip those records
  sRecordsToSkip = 'REFR,PGRD,PHZD,ACHR,NAVM,NAVI,LAND';
  
var
  slExport: TStringList;

function Initialize: integer;
begin
  slExport := TStringList.Create;
end;

function Process(e: IInterface): integer;
begin
  if Pos(Signature(e), sRecordsToSkip) <> 0 then
    Exit;
  if Pos('CUT_', EditorID(e)) <> 0 then
    Exit;
  if Pos('Lifetime', EditorID(e)) <> 0 then
    Exit;
  if Pos('SUB_', EditorID(e)) <> 0 then
    Exit;
  if Pos('Sub_', EditorID(e)) <> 0 then
    Exit;
  if Pos('zzz', EditorID(e)) <> 0 then
    Exit;
  if Pos('_Epic', EditorID(e)) <> 0 then
    Exit;
  
  if Pos('_Halloween_', EditorID(e)) <> 0 then
    slExport.Add(GetElementEditValues(e, 'FULL') + ' (x' + GetElementEditValues(e, 'TNAM') + ')|' + '🎃|' + GetElementEditValues(e, 'MNAM') )

  else if Pos('CALL TO AXE-ION:', GetElementEditValues(e,'FULL')) <> 0 then
    slExport.Add(GetElementEditValues(e, 'FULL') + ' (x' + GetElementEditValues(e, 'TNAM') + ')|' + '🪓|' + GetElementEditValues(e, 'MNAM') )

  else if Pos('_Love_', EditorID(e)) <> 0 then
    slExport.Add(GetElementEditValues(e, 'FULL') + ' (x' + GetElementEditValues(e, 'TNAM') + ')|' + '❤️|' + GetElementEditValues(e, 'MNAM') )
  
  else if Pos('_Valentines_', EditorID(e)) <> 0 then
    slExport.Add(GetElementEditValues(e, 'FULL') + ' (x' + GetElementEditValues(e, 'TNAM') + ')|' + '❤️|' + GetElementEditValues(e, 'MNAM') )

  else if Pos('_ST_Patrick_', EditorID(e)) <> 0 then
    slExport.Add(GetElementEditValues(e, 'FULL') + ' (x' + GetElementEditValues(e, 'TNAM') + ')|' + '☘️|' + GetElementEditValues(e, 'MNAM') )

  else if Pos('_Easter', EditorID(e)) <> 0 then
    slExport.Add(GetElementEditValues(e, 'FULL') + ' (x' + GetElementEditValues(e, 'TNAM') + ')|' + '🐰|' + GetElementEditValues(e, 'MNAM') )

  else if Pos('STORM:', GetElementEditValues(e, 'FULL')) <> 0 then
    slExport.Add(GetElementEditValues(e, 'FULL') + ' (x' + GetElementEditValues(e, 'TNAM') + ')|' + '⛈️|' + GetElementEditValues(e, 'MNAM') )

  else if Pos('_RECURRING_', EditorID(e)) <> 0 then
    slExport.Add(GetElementEditValues(e, 'FULL') + ' (x' + GetElementEditValues(e, 'TNAM') + ')|' + '🔁|' + GetElementEditValues(e, 'MNAM') )

  else if Pos('Expeditions', GetElementEditValues(e,'FULL')) <> 0 then
    slExport.Add(GetElementEditValues(e, 'FULL') + ' (x' + GetElementEditValues(e, 'TNAM') + ')|' + '🗺️|' + GetElementEditValues(e, 'MNAM') )

  else if Pos('Gold Star:', GetElementEditValues(e,'FULL')) <> 0 then
    slExport.Add(GetElementEditValues(e, 'FULL') + ' (x' + GetElementEditValues(e, 'TNAM') + ')|'  + '⭐|' + GetElementEditValues(e, 'MNAM') )

  else if Pos('Summer Camp:', GetElementEditValues(e,'FULL')) <> 0 then
    slExport.Add(GetElementEditValues(e, 'FULL') + ' (x' + GetElementEditValues(e, 'TNAM') + ')|'  + '🏕️|' + GetElementEditValues(e, 'MNAM') )
  
  else if Pos('Birthday:', GetElementEditValues(e,'FULL')) <> 0 then
    slExport.Add(GetElementEditValues(e, 'FULL') + ' (x' + GetElementEditValues(e, 'TNAM') + ')|'  + '🎂|'  + GetElementEditValues(e, 'MNAM'))
  
  else
    slExport.Add(GetElementEditValues(e, 'FULL') + ' (x' + GetElementEditValues(e, 'TNAM') + ')||' + GetElementEditValues(e, 'MNAM'));

end;

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
      dlgSave.FileName := 'dmp_challenges.txt';
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
