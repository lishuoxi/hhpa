Param(
  [string]$Root = (Resolve-Path .),
  [string[]]$IncludeExt = @('*.php','*.vue','*.js','*.ts','*.json','*.scss','*.css','*.html','*.md','*.yml','*.yaml','*.ini','*.env','*.txt','*.conf'),
  [switch]$StripBomOnly
)

$ErrorActionPreference = 'Stop'

$skipDirs = @('\node_modules\','\vendor\','\dist\','\build\','\storage\','\.git\')
$utf8NoBom = New-Object System.Text.UTF8Encoding($false)

function ShouldSkip([string]$path){
  $p = $path.ToLower()
  foreach($s in $script:skipDirs){ if($p.Contains($s)) { return $true } }
  return $false
}

function HasUtf8Bom([string]$path){
  try { $fs = [System.IO.File]::OpenRead($path) } catch { return $false }
  try {
    if($fs.Length -lt 3){ return $false }
    $b0 = $fs.ReadByte(); $b1 = $fs.ReadByte(); $b2 = $fs.ReadByte()
    return ($b0 -eq 0xEF -and $b1 -eq 0xBB -and $b2 -eq 0xBF)
  } finally { $fs.Dispose() }
}

function StripBom([string]$path){
  try { $bytes = [System.IO.File]::ReadAllBytes($path) } catch { return $false }
  if($bytes.Length -ge 3 -and $bytes[0] -eq 0xEF -and $bytes[1] -eq 0xBB -and $bytes[2] -eq 0xBF){
    [System.IO.File]::WriteAllBytes($path, $bytes[3..($bytes.Length-1)])
    return $true
  }
  return $false
}

$all = @()
foreach($ext in $IncludeExt){
  $all += Get-ChildItem -Recurse -File -Path $Root -Include $ext -ErrorAction SilentlyContinue
}
$all = $all | Where-Object { -not (ShouldSkip $_.FullName) }

$processed = 0; $rewritten = 0; $bomRemoved = 0
foreach($f in $all){
  $processed++
  $path = $f.FullName
  if(HasUtf8Bom $path){
    if(StripBom $path){ $bomRemoved++; $rewritten++ }
    if($StripBomOnly){ continue }
  }
  if(-not $StripBomOnly){
    # Best-effort normalize to UTF-8 (no BOM)
    try {
      $text = [System.IO.File]::ReadAllText($path)
      [System.IO.File]::WriteAllText($path, $text, $utf8NoBom)
      $rewritten++
    } catch {
      Write-Host "Skip (read/write error): $path" -ForegroundColor Yellow
    }
  }
}

Write-Host ("Processed: {0}" -f $processed)
Write-Host ("Rewritten to UTF-8 (no BOM): {0}" -f $rewritten)
Write-Host ("BOM removed: {0}" -f $bomRemoved)

