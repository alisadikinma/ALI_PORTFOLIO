#!/bin/bash

echo "Testing MCP Playwright setup for ALI_PORTFOLIO..."

cd "C:/xampp/htdocs/ALI_PORTFOLIO"

echo "1. Checking .mcp.json configuration..."
if [ -f ".mcp.json" ]; then
    echo "✅ .mcp.json exists"
    cat .mcp.json
else
    echo "❌ .mcp.json not found"
fi

echo -e "\n2. Testing Microsoft Official Playwright MCP..."
if npm list @playwright/mcp 2>/dev/null; then
    echo "✅ @playwright/mcp is installed"
    echo "Testing server..."
    npx @playwright/mcp@latest --help 2>/dev/null && echo "✅ Server works" || echo "❌ Server issue"
else
    echo "❌ @playwright/mcp not installed"
    echo "Run: npm install @playwright/test @playwright/mcp"
fi

echo -e "\n3. Testing ExecuteAutomation Playwright MCP..."
if npm list @executeautomation/playwright-mcp-server 2>/dev/null; then
    echo "✅ @executeautomation/playwright-mcp-server is installed"
    echo "Testing server..."
    npx @executeautomation/playwright-mcp-server --help 2>/dev/null && echo "✅ Server works" || echo "❌ Server issue"
else
    echo "❌ @executeautomation/playwright-mcp-server not installed"
    echo "Run: npm install @executeautomation/playwright-mcp-server"
fi

echo -e "\n4. Checking Playwright browsers..."
if npx playwright --version 2>/dev/null; then
    echo "✅ Playwright CLI available"
    npx playwright list-browsers 2>/dev/null | head -3
else
    echo "❌ Playwright CLI not available"
    echo "Run: npm install @playwright/test && npx playwright install"
fi

echo -e "\n5. Testing Claude Code MCP detection..."
echo "To test: Run 'claude' and check if mcp__playwright tools are available"

echo -e "\nRecommendation:"
echo "1. Use Microsoft official: npm install @playwright/test @playwright/mcp"
echo "2. Keep current .mcp.json (already configured for Microsoft version)"
echo "3. Test with: claude -> check for mcp__playwright__* tools"

echo -e "\nSetup commands:"
echo "npm install @playwright/test @playwright/mcp"
echo "npx playwright install"
echo "claude"
