$ServerInstance = "NCM\MSSQL2019"
$Database = "Scraper"
$OutputFile = "events.csv"

$Query = @"
SELECT
	FORMAT(aga.[Date], 'yyyy-MM-dd') AS [Date]
,	aga.[Title] AS [Name]
,	CASE aga.[JobId]
		WHEN 'dehelling'		THEN 'De Helling'
		WHEN 'devorstin'		THEN 'De Vorstin'
		WHEN 'ekko'				THEN 'Ekko'
		WHEN 'fluor'			THEN 'Fluor'
		WHEN 'melkweg'			THEN 'Melkweg'
		WHEN 'paradiso'			THEN 'Paradiso'
		WHEN 'tivolivredenburg' THEN 'TivoliVredenburg'
	END AS [Location]
,	CASE aga.JobId
		WHEN 'dehelling'		THEN 'Utrecht'
		WHEN 'devorstin'		THEN 'Hilversum'
		WHEN 'ekko'				THEN 'Utrecht'
		WHEN 'fluor'			THEN 'Amersfoort'
		WHEN 'melkweg'			THEN 'Amsterdam'
		WHEN 'paradiso'			THEN 'Amsterdam'
		WHEN 'tivolivredenburg' THEN 'Utrecht'
	END AS [Municipality]
,	CASE aga.[JobId]
		WHEN 'dehelling'		THEN 'De Helling'
		WHEN 'devorstin'		THEN 'De Vorstin'
		WHEN 'ekko'				THEN 'Ekko'
		WHEN 'fluor'			THEN 'Fluor'
		WHEN 'melkweg'			THEN 'Melkweg'
		WHEN 'paradiso'			THEN 'Paradiso'
		WHEN 'tivolivredenburg' THEN 'TivoliVredenburg'
	END AS [Podium]
,	att.[Name] AS [Artist]
,	gre.[Name] AS [Genre]
,	0 AS [Attendance]
,	0 AS [IsFestival]
,	FORMAT(aga.[AdditionDate], 'yyyy-MM-dd') AS [AdditionDate]
FROM 
	[Agenda] aga
LEFT JOIN
	[Music].[dbo].[Artist] att
ON
	att.[Name] = aga.Title
LEFT JOIN
	[Music].[dbo].[Genre] gre
ON
	gre.Id = att.GenreId
ORDER BY
	aga.[Date]
,	aga.[JobId]
,	aga.[Title]
"@

Invoke-SqlCmd -ServerInstance $ServerInstance -Database $Database -Query $Query 
    | Select-Object -Property Date, Name, Location, Municipality, Podium, Artist, Genre, Attendance, IsFestival, AdditionDate
    | ConvertTo-Csv -Delimiter ';' -NoTypeInformation -UseQuotes Never
    | Select-Object -Skip 1
    | Out-File -File $OutputFile -Encoding UTF8 -Force