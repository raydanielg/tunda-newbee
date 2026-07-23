# Tunda API Test Script
# Run: .\scripts\test_api.ps1

$BASE = "http://127.0.0.1:8080/api"
$Headers = @{ Accept = "application/json"; "Content-Type" = "application/json" }

function Test-Endpoint($method, $path, $body, $token) {
    $h = $Headers.Clone()
    if ($token) { $h["Authorization"] = "Bearer $token" }
    
    Write-Host "`n========================================" -ForegroundColor Cyan
    Write-Host "  $method $path" -ForegroundColor Yellow
    Write-Host "========================================" -ForegroundColor Cyan
    
    try {
        $params = @{ Uri = "$BASE$path"; Method = $method; Headers = $h }
        if ($body) { $params["Body"] = $body }
        $res = Invoke-RestMethod @params
        $res | ConvertTo-Json -Depth 10
        Write-Host "`n[STATUS: OK]" -ForegroundColor Green
        return $res
    } catch {
        $err = $_.Exception
        if ($err.Response) {
            $stream = $err.Response.GetResponseStream()
            $reader = New-Object System.IO.StreamReader($stream)
            $errBody = $reader.ReadToEnd()
            Write-Host $errBody -ForegroundColor Red
        } else {
            Write-Host $err.Message -ForegroundColor Red
        }
        Write-Host "`n[STATUS: FAILED]" -ForegroundColor Red
        return $null
    }
}

Write-Host "`n============================================" -ForegroundColor Magenta
Write-Host "  TUNDA API TEST SUITE" -ForegroundColor Magenta
Write-Host "============================================" -ForegroundColor Magenta

# 1. LOGIN
Write-Host "`n[1] LOGIN" -ForegroundColor Magenta
$loginRes = Test-Endpoint "Post" "/auth/login" '{"email":"admin@tunda.app","password":"Admin@2026"}'
$token = $loginRes.data.token

if (-not $token) {
    Write-Host "`nNo token received. Aborting." -ForegroundColor Red
    exit
}

# 2. GET USER
Write-Host "`n[2] GET CURRENT USER" -ForegroundColor Magenta
Test-Endpoint "Get" "/auth/user" $null $token

# 3. DISCOVER PROFILES
Write-Host "`n[3] DISCOVER PROFILES" -ForegroundColor Magenta
Test-Endpoint "Get" "/discover" $null $token

# 4. SWIPE
Write-Host "`n[4] SWIPE (like)" -ForegroundColor Magenta
Test-Endpoint "Post" "/swipe" '{"swiped_id":3,"action":"like"}' $token

# 5. MATCHES
Write-Host "`n[5] MATCHES" -ForegroundColor Magenta
Test-Endpoint "Get" "/matches" $null $token

# 6. CONVERSATIONS
Write-Host "`n[6] CONVERSATIONS" -ForegroundColor Magenta
Test-Endpoint "Get" "/conversations" $null $token

# 7. NOTIFICATIONS
Write-Host "`n[7] NOTIFICATIONS" -ForegroundColor Magenta
Test-Endpoint "Get" "/notifications" $null $token

# 8. PROFILE
Write-Host "`n[8] PROFILE" -ForegroundColor Magenta
Test-Endpoint "Get" "/profile" $null $token

# 9. INTERESTS
Write-Host "`n[9] INTERESTS" -ForegroundColor Magenta
Test-Endpoint "Get" "/interests" $null $token

# 10. MY INTERESTS
Write-Host "`n[10] MY INTERESTS" -ForegroundColor Magenta
Test-Endpoint "Get" "/interests/mine" $null $token

# 11. STORIES
Write-Host "`n[11] STORIES" -ForegroundColor Magenta
Test-Endpoint "Get" "/stories" $null $token

# 12. CALLS
Write-Host "`n[12] CALLS HISTORY" -ForegroundColor Magenta
Test-Endpoint "Get" "/calls" $null $token

# 13. BOOSTS ACTIVE
Write-Host "`n[13] BOOSTS ACTIVE" -ForegroundColor Magenta
Test-Endpoint "Get" "/boosts/active" $null $token

# 14. BLOCKS
Write-Host "`n[14] BLOCKS" -ForegroundColor Magenta
Test-Endpoint "Get" "/blocks" $null $token

# 15. WALLET
Write-Host "`n[15] WALLET" -ForegroundColor Magenta
Test-Endpoint "Get" "/wallet" $null $token

# 16. WALLET TRANSACTIONS
Write-Host "`n[16] WALLET TRANSACTIONS" -ForegroundColor Magenta
Test-Endpoint "Get" "/wallet/transactions" $null $token

# 17. PAYMENTS HISTORY
Write-Host "`n[17] PAYMENTS HISTORY" -ForegroundColor Magenta
Test-Endpoint "Get" "/payments/history" $null $token

# 18. PREMIUM PLANS
Write-Host "`n[18] PREMIUM PLANS" -ForegroundColor Magenta
Test-Endpoint "Get" "/premium/plans" $null $token

# 19. PREMIUM STATUS
Write-Host "`n[19] PREMIUM STATUS" -ForegroundColor Magenta
Test-Endpoint "Get" "/premium/status" $null $token

# 20. VERIFICATION STATUS
Write-Host "`n[20] VERIFICATION STATUS" -ForegroundColor Magenta
Test-Endpoint "Get" "/verification/status" $null $token

# 21. REGISTER
Write-Host "`n[21] REGISTER (new user)" -ForegroundColor Magenta
Test-Endpoint "Post" "/auth/register" '{"name":"Test User","email":"test@tunda.app","password":"password123","password_confirmation":"password123","gender":"male","region":"Dar es Salaam","phone":"+255712999999"}'

# 22. LOGOUT
Write-Host "`n[22] LOGOUT" -ForegroundColor Magenta
Test-Endpoint "Post" "/auth/logout" $null $token

Write-Host "`n============================================" -ForegroundColor Magenta
Write-Host "  ALL TESTS COMPLETE" -ForegroundColor Magenta
Write-Host "============================================`n" -ForegroundColor Magenta
