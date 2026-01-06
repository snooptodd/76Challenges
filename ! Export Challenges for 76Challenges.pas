{
  Export list of challenges for use with 76challenges.php

  it would be nice to have the script pre slelect the correct formid and sort and uniq the output. 
}
unit UserScript;

const
  // skip those records
  sRecordsToSkip = 'REFR,PGRD,PHZD,ACHR,NAVM,NAVI,LAND';

var
  slExport, slTemp, elementFullName,FullName, elementChallengeFrequency, elementRequiredCount, elementFlair, elementReward, elementReward2, formid: TStringList;
  sScore : integer;
  L0, L1, L2, L3, L4, L5, L6, L7: IInterface;

function Initialize: integer;
begin
  slExport := TStringList.Create;
end;

function Process(e: IInterface): integer;
begin
  if Pos(Signature(e), sRecordsToSkip) <> 0 then
    Exit;
  
  // This was soo simple. Why did they have to break you?
  // if (Pos('ATX_DE2023_', EditorID(e)) + Pos('SCORE_', EditorID(e))) <> 1 then
  //   Exit;

  //if Pos('Epic - ',GetElementEditValues(e, 'FULL')) = 1 then
  //begin
  //  if pos('Weekly',GetElementEditValues(e, 'CNAM') = 1 then
  //    sScore := 1500
  //  else
  //    sScore := 400;
  //end; 

  if Pos('_SUB_', EditorID(e)) <> 0 then
    Exit;
  if Pos('Sub_', EditorID(e)) <> 0 then
   Exit;
  if Pos('zzz', EditorID(e)) = 1 then
   Exit;
  if Pos('POST', EditorID(e)) = 1 then
   Exit;
  if Pos('CUT', EditorID(e)) = 1 then
   Exit;
  if Pos('ATX_DE2021_', EditorID(e)) = 1 then
   Exit;
  if Pos('ATX_DE2022_', EditorID(e)) = 1 then
   Exit;
  if Pos('ATX_DE2023_', EditorID(e)) = 1 then
   Exit;
  if Pos('ATX_DE2024_', EditorID(e)) = 1 then
   Exit;
  if Pos('TEST', EditorID(e)) = 1 then
   Exit;
  if Pos('ZZZ', EditorID(e)) = 1 then
   Exit;
  if Pos('ATOMS_', EditorID(e)) = 1 then
   Exit;
  

  formid := IntToHex(FixedFormID(e),8);
  elementFullName := GetElementEditValues(e, 'FULL');
  // if (Pos(': ',elementFullName)) > 0 then
  //   FullName := RightStr(elementFullName,StrLen(elementFullName)-Pos(': ',elementFullName)-1)
  // else
    FullName := elementFullName;
  
  elementRequiredCount := GetElementEditValues(e, 'TNAM');
  elementChallengeFrequency := GetElementEditValues(e, 'CNAM');
  elementReward := GetElementEditValues(e, 'MNAM');
  // elementChallengeCategory := GetElementEditValues(e, 'ENAM');
  // // need to check if sub challenges exist and get their info and somehow link it all together
  // elementSCFL := ElementBySignature(e, 'SCFL'); // get 'SubChallenge completion List' element
  // elementSCFL_link := LinksTo(elementSCFL); // open the flst
  // // get FormIDs of all challenges in the flst
  // numSubChallenges := ElementCount(ElementByPath(elementSCFL_link, 'FormIDs'));


  // if elementChallengeFrequency is not 'Daily', 'Weekly', 'Monthly' or 'Event', then skip it
  if (Pos('Daily', elementChallengeFrequency) = 0) and
     (Pos('Weekly', elementChallengeFrequency) = 0) and
     (Pos('Monthly', elementChallengeFrequency) = 0) and
     (Pos('Event', elementChallengeFrequency) = 0) then begin
     AddMessage('Skipping ' + EditorID(e) + ' with frequency ' + elementChallengeFrequency);
     Exit;
  end;

  // get 'rewards' element ElementByName(e,'Rewards')
  // get 'reward' child element ElementByName(rewards, 'reward') 
  // get 'DNAM' child element ElementBySignature(reward, 'DNAM')
  // open reward element (GMRW) LinksTo(DNAM)
  // do it all over again for QRCX to get GLOB
  // get FLTV from GLOB GetElementeditValues(GLOB,'FLTV')
  // we should have our scrip value for this challenge
  L0 := ElementByName(e,'Rewards');
  L1 := ElementByName(L0,'reward');
  L2 := LinksTo(ElementBySignature(L1, 'DNAM'));
  L3 := ElementByName(L2,'Rewards List');
  L4 := ElementByName(L3,'reward');
  L5 := LinksTo(ElementBySignature(L4, 'QRCX'));
  L6 := GetElementeditValues(L5,'FLTV');

  elementReward2 := StringReplace(L6,'.000000','',[rfReplaceAll, rfIgnoreCase]);

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
  
  else if Pos('_Week1', EditorID(e)) <> 0 then
    elementFlair := 'Week1' 
    
  else if Pos('_Week2', EditorID(e)) <> 0 then
    elementFlair := 'Week2' 
  
  else if Pos('event', elementChallengeFrequency) <> 0 then
    elementFlair := '' 
  
  else
    elementFlair := '';
  
  if Pos('¬¬¬¬', elementReward) <> 0 then
    elementReward := '⭐⭐⭐⭐📦';

  slTemp := FullName + '|' + elementRequiredCount + '|' + elementChallengeFrequency + '|' + elementFlair + '|' + elementReward + elementReward2 + '|' + formid;

  slExport.add(slTemp);
  //AddMessage(slTemp);
  AddMessage(EditorID(e));
  formid := '';
  FullName := '';
  elementChallengeFrequency := '';
  elementRequiredCount := '';
  elementFlair := '';
  elementReward := '';
  elementReward2 := '';

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
