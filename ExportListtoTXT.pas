{
  Generate text file of list contents
}
unit UserScript;

const
  // skip those records
  sRecordsToSkip = '';

var
  slExport: TStringList;
  listEntries, currentListEntry: IInterface;
  i: integer;

function Initialize: integer;
begin
  slExport := TStringList.Create;
end;

function Process(e: IInterface): integer;
begin
listEntries := ElementByPath(e, 'FormIDs'); // https://ptb.discord.com/channels/471930020454072348/483466910571167746/1101658577023803442
  for i := 0 to ElementCount(listEntries) - 1 do begin
        currentListEntry := ElementByIndex(listEntries, i);
        slExport.Add(GetElementEditValues(LinksTo(currentListEntry), 'FULL'));
  end;
  slExport.Sort;
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
      dlgSave.Filter := '*.txt';
      dlgSave.InitialDir := ScriptsPath;
      dlgSave.FileName := 'List.txt';
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
