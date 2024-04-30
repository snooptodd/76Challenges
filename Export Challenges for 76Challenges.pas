{
  Export list of challenges for use with 76challenges.php

  it would be nice to have the script pre slelect the correct formid and sort and uniq the output. 
}
unit UserScript;

const
  // skip those records
  sRecordsToSkip = 'REFR,PGRD,PHZD,ACHR,NAVM,NAVI,LAND';

var
  slExport, slTemp, elementFullName, elementChallengeFrequency, elementRequiredCount, elementFlair, elementReward: TStringList;
  sScore : integer;

function Initialize: integer;
begin
  slExport := TStringList.Create;
end;

function Process(e: IInterface): integer;
begin
  if Pos(Signature(e), sRecordsToSkip) <> 0 then
    Exit;
  
  if (Pos('ATX_DE2024_', EditorID(e)) + Pos('SCORE_', EditorID(e))) <> 1 then
    Exit;
  //if Pos('Epic - ',GetElementEditValues(e, 'FULL')) = 1 then
  //begin
  //  if pos('Weekly',GetElementEditValues(e, 'CNAM') = 1 then
  //    sScore := 1500
  //  else
  //    sScore := 400;
  //end; 
  if Pos('_META', EditorID(e)) <> 0 then
    Exit;
  if Pos('_SUB_', EditorID(e)) <> 0 then
    Exit;
  //if Pos('Sub_', EditorID(e)) <> 0 then
  //  Exit;
  //if Pos('zzz', EditorID(e)) <> 0 then
  //  Exit;
  //if Pos('_Epic', EditorID(e)) <> 0 then
  //  Exit;
  //if Pos('POST_', EditorID(e)) <> 0 then
  //  Exit;
  
  elementFullName := GetElementEditValues(e, 'FULL');
  elementRequiredCount := GetElementEditValues(e, 'TNAM');
  elementChallengeFrequency := GetElementEditValues(e, 'CNAM');
  elementReward := GetElementEditValues(e, 'MNAM');

  if Pos('_Halloween_', EditorID(e)) <> 0 then
    elementFlair := '🎃' 

  else if Pos('Pitt_Challenge_', EditorID(e)) <> 0 then
    elementFlair := '🪓' 

  else if Pos('_Love_', EditorID(e)) <> 0 then
    elementFlair := '❤️' 
  
  else if Pos('_Valentines_', EditorID(e)) <> 0 then
    elementFlair := '❤️' 

  else if Pos('_ST_Patrick_', EditorID(e)) <> 0 then
    elementFlair := '☘️' 

  else if Pos('_Easter', EditorID(e)) <> 0 then
    elementFlair := '🐰' 

  else if Pos('BoS_Challenge_', EditorID(e)) <> 0 then
    elementFlair := '⛈️' 

  else if Pos('_RECURRING_', EditorID(e)) <> 0 then
    elementFlair := '🔁' 

  else if Pos('_Expeditions_', EditorID(e)) <> 0 then
    elementFlair := '🗺️' 

  else if Pos('_GoldStar', EditorID(e)) <> 0 then
    elementFlair := '⭐' 

  else if Pos('_SummerCamp_', EditorID(e)) <> 0 then
    elementFlair := '🏕️'
  
  else if Pos('_Birthday_', EditorID(e)) <> 0 then
    elementFlair := '🎂' 
  
  else
    elementFlair := '';

slTemp := elementFullName + ' (x' + elementRequiredCount + ')|' + elementChallengeFrequency + '|' + elementFlair + '|' + elementReward;

slExport.add(slTemp);
//AddMessage(slTemp);

end;


function Finalize: integer;
var
  dlgSave: TSaveDialog;
  ExportFileName: string;
begin
  if slExport.Count <> 0 then begin
    //slExport.Sort;
    dlgSave := TSaveDialog.Create(nil);
    try
      dlgSave.Options := dlgSave.Options + [ofOverwritePrompt];
      dlgSave.Filter := 'text (*.txt)|*.txt';
      dlgSave.InitialDir := '\\doc\todd\src\76Challenges';
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
